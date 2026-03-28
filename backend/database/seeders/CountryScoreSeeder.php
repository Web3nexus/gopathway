<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountryScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'United Kingdom' => [
                'visa_difficulty' => 75,
                'cost_index' => 85,
                'processing_speed' => 60,
                'pr_ease' => 45,
                'job_market' => 85,
            ],
            'Canada' => [
                'visa_difficulty' => 45,
                'cost_index' => 75,
                'processing_speed' => 40,
                'pr_ease' => 90,
                'job_market' => 80,
            ],
            'Germany' => [
                'visa_difficulty' => 50,
                'cost_index' => 65,
                'processing_speed' => 45,
                'pr_ease' => 70,
                'job_market' => 95,
            ],
            'Portugal' => [
                'visa_difficulty' => 30,
                'cost_index' => 45,
                'processing_speed' => 55,
                'pr_ease' => 85,
                'job_market' => 60,
            ],
            'Spain' => [
                'visa_difficulty' => 35,
                'cost_index' => 55,
                'processing_speed' => 50,
                'pr_ease' => 80,
                'job_market' => 65,
            ],
            'Ireland' => [
                'visa_difficulty' => 55,
                'cost_index' => 80,
                'processing_speed' => 50,
                'pr_ease' => 65,
                'job_market' => 90,
            ],
            'Australia' => [
                'visa_difficulty' => 65,
                'cost_index' => 85,
                'processing_speed' => 45,
                'pr_ease' => 60,
                'job_market' => 85,
            ],
            'New Zealand' => [
                'visa_difficulty' => 60,
                'cost_index' => 75,
                'processing_speed' => 55,
                'pr_ease' => 75,
                'job_market' => 70,
            ],
            'Netherlands' => [
                'visa_difficulty' => 45,
                'cost_index' => 80,
                'processing_speed' => 65,
                'pr_ease' => 60,
                'job_market' => 95,
            ],
            'France' => [
                'visa_difficulty' => 50,
                'cost_index' => 70,
                'processing_speed' => 40,
                'pr_ease' => 55,
                'job_market' => 75,
            ],
            'Italy' => [
                'visa_difficulty' => 40,
                'cost_index' => 60,
                'processing_speed' => 35,
                'pr_ease' => 50,
                'job_market' => 60,
            ],
            'Sweden' => [
                'visa_difficulty' => 45,
                'cost_index' => 75,
                'processing_speed' => 50,
                'pr_ease' => 65,
                'job_market' => 80,
            ],
            'Finland' => [
                'visa_difficulty' => 40,
                'cost_index' => 70,
                'processing_speed' => 60,
                'pr_ease' => 70,
                'job_market' => 75,
            ],
            'Norway' => [
                'visa_difficulty' => 55,
                'cost_index' => 95,
                'processing_speed' => 55,
                'pr_ease' => 55,
                'job_market' => 85,
            ],
            'Austria' => [
                'visa_difficulty' => 50,
                'cost_index' => 75,
                'processing_speed' => 50,
                'pr_ease' => 50,
                'job_market' => 80,
            ],
            'Poland' => [
                'visa_difficulty' => 40,
                'cost_index' => 30,
                'processing_speed' => 70,
                'pr_ease' => 50,
                'job_market' => 60,
            ],
            'Switzerland' => [
                'visa_difficulty' => 80,
                'cost_index' => 100,
                'processing_speed' => 30,
                'pr_ease' => 20,
                'job_market' => 90,
            ],
            'Malta' => [
                'visa_difficulty' => 60,
                'cost_index' => 60,
                'processing_speed' => 40,
                'pr_ease' => 70,
                'job_market' => 50,
            ],
        ];

        foreach ($data as $countryName => $scores) {
            $country = \App\Models\Country::where('name', $countryName)->first();
            if ($country) {
                $country->score()->updateOrCreate([], $scores);
            }
        }
    }
}
