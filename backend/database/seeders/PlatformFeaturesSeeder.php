<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'feature_key' => 'AI_CHAT',
                'feature_name' => 'AI Travel Assistant',
                'description' => 'AI-powered relocation and immigration guidance.',
                'is_active' => false,
            ],
            [
                'feature_key' => 'FINANCE_RECOMMENDATION',
                'feature_name' => 'Financing Recommendations',
                'description' => 'Trusted financial provider recommendations based on pathway.',
                'is_active' => false,
            ],
            [
                'feature_key' => 'ADVANCED_PATHWAY_ANALYSIS',
                'feature_name' => 'Advanced Pathway Analysis',
                'description' => 'Deep analysis of immigration strategies and success rates.',
                'is_active' => false,
            ],
        ];

        foreach ($features as $feature) {
            \App\Models\PlatformFeature::updateOrCreate(
            ['feature_key' => $feature['feature_key']],
                $feature
            );
        }
    }
}