<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScholarshipFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add to platform_features (Global toggle)
        DB::table('platform_features')->updateOrInsert(
            ['feature_key' => 'scholarship_system'],
            [
                'feature_name' => 'Scholarship Aggregator',
                'description' => 'Automated scholarship discovery and directory system.',
                'is_active' => true,
                'is_premium' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Add to features (Granular gating)
        DB::table('features')->updateOrInsert(
            ['slug' => 'scholarship-directory'],
            [
                'name' => 'Scholarship Directory',
                'description' => 'Access the global database of scholarships.',
                'is_premium' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
