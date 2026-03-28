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
            ['code' => 'PL', 'name' => 'Poland'],
            ['code' => 'CH', 'name' => 'Switzerland'],
            ['code' => 'MT', 'name' => 'Malta'],
            ['code' => 'NG', 'name' => 'Nigeria'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['code' => $country['code']], [
                'name' => $country['name'],
                'is_active' => true,
            ]);
        }
    }
}
