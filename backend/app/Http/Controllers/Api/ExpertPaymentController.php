<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpertTransaction;
use App\Models\ExpertWithdrawal;
use App\Models\Setting;
use App\Models\User;
use App\Services\PaystackService;
use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpertPaymentController extends Controller
{
    protected PaystackService $paystackService;
    protected FlutterwaveService $flutterwaveService;

    public function __construct(PaystackService $paystackService, FlutterwaveService $flutterwaveService)
    {
        $this->paystackService = $paystackService;
        $this->flutterwaveService = $flutterwaveService;
    }

    public function initializePayment(Request $request)
    {
        $validated = $request->validate([
            'expert_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string|size:3',
            'gateway' => 'nullable|string|in:paystack,flutterwave',
            'description' => 'required|string|max:255',
            'cf_turnstile_response' => ['sometimes', new \App\Rules\Turnstile()],
        ]);

        $user = $request->user();
        if ($user->id == $validated['expert_id']) {
            return response()->json(['message' => 'You cannot pay yourself.'], 400);
        }

        $expert = User::findOrFail($validated['expert_id']);
        if (!$expert->hasRole('Professional')) {
            return response()->json(['message' => 'The receiving user is not an expert.'], 400);
        }

        $currency = $validated['currency'] ?? 'USD';
        $gatewayChoice = $validated['gateway'] ?? Setting::where('key', 'payment_gateway_active')->value('value') ?? 'paystack';
        if ($gatewayChoice === 'both') $gatewayChoice = 'paystack';

        try {
            if ($gatewayChoice === 'flutterwave') {
                $checkoutData = $this->flutterwaveService->initializeTransaction([
                    'tx_ref' => 'exp_' . uniqid() . '_' . time(),
                    'amount' => $validated['amount'],
                    'currency' => $currency,
                    'redirect_url' => config('app.frontend_url') . '/dashboard/messages?payment=verify&gateway=flutterwave',
                    'customer' => ['email' => $user->email, 'name' => $user->name],
                    'meta' => [
                        'type' => 'expert_payment',
                        'expert_id' => $expert->id,
                        'user_id' => $user->id,
                        'description' => $validated['description'],
                    ],
                    'customizations' => [
                        'title' => 'Expert Service Payment',
                        'description' => $validated['description'],
                    ]
                ]);
            } else {
                $checkoutData = $this->paystackService->initializeTransaction([
                    'email' => $user->email,
                    'amount' => (int)($validated['amount'] * 100),
                    'currency' => $currency,
                    'callback_url' => config('app.frontend_url') . '/dashboard/messages?payment=verify&gateway=paystack',
                    'metadata' => [
                        'type' => 'expert_payment',
                        'expert_id' => $expert->id,
                        'user_id' => $user->id,
                        'description' => $validated['description'],
                    ],
                ]);
            }

            return response()->json(['data' => $checkoutData, 'gateway' => $gatewayChoice]);
        } catch (\Exception $e) {
            Log::error('Expert Payment Initialization Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function verifyPayment(Request $request)
    {
        $gateway = $request->query('gateway', 'paystack');

        try {
            if ($gateway === 'flutterwave') {
                $transactionId = $request->query('transaction_id');
                if (!$transactionId) return response()->json(['message' => 'Transaction ID missing'], 400);

                $paymentData = $this->flutterwaveService->verifyTransaction($transactionId);
                if ($paymentData && $paymentData['status'] === 'successful') {
                    $this->processPaymentSuccess($paymentData['meta'], $paymentData['amount'], $paymentData['currency'], $paymentData['tx_ref'], 'flutterwave');
                    return response()->json(['message' => 'Payment successful']);
                }
            } else {
                $reference = $request->query('reference');
                if (!$reference) return response()->json(['message' => 'Reference missing'], 400);

                $paymentData = $this->paystackService->verifyTransaction($reference);
                if ($paymentData && $paymentData['status'] === 'success') {
                    $this->processPaymentSuccess($paymentData['metadata'], $paymentData['amount'] / 100, $paymentData['currency'], $paymentData['reference'], 'paystack');
                    return response()->json(['message' => 'Payment successful']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Expert Payment Verification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Verification failed'], 400);
        }

        return response()->json(['message' => 'Payment verification failed'], 400);
    }

    private function processPaymentSuccess($meta, $amount, $currency, $reference, $gateway)
    {
        if (!isset($meta['type']) || $meta['type'] !== 'expert_payment') return;

        // Check if already processed
        if (ExpertTransaction::where('reference', $reference)->exists()) return;

        $commissionPercent = (float) (Setting::where('key', 'expert_commission_percentage')->value('value') ?? 20);
        $commissionAmount = ($amount * $commissionPercent) / 100;
        $netAmount = $amount - $commissionAmount;

        ExpertTransaction::create([
            'user_id' => $meta['user_id'],
            'expert_id' => $meta['expert_id'],
            'amount' => $amount,
            'commission_amount' => $commissionAmount,
            'net_amount' => $netAmount,
            'currency' => $currency,
            'gateway' => $gateway,
            'reference' => $reference,
            'status' => 'completed',
            'description' => $meta['description'] ?? 'Expert Service Payment',
        ]);
    }

    public function expertStats(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('Professional')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $totalEarnings = ExpertTransaction::where('expert_id', $user->id)->where('status', 'completed')->sum('net_amount');
        $totalWithdrawn = ExpertWithdrawal::where('expert_id', $user->id)->whereIn('status', ['approved', 'processed'])->sum('amount');
        $pendingWithdrawals = ExpertWithdrawal::where('expert_id', $user->id)->where('status', 'pending')->sum('amount');
        
        $availableBalance = $totalEarnings - $totalWithdrawn - $pendingWithdrawals;

        $history = ExpertTransaction::where('expert_id', $user->id)->with('user:id,first_name,last_name,name')->latest()->get();
        
        $maskedHistory = $history->map(function ($tx) {
            $maskedName = mb_substr($tx->user->first_name ?? $tx->user->name, 0, 2) . '****' . mb_substr($tx->user->first_name ?? $tx->user->name, -1);
            return [
                'id' => $tx->id,
                'user_name' => $maskedName,
                'amount' => $tx->amount,
                'commission' => $tx->commission_amount,
                'net_amount' => $tx->net_amount,
                'currency' => $tx->currency,
                'description' => $tx->description,
                'date' => $tx->created_at,
                'status' => $tx->status,
            ];
        });

        $withdrawals = ExpertWithdrawal::where('expert_id', $user->id)->latest()->get();

        return response()->json([
            'stats' => [
                'total_earnings' => $totalEarnings,
                'total_withdrawn' => $totalWithdrawn,
                'pending_withdrawals' => $pendingWithdrawals,
                'available_balance' => max(0, $availableBalance),
            ],
            'transactions' => $maskedHistory,
            'withdrawals' => $withdrawals,
        ]);
    }

    public function requestWithdrawal(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('Professional')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
            'currency' => 'nullable|string|size:3',
            'payout_details' => 'required|string',
        ]);

        $totalEarnings = ExpertTransaction::where('expert_id', $user->id)->where('status', 'completed')->sum('net_amount');
        $totalWithdrawn = ExpertWithdrawal::where('expert_id', $user->id)->whereIn('status', ['approved', 'processed'])->sum('amount');
        $pendingWithdrawals = ExpertWithdrawal::where('expert_id', $user->id)->where('status', 'pending')->sum('amount');
        
        $availableBalance = $totalEarnings - $totalWithdrawn - $pendingWithdrawals;

        if ($validated['amount'] > $availableBalance) {
            return response()->json(['message' => 'Insufficient available balance'], 400);
        }

        $withdrawal = ExpertWithdrawal::create([
            'expert_id' => $user->id,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'USD',
            'status' => 'pending',
            'payout_details' => $validated['payout_details'],
        ]);

        return response()->json(['message' => 'Withdrawal requested successfully', 'data' => $withdrawal]);
    }
}
