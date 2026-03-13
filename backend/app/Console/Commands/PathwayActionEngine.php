<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PathwayActionEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pathway-action-engine {--test : Run without dispatching jobs}';

    protected $description = 'Scans user pathways and dispatches customized action nudges';

    public function handle()
    {
        $testMode = $this->option('test');
        $this->info("Starting Pathway Action Engine" . ($testMode ? ' (TEST MODE)' : '') . "...");

        // Look for users with active pathways
        $users = \App\Models\User::whereHas('pathway', function ($q) {
            $q->where('status', 'active');
        })->with(['pathway' => function ($q) {
            $q->where('status', 'active')->with('visaType');
        }, 'timelineSteps'])->get();

        $notificationsSent = 0;

        foreach ($users as $user) {
            $pathway = $user->pathway->first();
            if (!$pathway) continue;

            // Find the first pending step
            $currentStep = $user->timelineSteps()
                ->where('status', 'pending')
                ->where('pathway_id', $pathway->id)
                ->orderBy('order')
                ->first();

            if (!$currentStep) {
                continue; // User is done or has no steps
            }

            // Check if user has been stuck on this step for > 3 days
            $lastCompletedStep = $user->timelineSteps()
                ->where('status', 'completed')
                ->where('pathway_id', $pathway->id)
                ->orderByDesc('completed_at')
                ->first();

            $dateToMeasureFrom = $lastCompletedStep?->completed_at ?? $pathway->created_at;

            if ($dateToMeasureFrom->diffInDays(now()) >= 3) {
                // Check if we already nudged them for this specific step recently (e.g., in the last 7 days)
                $recentNudge = \App\Models\UserActionLog::where('user_id', $user->id)
                    ->where('pathway_id', $pathway->id)
                    ->where('action_type', "timeline_nudge_{$currentStep->id}")
                    ->where('sent_at', '>=', now()->subDays(7))
                    ->exists();

                if (!$recentNudge) {
                    $this->info("Nudging User {$user->email} to complete step: {$currentStep->title}");
                    
                    if (!$testMode) {
                        dispatch(new \App\Jobs\SendActionNudgeJob($user, $pathway, $currentStep));
                        
                        \App\Models\UserActionLog::create([
                            'user_id' => $user->id,
                            'pathway_id' => $pathway->id,
                            'action_type' => "timeline_nudge_{$currentStep->id}",
                            'sent_at' => now(),
                        ]);
                    }
                    $notificationsSent++;
                }
            }
        }

        $this->info("Engine complete. {$notificationsSent} nudges " . ($testMode ? 'simulated' : 'dispatched') . ".");
    }
}
