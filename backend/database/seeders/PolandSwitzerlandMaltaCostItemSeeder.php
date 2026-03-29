<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\CostItem;

class PolandSwitzerlandMaltaCostItemSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'PL' => [
                ['name' => 'Flight to Poland (Est.)', 'amount' => 450.00, 'currency' => 'EUR', 'is_mandatory' => false, 'description' => 'Estimated flight cost'],
                ['name' => 'Visa Fee (Type D)', 'amount' => 80.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'National visa application fee'],
                ['name' => 'Monthly Rent (Average Center)', 'amount' => 750.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => '1-bedroom apartment in city center'],
                ['name' => 'Monthly Groceries (Standard)', 'amount' => 300.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'Basic food and supplies'],
                ['name' => 'Monthly Utilities', 'amount' => 150.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'Electricity, water, heating, internet'],
                ['name' => 'Health Insurance (Monthly)', 'amount' => 40.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'Private/State insurance mix'],
                ['name' => 'Monthly Public Transport Pass', 'amount' => 25.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'City-wide unlimited travel'],
                ['name' => 'Estimated Settlement Misc.', 'amount' => 500.00, 'currency' => 'EUR', 'is_mandatory' => false, 'description' => 'Initial setup costs, furnishings, etc.'],
            ],
            'CH' => [
                ['name' => 'Flight to Switzerland (Est.)', 'amount' => 500.00, 'currency' => 'CHF', 'is_mandatory' => false, 'description' => 'Estimated flight cost'],
                ['name' => 'Visa Fee (Type D)', 'amount' => 80.00, 'currency' => 'CHF', 'is_mandatory' => true, 'description' => 'National visa fee'],
                ['name' => 'Monthly Rent (Studio Center)', 'amount' => 1800.00, 'currency' => 'CHF', 'is_mandatory' => true, 'description' => '1.5-room apartment in Zurich/Geneva'],
                ['name' => 'Monthly Groceries (High)', 'amount' => 550.00, 'currency' => 'CHF', 'is_mandatory' => true, 'description' => 'Living in expensive Switzerland'],
                ['name' => 'Monthly Utilities', 'amount' => 250.00, 'currency' => 'CHF', 'is_mandatory' => true, 'description' => 'Electricity, water, heating, waste tax'],
                ['name' => 'Mandatory Health Insurance (KVG)', 'amount' => 350.00, 'currency' => 'CHF', 'is_mandatory' => true, 'description' => 'Minimum Swiss health insurance premium'],
                ['name' => 'Half-Fare Travelcard (Monthly Eq)', 'amount' => 15.00, 'currency' => 'CHF', 'is_mandatory' => true, 'description' => 'SBB Half-fare subscription monthly cost'],
                ['name' => 'Monthly Public Transport Pass', 'amount' => 85.00, 'currency' => 'CHF', 'is_mandatory' => true, 'description' => 'City-wide public transport pass'],
            ],
            'MT' => [
                ['name' => 'Flight to Malta (Est.)', 'amount' => 300.00, 'currency' => 'EUR', 'is_mandatory' => false, 'description' => 'Estimated flight cost'],
                ['name' => 'Visa Fee (Type D)', 'amount' => 66.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'Maltese student visa fee'],
                ['name' => 'Monthly Rent (Average Center)', 'amount' => 950.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => '1-bedroom apartment in Sliema/St. Julians'],
                ['name' => 'Monthly Groceries (Standard)', 'amount' => 350.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'Food and basic supplies'],
                ['name' => 'Monthly Utilities', 'amount' => 100.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'Electricity and water (ARMS)'],
                ['name' => 'Health Insurance (Yearly Eq Monthly)', 'amount' => 20.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'Private health insurance for expats'],
                ['name' => 'Tallinja Bus Card (Monthly Top-up)', 'amount' => 21.00, 'currency' => 'EUR', 'is_mandatory' => true, 'description' => 'Monthly bus travel top-up (resident rate)'],
            ],
        ];

        foreach ($data as $countryCode => $items) {
            $country = Country::where('code', $countryCode)->first();
            if (!$country) continue;

            // Optional: Cleanup existing cost_items for these countries to avoid duplicates on re-seed
            CostItem::where('country_id', $country->id)->whereNull('pathway_id')->delete();

            foreach ($items as $item) {
                CostItem::create(array_merge($item, [
                    'country_id' => $country->id,
                    'pathway_id' => null,   // This makes it a Template in your Admin panel
                ]));
            }
        }
    }
}
