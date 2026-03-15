<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'site_name',
                'value' => 'GoPathway',
                'group' => 'general',
                'type' => 'string',
                'label' => 'Site Name',
                'description' => 'The name of the application.',
            ],
            [
                'key' => 'support_email',
                'value' => 'support@gopathway.net',
                'group' => 'general',
                'type' => 'string',
                'label' => 'Support Email',
                'description' => 'Contact email for user support.',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1 (555) 000-0000',
                'group' => 'general',
                'type' => 'string',
                'label' => 'Contact Phone',
                'description' => 'Support contact phone number.',
            ],
            [
                'key' => 'currency_symbol',
                'value' => '£',
                'group' => 'general',
                'type' => 'string',
                'label' => 'Currency Symbol',
                'description' => 'Used for displaying costs.',
            ],
            // Appearance
            [
                'key' => 'accent_color',
                'value' => '#00C2FF',
                'group' => 'appearance',
                'type' => 'string',
                'label' => 'Accent Color',
                'description' => 'Primary brand color for the UI.',
            ],
            // System
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'group' => 'system',
                'type' => 'boolean',
                'label' => 'Maintenance Mode',
                'description' => 'Disable frontend access for regular users.',
            ],
            // Dashboard Labels
            [
                'key' => 'label_go_score_readiness',
                'value' => 'Your Readiness',
                'group' => 'labels',
                'type' => 'string',
                'label' => 'Label: GoScore Readiness',
                'description' => 'Text shown above the GoScore on the dashboard.',
            ],
            [
                'key' => 'label_go_score_title',
                'value' => 'GoScore™',
                'group' => 'labels',
                'type' => 'string',
                'label' => 'Label: GoScore Title',
                'description' => 'Title of the GoScore widget.',
            ],
            [
                'key' => 'label_go_score_breakdown',
                'value' => 'Score Breakdown',
                'group' => 'labels',
                'type' => 'string',
                'label' => 'Label: GoScore Breakdown',
                'description' => 'Header for the score dimension breakdown (Premium).',
            ],
            [
                'key' => 'label_pathway_risk_level',
                'value' => 'Risk Level',
                'group' => 'labels',
                'type' => 'string',
                'label' => 'Label: Risk Level',
                'description' => 'Header for the risk indicator on the pathway page.',
            ],
            [
                'key' => 'label_pathway_progress',
                'value' => 'Progress',
                'group' => 'labels',
                'type' => 'string',
                'label' => 'Label: Pathway Progress',
                'description' => 'Label for the progress percentage on the pathway page.',
            ],
            [
                'key' => 'label_pathway_roadmap',
                'value' => 'Your Roadmap',
                'group' => 'labels',
                'type' => 'string',
                'label' => 'Label: Roadmap Title',
                'description' => 'Title of the timeline steps section.',
            ],
            // Payments
            [
                'key' => 'payment_gateway_active',
                'value' => 'paystack', // Options: 'paystack', 'flutterwave', 'both'
                'group' => 'payment',
                'type' => 'string',
                'label' => 'Active Gateway',
                'description' => 'Select which gateway to use for checkout (paystack, flutterwave, both).',
            ],
            [
                'key' => 'paystack_public_key',
                'value' => '',
                'group' => 'payment',
                'type' => 'string',
                'label' => 'Paystack Public Key',
                'description' => 'Public API key for Paystack.',
            ],
            [
                'key' => 'paystack_secret_key',
                'value' => '',
                'group' => 'payment',
                'type' => 'encrypted_string',
                'label' => 'Paystack Secret Key',
                'description' => 'Secret API key for Paystack.',
            ],
            [
                'key' => 'flutterwave_public_key',
                'value' => '',
                'group' => 'payment',
                'type' => 'string',
                'label' => 'Flutterwave Public Key',
                'description' => 'Public API key for Flutterwave.',
            ],
            [
                'key' => 'flutterwave_secret_key',
                'value' => '',
                'group' => 'payment',
                'type' => 'encrypted_string',
                'label' => 'Flutterwave Secret Key',
                'description' => 'Secret API key for Flutterwave.',
            ],
            // Social Auth
            [
                'key' => 'google_auth_enabled',
                'value' => '0',
                'group' => 'auth',
                'type' => 'boolean',
                'label' => 'Google Auth Enabled',
                'description' => 'Enable/Disable Google login and registration.',
            ],
            [
                'key' => 'google_client_id',
                'value' => '',
                'group' => 'auth',
                'type' => 'string',
                'label' => 'Google Client ID',
                'description' => 'Public client ID for Google OAuth.',
            ],
            [
                'key' => 'google_client_secret',
                'value' => '',
                'group' => 'auth',
                'type' => 'encrypted_string',
                'label' => 'Google Client Secret',
                'description' => 'Secret key for Google OAuth.',
            ],
            [
                'key' => 'apple_auth_enabled',
                'value' => '0',
                'group' => 'auth',
                'type' => 'boolean',
                'label' => 'Apple Auth Enabled',
                'description' => 'Enable/Disable Apple login and registration.',
            ],
            [
                'key' => 'apple_client_id',
                'value' => '',
                'group' => 'auth',
                'type' => 'string',
                'label' => 'Apple Client ID',
                'description' => 'Public client ID for Apple OAuth.',
            ],
            [
                'key' => 'apple_client_secret',
                'value' => '',
                'group' => 'auth',
                'type' => 'encrypted_string',
                'label' => 'Apple Client Secret',
                'description' => 'Secret key for Apple OAuth.',
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
