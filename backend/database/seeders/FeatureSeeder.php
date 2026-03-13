<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'name' => 'Messaging & Expert Chat',
                'slug' => 'messaging',
                'description' => 'Real-time chat with immigration experts and professionals.',
                'is_premium' => true,
            ],
            [
                'name' => 'Cost Planner',
                'slug' => 'cost-planner',
                'description' => 'Detailed relocation cost estimates and budget planning.',
                'is_premium' => true,
            ],
            [
                'name' => 'Document Vault',
                'slug' => 'document-vault',
                'description' => 'Secure storage and management of immigration documents.',
                'is_premium' => true,
            ],
            [
                'name' => 'Pathway Recommendations',
                'slug' => 'recommendations',
                'description' => 'AI-powered immigration pathway suggestions based on your profile.',
                'is_premium' => true,
            ],
            [
                'name' => 'Expert Marketplace',
                'slug' => 'marketplace',
                'description' => 'Connect and book sessions with verified immigration lawyers and translators.',
                'is_premium' => true,
            ],
            [
                'name' => 'Immigration Roadmap',
                'slug' => 'roadmap',
                'description' => 'Step-by-step interactive timeline for your immigration journey.',
                'is_premium' => false,
            ],
            [
                'name' => 'Priority Support',
                'slug' => 'priority-support',
                'description' => 'Fast-track assistance and dedicated support for premium users.',
                'is_premium' => true,
            ],
            [
                'name' => 'SOP Builder',
                'slug' => 'sop-builder',
                'description' => 'Guided wizard to generate a professional Statement of Purpose.',
                'is_premium' => true,
            ],
            [
                'name' => 'School Explorer',
                'slug' => 'school-explorer',
                'description' => 'Explore universities, programs and visa requirements for students.',
                'is_premium' => true,
            ],
            [
                'name' => 'Settlement Checklist',
                'slug' => 'settlement-checklist',
                'description' => 'A personalized guide for your first week, month and year in a new country.',
                'is_premium' => true,
            ],
        ];

        foreach ($features as $feature) {
            \App\Models\Feature::updateOrCreate(
                ['slug' => $feature['slug']],
                $feature
            );
        }
    }
}
