<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class MailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds for Email settings.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'mail_mailer',
                'value' => 'smtp',
                'group' => 'Email',
                'type' => 'string',
                'label' => 'Mail Mailer',
                'description' => 'Protocol used for sending emails (usually smtp).',
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
                'group' => 'Email',
                'type' => 'string',
                'label' => 'Mail Host',
                'description' => 'SMTP server address.',
            ],
            [
                'key' => 'mail_port',
                'value' => '2525',
                'group' => 'Email',
                'type' => 'string',
                'label' => 'Mail Port',
                'description' => 'SMTP server port.',
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'group' => 'Email',
                'type' => 'string',
                'label' => 'Mail Username',
                'description' => 'SMTP account username.',
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'group' => 'Email',
                'type' => 'encrypted_string',
                'label' => 'Mail Password',
                'description' => 'SMTP account password.',
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'group' => 'Email',
                'type' => 'string',
                'label' => 'Mail Encryption',
                'description' => 'Encryption protocol (tls or ssl).',
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'hello@gopathway.net',
                'group' => 'Email',
                'type' => 'string',
                'label' => 'From Address',
                'description' => 'Email address shown as the sender.',
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'GoPathway',
                'group' => 'Email',
                'type' => 'string',
                'label' => 'From Name',
                'description' => 'Name shown as the sender.',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
