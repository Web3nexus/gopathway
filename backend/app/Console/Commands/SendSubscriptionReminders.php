<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendSubscriptionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-subscription-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders to users whose subscriptions are expiring in 3 days.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiryDate = now()->addDays(3)->toIdString(); // Get the date 3 days from now
        
        $subscriptions = \App\Models\Subscription::whereDate('ends_at', now()->addDays(3)->toDateString())
            ->where('status', 'active')
            ->with(['user', 'plan'])
            ->get();

        $this->info("Found " . $subscriptions->count() . " subscriptions expiring in 3 days.");

        foreach ($subscriptions as $subscription) {
            try {
                \Illuminate\Support\Facades\Mail::to($subscription->user->email)->send(new \App\Mail\DynamicEmail('subscription_reminder', [
                    'user_name' => $subscription->user->name,
                    'plan_name' => $subscription->plan->name ?? 'Premium Plan',
                    'expiry_date' => $subscription->ends_at->format('M d, Y'),
                    'billing_url' => config('app.frontend_url') . '/billing',
                ]));
                $this->info("Reminder sent to: " . $subscription->user->email);
            } catch (\Exception $e) {
                $this->error("Failed to send reminder to {$subscription->user->email}: " . $e->getMessage());
            }
        }

        $this->info('Subscription reminders processed successfully.');
    }
}
