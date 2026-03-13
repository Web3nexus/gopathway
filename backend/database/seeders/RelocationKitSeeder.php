<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Country;
use App\Models\RelocationKit;
use App\Models\RelocationKitItem;

class RelocationKitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $canada = Country::where('code', 'CA')->first();
        $uk = Country::where('code', 'UK')->first();

        if ($canada) {
            $this->seedCanadaKits($canada);
        }

        if ($uk) {
            $this->seedUkKits($uk);
        }
    }

    private function seedCanadaKits(Country $canada)
    {
        $arrivalKit = RelocationKit::create([
            'country_id' => $canada->id,
            'title' => 'First 30 Days in Canada',
            'description' => 'Essential checklist for your first month after landing in Canada.',
            'icon' => 'home',
            'is_premium' => false,
            'order' => 1,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $arrivalKit->id,
            'title' => 'Get a SIN (Social Insurance Number)',
            'content' => 'You need a SIN to work in Canada or to receive benefits and services from government programs. You can apply online or in-person at a Service Canada Centre.',
            'is_premium' => false,
            'order' => 1,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $arrivalKit->id,
            'title' => 'Open a Canadian Bank Account',
            'content' => 'Top banks (RBC, TD, Scotiabank, BMO, CIBC) often have "Newcomer to Canada" packages with no monthly fees for the first year. Bring your passport and CoPR (Confirmation of Permanent Residence) or work/study permit.',
            'is_premium' => false,
            'order' => 2,
        ]);

        $housingKit = RelocationKit::create([
            'country_id' => $canada->id,
            'title' => 'Housing & Neighborhoods Guide',
            'description' => 'Deep dive into finding an apartment, understanding leases, and avoiding scams.',
            'icon' => 'map-pin',
            'is_premium' => true,
            'order' => 2,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $housingKit->id,
            'title' => 'Understanding Standard Leases',
            'content' => 'In most provinces, landlords must use a standard lease form. Ensure you read the terms regarding tenant insurance, utilities, and subletting.',
            'is_premium' => true,
            'order' => 1,
        ]);
        
        RelocationKitItem::create([
            'relocation_kit_id' => $housingKit->id,
            'title' => 'Credit Checks for Newcomers',
            'content' => 'Since you won\'t have a Canadian credit history, be prepared to show proof of employment, significant savings, or offer an extra month of rent upfront (though legally grey in some provinces).',
            'is_premium' => true,
            'order' => 2,
        ]);
    }

    private function seedUkKits(Country $uk)
    {
        $arrivalKit = RelocationKit::create([
            'country_id' => $uk->id,
            'title' => 'First 30 Days in the UK',
            'description' => 'Essential checklist for your first month after landing in the UK.',
            'icon' => 'home',
            'is_premium' => false,
            'order' => 1,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $arrivalKit->id,
            'title' => 'Pick up your BRP',
            'content' => 'Collect your Biometric Residence Permit from the designated Post Office within 10 days of arriving in the UK.',
            'is_premium' => false,
            'order' => 1,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $arrivalKit->id,
            'title' => 'Apply for a National Insurance (NI) Number',
            'content' => 'You can start work without it, but you must apply for it immediately. Call the application line to get the form sent to your UK address.',
            'is_premium' => false,
            'order' => 2,
        ]);
    }
}
