<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $costs = [
            [
                'name' => 'Visa Application Fee',
                'amount' => 490,
                'description' => 'Standard Priority Visa processing fee',
                'is_mandatory' => true,
                'currency' => 'GBP'
            ],
            [
                'name' => 'IHS Surcharge (Health)',
                'amount' => 1035,
                'description' => 'Immigration Health Surcharge per year',
                'is_mandatory' => true,
                'currency' => 'GBP'
            ],
            [
                'name' => 'Flight to Destination',
                'amount' => 800,
                'description' => 'Estimated cost per adult',
                'is_mandatory' => true,
                'currency' => 'GBP'
            ],
            [
                'name' => 'Accommodation Deposit',
                'amount' => 1500,
                'description' => 'Security deposit and first month rent reserve',
                'is_mandatory' => true,
                'currency' => 'GBP'
            ],
            [
                'name' => 'Proof of Funds',
                'amount' => 1272,
                'description' => 'Maintenance funds required by UKVI',
                'is_mandatory' => true,
                'currency' => 'GBP'
            ],
            [
                'name' => 'TB Test & Misc',
                'amount' => 150,
                'description' => 'Medical examinations and document certification',
                'is_mandatory' => false,
                'currency' => 'GBP'
            ],
        ];

        foreach ($costs as $cost) {
            \App\Models\CostItem::create($cost);
        }
    }
}
