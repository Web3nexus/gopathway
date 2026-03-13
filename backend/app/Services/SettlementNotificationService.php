<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\SettlementStep;
use App\Models\UserSettlementProgress;
use Carbon\Carbon;

class SettlementNotificationService
{
    /**
     * Send reminders for incomplete mandatory settlement steps.
     */
    public function sendReminders()
    {
        // For a real app, logic would be:
        // 1. Find users with active pathways.
        // 2. Find incomplete mandatory settlement steps for those countries.
        // 3. Check if we've already sent a reminder recently.
        // 4. Create a notification.

        $users = User::whereHas('pathway', function ($q) {
            $q->where('status', 'active');
        })->get();

        foreach ($users as $user) {
            $pathway = $user->pathway()->where('status', 'active')->first();
            if (!$pathway)
                continue;

            $countryId = $pathway->country_id;

            // Find mandatory steps that are NOT in user_settlement_progress
            $completedStepIds = UserSettlementProgress::where('user_id', $user->id)
                ->pluck('settlement_step_id');

            $pendingMandatorySteps = SettlementStep::where('country_id', $countryId)
                ->where('mandatory', true)
                ->whereNotIn('id', $completedStepIds)
                ->get();

            foreach ($pendingMandatorySteps as $step) {
                // Send a generic reminder for now
                Notification::updateOrCreate([
                    'user_id' => $user->id,
                    'title' => "Reminder: {$step->title}",
                ], [
                    'message' => "Don't forget to complete the '{$step->title}' step for your relocation to {$pathway->country->name}. This is a mandatory requirement.",
                    'is_read' => false,
                ]);
            }
        }
    }
}