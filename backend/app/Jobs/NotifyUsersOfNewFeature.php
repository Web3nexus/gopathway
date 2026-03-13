<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifyUsersOfNewFeature implements ShouldQueue
{
    use Queueable;

    public $feature;

    /**
     * Create a new job instance.
     */
    public function __construct(\App\Models\PlatformFeature $feature)
    {
        $this->feature = $feature;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            // 1. In-App Notification
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => '🚀 New Feature Released: ' . $this->feature->feature_name,
                'message' => $this->feature->description,
                'is_read' => false,
            ]);

            // 2. Email (if opted in)
            if ($user->email_notifications) {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(
                    new \App\Mail\NewFeatureAnnouncement($this->feature)
                );
            }
        }
    }
}