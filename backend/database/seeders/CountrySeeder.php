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
            ['code' => 'PL', 'name' => 'Poland', 'description' => 'A vibrant Central European hub offering a unique blend of medieval history, affordable high-quality education, and a rapidly growing tech economy.', 'image_url' => 'https://images.unsplash.com/photo-1512813583167-9d7842e4719e?q=80&w=1200', 'competitiveness_score' => 75],
            ['code' => 'CH', 'name' => 'Switzerland', 'description' => 'A global leader in innovation and research, offering elite education set against the breathtaking backdrop of the Swiss Alps and pristine lakes.', 'image_url' => 'https://images.unsplash.com/photo-1531310197839-ccf54634509e?q=80&w=1200', 'competitiveness_score' => 85],
            ['code' => 'MT', 'name' => 'Malta', 'description' => 'A sun-drenched Mediterranean archipelago and rising tech hub, perfect for international students seeking a safe, English-speaking environment.', 'image_url' => 'https://images.unsplash.com/photo-1543834297-c1d401344607?q=80&w=1200', 'competitiveness_score' => 70],
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
