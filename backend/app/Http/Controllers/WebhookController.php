<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Verify Paystack Signature
        $secretKey = config('services.paystack.secret_key') ?? env('PAYSTACK_SECRET_KEY');
        $signature = $request->header('x-paystack-signature');

        if (!$signature || $signature !== hash_hmac('sha512', $request->getContent(), $secretKey)) {
            Log::warning('Paystack Webhook Signature Mismatch');
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $event = $request->input('event');
        $data = $request->input('data');

        Log::info('Paystack Webhook Received', ['event' => $event]);

        switch ($event) {
            case 'charge.success':
            case 'subscription.create':
                $this->handleSubscriptionSuccess($data);
                break;
            case 'subscription.disable':
                $this->handleSubscriptionDisabled($data);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleSubscriptionSuccess($data)
    {
        $email = $data['customer']['email'];
        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::error('Webhook User Not Found', ['email' => $email]);
            return;
        }

        // If metadata has plan_id, it's likely a transaction initialized by our app
        $planId = $data['metadata']['plan_id'] ?? null;

        // If it's a Paystack Subscription (recurring), they might not have plan_id in metadata 
        // but they have specialized fields. For now, let's focus on the metadata path.

        if ($planId) {
            // Find current active/recent subscription for this plan
            $subscription = $user->subscriptions()
                ->where('subscription_plan_id', $planId)
                ->latest()
                ->first();

            if ($subscription && ($subscription->status === 'active' || $subscription->ends_at->isFuture() || $subscription->ends_at->diffInDays(now()) < 7)) {
                // It's a renewal or extension
                $subscription->extend();
                Log::info('Subscription Extended via Webhook', ['user' => $user->id, 'plan' => $planId]);
            }
            else {
                // New subscription or long expired
                $plan = \App\Models\SubscriptionPlan::find($planId);
                $interval = $plan->interval ?? 'month';
                $endsAt = $interval === 'year' ? now()->addYear() : now()->addMonth();

                $user->subscriptions()->update(['status' => 'inactive']);
                $user->subscriptions()->create([
                    'subscription_plan_id' => $planId,
                    'paystack_id' => $data['id'] ?? null,
                    'paystack_code' => $data['reference'] ?? null,
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => $endsAt,
                ]);
                Log::info('New Subscription Created via Webhook', ['user' => $user->id, 'plan' => $planId]);
            }
        }

        // Record Referral Commission
        $referralService = app(\App\Services\ReferralService::class);
        $referralService->recordPayment($user, $data['amount'] / 100, $data['reference']);
    }

    protected function handleSubscriptionDisabled($data)
    {
        $paystackCode = $data['subscription_code'];
        Subscription::where('paystack_code', $paystackCode)->update(['status' => 'cancelled']);
    }
}