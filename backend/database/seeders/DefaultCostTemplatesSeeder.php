<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CostItem;
use App\Models\Country;

class DefaultCostTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $canada = Country::where('name', 'Canada')->first();
        $nigeria = Country::where('name', 'Nigeria')->first();

        // 1. Global Costs (No Country)
        CostItem::updateOrCreate(
            ['name' => 'Gateway Platform Fee', 'country_id' => null, 'visa_type_id' => null],
            [
                'amount' => 150.00,
                'currency' => 'USD',
                'description' => 'Platform processing and roadmap generation fee',
                'is_mandatory' => true,
            ]
        );

        if ($canada) {
            $ee = $canada->visaTypes()->where('name', 'LIKE', '%Express Entry%')->first();

            // 2. Canada Global Cost
            CostItem::updateOrCreate(
                ['name' => 'WES Credential Evaluation', 'country_id' => $canada->id, 'visa_type_id' => null],
                [
                    'amount' => 220.00,
                    'currency' => 'CAD',
                    'description' => 'Education credential assessment for Canadian immigration',
                    'is_mandatory' => true,
                ]
            );

            if ($ee) {
                // 3. Route Specific Cost
                CostItem::updateOrCreate(
                    ['name' => 'IRCC Application Fee (Express Entry)', 'country_id' => $canada->id, 'visa_type_id' => $ee->id],
                    [
                        'amount' => 850.00,
                        'currency' => 'CAD',
                        'description' => 'Processing fee for Permanent Residency application',
                        'is_mandatory' => true,
                    ]
                );
            }
        }

        if ($nigeria) {
            // 4. Nigeria Specific Cost
            CostItem::updateOrCreate(
                ['name' => 'Police Clearance Certificate', 'country_id' => $nigeria->id, 'visa_type_id' => null],
                [
                    'amount' => 20000.00,
                    'currency' => 'NGN',
                    'description' => 'Local criminal record check for emigration',
                    'is_mandatory' => true,
                ]
            );

            CostItem::updateOrCreate(
                ['name' => 'NIN Verification', 'country_id' => $nigeria->id, 'visa_type_id' => null],
                [
                    'amount' => 5000.00,
                    'currency' => 'NGN',
                    'description' => 'National Identity Number verification fee',
                    'is_mandatory' => true,
                ]
            );
        }
    }
}
