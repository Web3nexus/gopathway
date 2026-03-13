<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinanceProvidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'name' => 'MPOWER Financing',
                'provider_type' => 'Education Loan',
                'supported_countries' => ['USA', 'Canada'],
                'supported_pathways' => ['Study Visa', 'masters', 'phd'],
                'website' => 'https://www.mpowerfinancing.com',
                'description' => 'No-cosigner, no-collateral loans for international students.',
                'rating' => 4.8,
            ],
            [
                'name' => 'Prodigy Finance',
                'provider_type' => 'Education Loan',
                'supported_countries' => ['UK', 'USA', 'Canada', 'Europe'],
                'supported_pathways' => ['Study Visa', 'bachelors', 'masters'],
                'website' => 'https://prodigyfinance.com',
                'description' => 'International student loans for top universities worldwide.',
                'rating' => 4.7,
            ],
            [
                'name' => 'Access Bank - Study Abroad Loan',
                'provider_type' => 'Relocation Financing',
                'supported_countries' => ['Nigeria'],
                'supported_pathways' => ['Study Visa', 'Work Visa'],
                'website' => 'https://www.accessbankplc.com',
                'description' => 'Specifically designed for Nigerians moving abroad for study or work.',
                'rating' => 4.5,
            ],
            [
                'name' => 'GTBank - Education Support',
                'provider_type' => 'Education Loan',
                'supported_countries' => ['Nigeria'],
                'supported_pathways' => ['Study Visa'],
                'website' => 'https://www.gtbank.com',
                'description' => 'Tuition fee payments and personal loans for international study.',
                'rating' => 4.4,
            ],
            [
                'name' => 'RBC - Newcomer Advantage',
                'provider_type' => 'Relocation Financing',
                'supported_countries' => ['Canada'],
                'supported_pathways' => ['Permanent Residency', 'Work Visa'],
                'website' => 'https://www.rbc.com/newcomers',
                'description' => 'Banking and credit for people new to Canada.',
                'rating' => 4.6,
            ],
            [
                'name' => 'Erasmus+ Scholarships',
                'provider_type' => 'Scholarship',
                'supported_countries' => ['Europe', 'Germany', 'France', 'Spain'],
                'supported_pathways' => ['Study Visa'],
                'website' => 'https://erasmus-plus.ec.europa.eu',
                'description' => 'Fully funded scholarships for international students in Europe.',
                'rating' => 4.9,
            ],
        ];

        foreach ($providers as $provider) {
            \App\Models\FinanceProvider::updateOrCreate(
            ['name' => $provider['name']],
                $provider
            );
        }
    }
}