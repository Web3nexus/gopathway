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
            $settings = Setting::where('group', 'Email')->get();

            if ($settings->isEmpty()) {
                return;
            }

            // Create a key-value map while respecting accessors
            $settingsMap = [];
            foreach ($settings as $setting) {
                $settingsMap[$setting->key] = $setting->value;
            }

            $config = [
                'mail.default' => $settingsMap['mail_mailer'] ?? config('mail.default'),
                'mail.mailers.smtp.host' => $settingsMap['mail_host'] ?? config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $settingsMap['mail_port'] ?? config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.encryption' => $settingsMap['mail_encryption'] ?? config('mail.mailers.smtp.encryption'),
                'mail.mailers.smtp.username' => $settingsMap['mail_username'] ?? config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $settingsMap['mail_password'] ?? config('mail.mailers.smtp.password'),
                'mail.from.address' => $settingsMap['mail_from_address'] ?? config('mail.from.address'),
                'mail.from.name' => $settingsMap['mail_from_name'] ?? config('mail.from.name'),
            ];

            config($config);
        } catch (\Exception $e) {
            // Silently fail if settings table doesn't exist yet (during early migrations)
            \Illuminate\Support\Facades\Log::warning("Could not apply mail configuration: " . $e->getMessage());
        }
    }
}
