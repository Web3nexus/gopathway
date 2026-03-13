<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0.00,
                'currency' => 'USD',
                'interval' => 'month',
                'features' => json_encode(['Basic Pathway Access', 'Document Checklist']),
                'is_active' => true,
            ],
            [
                'name' => 'Premium Monthly',
                'slug' => 'premium-monthly',
                'price' => 29.99,
                'currency' => 'USD',
                'interval' => 'month',
                'features' => json_encode(['All Free Features', 'Expert Consultation Preview', 'Unlimited Document Storage', 'Full Cost Planner']),
                'is_active' => true,
            ],
            [
                'name' => 'Premium Yearly',
                'slug' => 'premium-yearly',
                'price' => 299.99,
                'currency' => 'USD',
                'interval' => 'year',
                'features' => json_encode(['Everything in Monthly', '2 Months Free', 'Priority Support']),
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            \App\Models\SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
