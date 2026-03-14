<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class MailConfigService
{
    /**
     * Apply SMTP settings from the database to the application configuration.
     */
    public static function apply(): void
    {
        try {
            $settings = Setting::where('group', 'Email')->get()->pluck('value', 'key');

            if ($settings->isEmpty()) {
                return;
            }

            $config = [
                'mail.default' => $settings->get('mail_mailer', config('mail.default')),
                'mail.mailers.smtp.host' => $settings->get('mail_host', config('mail.mailers.smtp.host')),
                'mail.mailers.smtp.port' => $settings->get('mail_port', config('mail.mailers.smtp.port')),
                'mail.mailers.smtp.encryption' => $settings->get('mail_encryption', config('mail.mailers.smtp.encryption')),
                'mail.mailers.smtp.username' => $settings->get('mail_username', config('mail.mailers.smtp.username')),
                'mail.mailers.smtp.password' => $settings->get('mail_password', config('mail.mailers.smtp.password')),
                'mail.from.address' => $settings->get('mail_from_address', config('mail.from.address')),
                'mail.from.name' => $settings->get('mail_from_name', config('mail.from.name')),
            ];

            config($config);
        } catch (\Exception $e) {
            // Silently fail if settings table doesn't exist yet (during early migrations)
            \Illuminate\Support\Facades\Log::warning("Could not apply mail configuration: " . $e->getMessage());
        }
    }
}
