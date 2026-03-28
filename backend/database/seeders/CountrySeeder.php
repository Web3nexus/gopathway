<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['code' => 'GB', 'name' => 'United Kingdom'],
            ['code' => 'CA', 'name' => 'Canada'],
            ['code' => 'AU', 'name' => 'Australia'],
            ['code' => 'NZ', 'name' => 'New Zealand'],
            ['code' => 'DE', 'name' => 'Germany'],
            ['code' => 'NL', 'name' => 'Netherlands'],
            ['code' => 'SE', 'name' => 'Sweden'],
            ['code' => 'FI', 'name' => 'Finland'],
            ['code' => 'AT', 'name' => 'Austria'],
            ['code' => 'FR', 'name' => 'France'],
            ['code' => 'ES', 'name' => 'Spain'],
            ['code' => 'PT', 'name' => 'Portugal'],
            ['code' => 'IE', 'name' => 'Ireland'],
            ['code' => 'NO', 'name' => 'Norway'],
            ['code' => 'IT', 'name' => 'Italy'],
            ['code' => 'PL', 'name' => 'Poland', 'description' => 'A central European hub with affordable living, rich history, and a booming tech sector.', 'image_url' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?q=80&w=1200&auto=format&fit=crop', 'competitiveness_score' => 75],
            ['code' => 'CH', 'name' => 'Switzerland', 'description' => 'Highly innovative alpine paradise offering a premium quality of life and world-class research.', 'image_url' => 'https://images.unsplash.com/photo-1530122037265-a5f1f91d3b99?q=80&w=1200&auto=format&fit=crop', 'competitiveness_score' => 85],
            ['code' => 'MT', 'name' => 'Malta', 'description' => 'A sunny Mediterranean gateway for business and study with an English-speaking environment.', 'image_url' => 'https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?q=80&w=1200&auto=format&fit=crop', 'competitiveness_score' => 70],
            ['code' => 'NG', 'name' => 'Nigeria'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['code' => $country['code']], array_merge([
                'name' => $country['name'],
                'is_active' => true,
            ], $country));
        }
    }
}
