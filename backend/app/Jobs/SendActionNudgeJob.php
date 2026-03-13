<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendActionNudgeJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public \App\Models\User $user,
        public \App\Models\Pathway $pathway,
        public \App\Models\UserTimelineStep $step
    ) {}

    public function handle(): void
    {
        // 1. Create In-App Notification
        \App\Models\Notification::create([
            'user_id' => $this->user->id,
            'title' => 'Action Required: ' . $this->step->title,
            'message' => 'It looks like you might be stuck on this step. Let\'s keep your relocation roadmap moving!',
        ]);

        // 2. Send Email if user has opted in
        if ($this->user->email_notifications) {
            \Illuminate\Support\Facades\Mail::to($this->user->email)
                ->send(new \App\Mail\ActionNudgeMail($this->user, $this->pathway, $this->step));
        }
    }
}
