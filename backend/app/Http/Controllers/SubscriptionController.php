<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\PaystackService;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    protected PaystackService $paystackService;
    protected \App\Services\ReferralService $referralService;
    protected CurrencyService $currencyService;

    public function __construct(
        PaystackService $paystackService,
        \App\Services\ReferralService $referralService,
        CurrencyService $currencyService
        )
    {
        $this->paystackService = $paystackService;
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

        return response()->json([
            'data' => $plans,
            'detected_currency' => $targetCurrency
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

        return response()->json([
            'data' => $subscription
        ]);
    }

    /**
     * Initialize subscription payment.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'currency' => 'nullable|string|size:3',
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

        // Paystack supports NGN, GHS, KES, ZAR, and USD (if configured)
        // For others, we might want to default to USD, but let's try the target first.

        try {
            $checkoutData = $this->paystackService->initializeTransaction([
                'email' => $user->email,
                'amount' => (int)($amount * 100), // Kobo / Cents
                'currency' => $targetCurrency,
                'callback_url' => config('app.frontend_url') . '/billing/verify',
                'metadata' => [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'original_amount' => $amount,
                    'original_currency' => $targetCurrency,
                ],
            ]);

            return response()->json(['data' => $checkoutData]);
        }
        catch (\Exception $e) {
            Log::error('Paystack Sub Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Verify payment after redirect.
     */
    public function verify(Request $request)
    {
        $reference = $request->query('reference');
        if (!$reference) {
            return response()->json(['message' => 'Reference not provided'], 400);
        }

        $paymentData = $this->paystackService->verifyTransaction($reference);

        if ($paymentData && $paymentData['status'] === 'success') {
            $planId = $paymentData['metadata']['plan_id'];
            $user = $request->user();

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

                // Create Payment Log
                $user->paymentLogs()->create([
                    'amount' => $paymentData['amount'] / 100,
                    'currency' => $paymentData['currency'],
                    'reference' => $paymentData['reference'],
                    'status' => 'success',
                    'plan_name' => $plan->name,
                ]);

                // Record Referral Commission
                $this->referralService->recordPayment($user, $paymentData['amount'] / 100, $paymentData['reference']);
            });

            return response()->json(['message' => 'Subscription successful']);
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