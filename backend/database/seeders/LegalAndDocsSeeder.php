<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class LegalAndDocsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => 'privacy_policy_content',
                'value' => "<h1>Privacy Policy</h1><p>Welcome to GoPathway. We value your privacy and are committed to protecting your personal data.</p><h3>1. Information We Collect</h3><p>We collect information that you provide directly to us, such as when you create an account, fill out a profile, or communicate with us.</p><h3>2. How We Use Your Information</h3><p>We use the information we collect to provide, maintain, and improve our services, and to communicate with you.</p><h3>3. Data Security</h3><p>We implement a variety of security measures to maintain the safety of your personal information.</p>",
                'group' => 'legal',
                'type' => 'text',
                'label' => 'Privacy Policy Content',
                'description' => 'The full content of the Privacy Policy page (HTML allowed).',
            ],
            [
                'key' => 'terms_service_content',
                'value' => "<h1>Terms of Service</h1><p>By using GoPathway, you agree to the following terms and conditions.</p><h3>1. Acceptance of Terms</h3><p>By accessing or using our services, you agree to be bound by these terms.</p><h3>2. User Responsibilities</h3><p>You are responsible for maintaining the confidentiality of your account and for all activities that occur under your account.</p><h3>3. Limitation of Liability</h3><p>GoPathway shall not be liable for any indirect, incidental, special, consequential, or punitive damages.</p>",
                'group' => 'legal',
                'type' => 'text',
                'label' => 'Terms of Service Content',
                'description' => 'The full content of the Terms of Service page (HTML allowed).',
            ],
            [
                'key' => 'documentation_content',
                'value' => "<h1>Documentation & Features</h1><p>Learn how to get the most out of GoPathway.</p><h3>1. Getting Started</h3><p>Create your account and complete your profile setup to receive personalized relocation recommendations.</p><h3>2. Pathway Tracking</h3><p>Use our interactive roadmap to stay on top of your immigration journey, from document preparation to arrival.</p><h3>3. Expert Marketplace</h3><p>Connect with verified professionals who can provide specialized advice for your specific situation.</p><h3>4. Cost Planner</h3><p>Estimate your relocation expenses accurately using our data-driven budget tools.</p>",
                'group' => 'system',
                'type' => 'text',
                'label' => 'Documentation Content',
                'description' => 'The full content of the Documentation/Help page (HTML allowed).',
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
