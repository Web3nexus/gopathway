<?php

namespace App\Console\Commands;

use App\Mail\DynamicEmail;
use App\Models\Notification;
use App\Models\Scholarship;
use App\Models\School;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckDeadlinesCommand extends Command
{
    protected $signature = 'app:check-deadlines';
    protected $description = 'Check scholarship and school admission deadlines and notify users.';

    public function handle(): void
    {
        $this->info('Checking scholarship deadlines...');
        $this->checkScholarships();

        $this->info('Checking school admission deadlines...');
        $this->checkSchoolAdmissions();

        $this->info('Done.');
    }

    /**
     * Check scholarship opening and closing dates.
     */
    protected function checkScholarships(): void
    {
        $today = now()->toDateString();
        $in7days = now()->addDays(7)->toDateString();
        $in2days = now()->addDays(2)->toDateString();

        // Just opened today
        $opened = Scholarship::approved()->whereDate('opening_date', $today)->get();
        foreach ($opened as $scholarship) {
            $this->notifyAllUsers(
                "🎓 Scholarship Now Open: {$scholarship->title}",
                "The scholarship '{$scholarship->title}' by {$scholarship->provider} is now open for applications! Apply before the deadline.",
                'scholarship_opened',
                ['scholarship_id' => $scholarship->id, 'application_link' => $scholarship->application_link]
            );
        }

        // Closing in 7 days
        $closing7 = Scholarship::approved()->whereDate('deadline', $in7days)->get();
        foreach ($closing7 as $scholarship) {
            $this->notifyAllUsers(
                "⏰ Scholarship Closing in 7 Days: {$scholarship->title}",
                "Hurry! The scholarship '{$scholarship->title}' closes in 7 days on " . $scholarship->deadline->format('M d, Y') . ". Don't miss your chance!",
                'scholarship_closing_soon',
                ['scholarship_id' => $scholarship->id, 'application_link' => $scholarship->application_link]
            );
        }

        // Closing in 2 days
        $closing2 = Scholarship::approved()->whereDate('deadline', $in2days)->get();
        foreach ($closing2 as $scholarship) {
            $this->notifyAllUsers(
                "🚨 Last Chance! Scholarship Closing in 2 Days: {$scholarship->title}",
                "Final reminder! '{$scholarship->title}' closes in just 2 days. Apply now at: {$scholarship->application_link}",
                'scholarship_closing_urgent',
                ['scholarship_id' => $scholarship->id, 'application_link' => $scholarship->application_link]
            );
        }
    }

    /**
     * Check school admission opening and deadline dates.
     */
    protected function checkSchoolAdmissions(): void
    {
        $today = now()->toDateString();
        $in7days = now()->addDays(7)->toDateString();
        $in2days = now()->addDays(2)->toDateString();

        // Admissions just opened
        $opened = School::where('is_active', true)->whereDate('admission_opening_date', $today)->get();
        foreach ($opened as $school) {
            $trackers = $school->trackers()->with('profile')->get();
            $this->notifyTrackers(
                $trackers,
                "🏫 Admissions Now Open: {$school->name}",
                "Great news! Admissions at {$school->name} are now open. Visit the portal to start your application.",
                'school_admission_opened',
                ['school_id' => $school->id, 'application_portal' => $school->application_portal]
            );
        }

        // Admissions closing in 7 days
        $closing7 = School::where('is_active', true)->whereDate('admission_deadline_date', $in7days)->get();
        foreach ($closing7 as $school) {
            $trackers = $school->trackers()->get();
            $this->notifyTrackers(
                $trackers,
                "⏰ Admission Deadline in 7 Days: {$school->name}",
                "The application deadline for {$school->name} is in 7 days on " . $school->admission_deadline_date->format('M d, Y') . ". Submit your application soon!",
                'school_admission_closing_soon',
                ['school_id' => $school->id, 'application_portal' => $school->application_portal]
            );
        }

        // Admissions closing in 2 days
        $closing2 = School::where('is_active', true)->whereDate('admission_deadline_date', $in2days)->get();
        foreach ($closing2 as $school) {
            $trackers = $school->trackers()->get();
            $this->notifyTrackers(
                $trackers,
                "🚨 Final Notice! Admission Deadline in 2 Days: {$school->name}",
                "This is your last reminder! The application deadline for {$school->name} is in 2 days. Visit: {$school->application_portal}",
                'school_admission_closing_urgent',
                ['school_id' => $school->id, 'application_portal' => $school->application_portal]
            );
        }
    }

    /**
     * Notify all active users (for global scholarship events).
     */
    protected function notifyAllUsers(string $title, string $body, string $type, array $data = []): void
    {
        $users = User::where('email_notifications', true)->get();
        $count = 0;

        foreach ($users as $user) {
            // In-app notification
            Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $body,
                'is_read' => false,
            ]);

            // Email
            try {
                Mail::to($user->email)->send(new DynamicEmail('deadline_alert', [
                    'user_name' => $user->name,
                    'alert_title' => $title,
                    'alert_body' => $body,
                    'action_url' => $data['application_link'] ?? config('app.frontend_url') . '/scholarships',
                    'action_label' => 'View Scholarship',
                ]));
            } catch (\Exception $e) {
                Log::error("CheckDeadlines: Email failed for {$user->email}: " . $e->getMessage());
            }

            $count++;
        }

        $this->info("  Notified {$count} users: {$title}");
    }

    /**
     * Notify only users tracking a specific school.
     */
    protected function notifyTrackers($trackers, string $title, string $body, string $type, array $data = []): void
    {
        $count = 0;

        foreach ($trackers as $user) {
            // In-app notification
            Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $body,
                'is_read' => false,
            ]);

            // Email
            try {
                Mail::to($user->email)->send(new DynamicEmail('deadline_alert', [
                    'user_name' => $user->name,
                    'alert_title' => $title,
                    'alert_body' => $body,
                    'action_url' => $data['application_portal'] ?? config('app.frontend_url') . '/schools',
                    'action_label' => 'View School Portal',
                ]));
            } catch (\Exception $e) {
                Log::error("CheckDeadlines: Email failed for {$user->email}: " . $e->getMessage());
            }

            $count++;
        }

        $this->info("  Notified {$count} trackers: {$title}");
    }
}
