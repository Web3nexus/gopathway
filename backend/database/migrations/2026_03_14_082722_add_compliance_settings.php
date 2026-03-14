<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insertOrIgnore([
            [
                'key' => 'cookie_consent_enabled',
                'value' => '0',
                'type' => 'boolean',
                'label' => 'Enable Cookie Consent Banner',
                'description' => 'Show a popup to users to accept cookies.',
                'group' => 'Compliance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'cookie_consent_message',
                'value' => 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.',
                'type' => 'text',
                'label' => 'Cookie Consent Message',
                'description' => 'The message shown on the cookie banner.',
                'group' => 'Compliance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'privacy_policy_url',
                'value' => '/privacy-policy',
                'type' => 'string',
                'label' => 'Privacy Policy URL',
                'description' => 'Link to your privacy policy page.',
                'group' => 'Compliance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'terms_service_url',
                'value' => '/terms-of-service',
                'type' => 'string',
                'label' => 'Terms of Service URL',
                'description' => 'Link to your terms of service page.',
                'group' => 'Compliance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'cookie_consent_enabled',
            'cookie_consent_message',
            'privacy_policy_url',
            'terms_service_url',
        ])->delete();
    }
};
