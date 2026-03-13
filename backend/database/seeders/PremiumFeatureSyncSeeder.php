<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;
use App\Models\PlatformFeature;

class PremiumFeatureSyncSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sync the 'features' table (Frontend uses these slugs)
        $features = [
            [
                'name' => 'School Explorer',
                'slug' => 'school-explorer',
                'description' => 'Explore universities, programs and visa requirements.',
                'is_premium' => true,
            ],
            [
                'name' => 'Settlement Checklist',
                'slug' => 'settlement-checklist',
                'description' => 'A personalized guide for your first week, month and year.',
                'is_premium' => true,
            ],
            [
                'name' => 'Cost Planner',
                'slug' => 'cost-planner',
                'description' => 'Detailed relocation cost estimates and budget planning.',
                'is_premium' => true,
            ],
        ];

        foreach ($features as $f) {
            Feature::updateOrCreate(['slug' => $f['slug']], $f);
        }

        // 2. Sync the 'platform_features' table (Dashboard flags)
        $platformFeatures = [
            [
                'feature_key' => 'SCHOOL_EXPLORER',
                'feature_name' => 'School Explorer',
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'feature_key' => 'SETTLEMENT_CHECKLIST',
                'feature_name' => 'Settlement Checklist',
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'feature_key' => 'SETTLEMENT-CHECKLIST',
                'feature_name' => 'Settlement Checklist (Hyphenated)',
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'feature_key' => 'COST_PLANNER',
                'feature_name' => 'Relocation Cost Planning',
                'is_active' => true,
                'is_premium' => true,
            ],
            [
                'feature_key' => 'COST-PLANNER',
                'feature_name' => 'Relocation Cost Planning (Hyphenated)',
                'is_active' => true,
                'is_premium' => true,
            ],
        ];

        foreach ($platformFeatures as $pf) {
            PlatformFeature::updateOrCreate(['feature_key' => $pf['feature_key']], $pf);
        }

        // 3. Clear the dashboard cache to force refresh
        \Illuminate\Support\Facades\Cache::forget('platform_features');
    }
}
