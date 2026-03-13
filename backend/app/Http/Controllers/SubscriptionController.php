<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\PaystackService;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use App\Services\FlutterwaveService;

class SubscriptionController extends Controller
{
    protected PaystackService $paystackService;
    protected FlutterwaveService $flutterwaveService;
    protected \App\Services\ReferralService $referralService;
    protected CurrencyService $currencyService;

    public function __construct(
        PaystackService $paystackService,
        FlutterwaveService $flutterwaveService,
        \App\Services\ReferralService $referralService,
        CurrencyService $currencyService
        )
    {
        $this->paystackService = $paystackService;
        $this->flutterwaveService = $flutterwaveService;
        $this->referralService = $referralService;
        $this->currencyService = $currencyService;
    }

    /**
     * List all active subscription plans.
     */
    public function plans(Request $request)
    {
        $user = $request->user();
        $targetCurrency = 'USD';

        if ($user) {
            $activePathway = $user->activePathway()->with('country')->first();
            if ($activePathway && $activePathway->country) {
                $targetCurrency = $this->currencyService->getCurrencyForCountry($activePathway->country);
            }
        }

        $plans = SubscriptionPlan::where('is_active', true)->get()->map(function ($plan) use ($targetCurrency) {
            $price = $plan->price;
            $currency = 'USD';

            // Check if localized price exists
            if ($plan->prices && isset($plan->prices[$targetCurrency])) {
                $price = $plan->prices[$targetCurrency];
                $currency = $targetCurrency;
            }
            elseif ($targetCurrency !== 'USD') {
                // Otherwise convert from base price (USD)
                $rate = $this->currencyService->getRateFor($targetCurrency);
                $price = $plan->price * $rate;
                $currency = $targetCurrency;
            }

            return [
            'id' => $plan->id,
            'name' => $plan->name,
            'slug' => $plan->slug,
            'tier' => $plan->tier,
            'base_price' => $plan->price,
            'display_price' => $price,
            'display_currency' => $currency,
            'interval' => $plan->interval,
            'features' => $plan->features,
            'description' => $plan->description,
            ];
        });

        $activeGateway = Setting::where('key', 'payment_gateway_active')->value('value') ?: 'paystack';
        $paystackKey = Setting::where('key', 'paystack_public_key')->value('value') ?: env('PAYSTACK_PUBLIC_KEY', '');
        $flutterwaveKey = Setting::where('key', 'flutterwave_public_key')->value('value') ?: env('FLUTTERWAVE_PUBLIC_KEY', '');

        return response()->json([
            'data' => $plans,
            'detected_currency' => $targetCurrency,
            'gateways' => [
                'active' => $activeGateway,
                'paystack_public' => $paystackKey,
                'flutterwave_public' => $flutterwaveKey,
            ]
        ]);
    }

    /**
     * Get the user's current subscription.
     */
    public function current(Request $request)
    {
        $subscription = $request->user()->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$subscription) {
            $subscription = $request->user()->subscriptions()
                ->with('plan')
                ->latest()
                ->first();
        }

        $activeGateway = Setting::where('key', 'payment_gateway_active')->value('value') ?: 'paystack';

        return response()->json([
            'data' => $subscription,
            'active_gateway' => $activeGateway
        ]);
    }

    /**
     * Download all invoices as CSV.
     */
    public function downloadInvoices(Request $request)
    {
        $user = $request->user();
        $logs = $user->paymentLogs()->latest()->get();

        $filename = "invoices_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Date', 'Plan', 'Amount', 'Currency', 'Reference', 'Status'];

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i'),
                    $log->plan_name,
                    $log->amount,
                    $log->currency,
                    $log->reference,
                    $log->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Initialize subscription payment.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'currency' => 'nullable|string|size:3',
            'gateway' => 'nullable|string|in:paystack,flutterwave',
        ]);

        $plan = SubscriptionPlan::find($validated['plan_id']);
        $user = $request->user();

        // If plan is free, just activate it directly
        if ($plan->price <= 0) {
            $user->subscriptions()->update(['status' => 'inactive']);
            $user->subscriptions()->create([
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
            ]);
            return response()->json(['message' => 'Free plan activated successfully']);
        }

        // Determine currency and amount
        $targetCurrency = $validated['currency'] ?? 'USD';

        // If no currency provided, try to detect from pathway
        if (!$validated['currency']) {
            $activePathway = $user->activePathway()->with('country')->first();
            if ($activePathway && $activePathway->country) {
                $targetCurrency = $this->currencyService->getCurrencyForCountry($activePathway->country);
            }
        }

        $amount = $plan->price;
        if ($plan->prices && isset($plan->prices[$targetCurrency])) {
            $amount = $plan->prices[$targetCurrency];
        }
        elseif ($targetCurrency !== 'USD') {
            $rate = $this->currencyService->getRateFor($targetCurrency);
            $amount = $plan->price * $rate;
        }

        $gatewayChoice = $validated['gateway'] ?? Setting::where('key', 'payment_gateway_active')->value('value') ?? 'paystack';
        if ($gatewayChoice === 'both') {
            $gatewayChoice = 'paystack'; // Default to paystack if not explicitly sent when both are active
        }

        try {
            if ($gatewayChoice === 'flutterwave') {
                $checkoutData = $this->flutterwaveService->initializeTransaction([
                    'tx_ref' => 'sub_' . uniqid() . '_' . time(),
                    'amount' => $amount,
                    'currency' => $targetCurrency,
                    'redirect_url' => config('app.frontend_url') . '/billing/verify?gateway=flutterwave',
                    'customer' => [
                        'email' => $user->email,
                        'name' => $user->name,
                    ],
                    'meta' => [
                        'plan_id' => $plan->id,
                        'user_id' => $user->id,
                        'original_amount' => $amount,
                        'original_currency' => $targetCurrency,
                    ],
                    'customizations' => [
                        'title' => 'GoPathway Subscription',
                        'description' => $plan->name . ' Plan',
                    ]
                ]);
            } else {
                $checkoutData = $this->paystackService->initializeTransaction([
                    'email' => $user->email,
                    'amount' => (int)($amount * 100), // Kobo / Cents
                    'currency' => $targetCurrency,
                    'callback_url' => config('app.frontend_url') . '/billing/verify?gateway=paystack',
                    'metadata' => [
                        'plan_id' => $plan->id,
                        'user_id' => $user->id,
                        'original_amount' => $amount,
                        'original_currency' => $targetCurrency,
                    ],
                ]);
            }

            return response()->json(['data' => $checkoutData, 'gateway' => $gatewayChoice]);
        }
        catch (\Exception $e) {
            Log::error('Subscription Payment Initialization Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Verify payment after redirect.
     */
    public function verify(Request $request)
    {
        $gateway = $request->query('gateway', 'paystack');
        $user = $request->user();

        if ($gateway === 'flutterwave') {
            $transactionId = $request->query('transaction_id');
            if (!$transactionId) {
                return response()->json(['message' => 'Transaction ID not provided'], 400);
            }

            $paymentData = $this->flutterwaveService->verifyTransaction($transactionId);

            if ($paymentData && $paymentData['status'] === 'successful') {
                $planId = $paymentData['meta']['plan_id'];
                
                DB::transaction(function () use ($user, $planId, $paymentData) {
                    $plan = SubscriptionPlan::find($planId);
                    $interval = $plan->interval ?? 'month';
                    $endsAt = $interval === 'year' ? now()->addYear() : now()->addMonth();

                    $user->subscriptions()->update(['status' => 'inactive']);
                    $user->subscriptions()->create([
                        'subscription_plan_id' => $planId,
                        'paystack_id' => $paymentData['id'], // Using same column for simplicity or add flutterwave_id
                        'paystack_code' => $paymentData['tx_ref'],
                        'status' => 'active',
                        'starts_at' => now(),
                        'ends_at' => $endsAt,
                    ]);

                    $user->paymentLogs()->create([
                        'amount' => $paymentData['amount'],
                        'currency' => $paymentData['currency'],
                        'reference' => $paymentData['tx_ref'],
                        'status' => 'success',
                        'plan_name' => $plan->name,
                    ]);

                    $this->referralService->recordPayment($user, $paymentData['amount'], $paymentData['tx_ref']);
                });

                return response()->json(['message' => 'Subscription successful']);
            }
        } 
        else {
            $reference = $request->query('reference');
            if (!$reference) {
                return response()->json(['message' => 'Reference not provided'], 400);
            }

            $paymentData = $this->paystackService->verifyTransaction($reference);

            if ($paymentData && $paymentData['status'] === 'success') {
                $planId = $paymentData['metadata']['plan_id'];

                DB::transaction(function () use ($user, $planId, $paymentData) {
                    $plan = SubscriptionPlan::find($planId);
                    $interval = $plan->interval ?? 'month';
                    $endsAt = $interval === 'year' ? now()->addYear() : now()->addMonth();

                    $user->subscriptions()->update(['status' => 'inactive']);
                    $user->subscriptions()->create([
                        'subscription_plan_id' => $planId,
                        'paystack_id' => $paymentData['id'],
                        'paystack_code' => $paymentData['reference'],
                        'status' => 'active',
                        'starts_at' => now(),
                        'ends_at' => $endsAt,
                    ]);

                    $user->paymentLogs()->create([
                        'amount' => $paymentData['amount'] / 100,
                        'currency' => $paymentData['currency'],
                        'reference' => $paymentData['reference'],
                        'status' => 'success',
                        'plan_name' => $plan->name,
                    ]);

                    $this->referralService->recordPayment($user, $paymentData['amount'] / 100, $paymentData['reference']);
                });

                return response()->json(['message' => 'Subscription successful']);
            }
        }

        return response()->json(['message' => 'Payment verification failed'], 400);
    }

    /**
     * Get user's payment history.
     */
    public function history(Request $request)
    {
        return response()->json([
            'data' => $request->user()->paymentLogs()->latest()->get()
        ]);
    }
}