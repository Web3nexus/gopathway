<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\RelocationKit;
use App\Models\RelocationKitItem;

class SpainRelocationKitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spain = Country::where('name', 'Spain')->first();

        if (!$spain) {
            $this->command->error('Spain not found in countries table.');
            return;
        }

        // 1. First 30 Days Kit (Free)
        $arrivalKit = RelocationKit::create([
            'country_id' => $spain->id,
            'title' => 'First 30 Days in Spain',
            'description' => 'Essential checklist for your first month in Spain.',
            'icon' => 'home',
            'is_premium' => false,
            'order' => 1,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $arrivalKit->id,
            'title' => 'Get your NIE (Número de Identidad de Extranjero)',
            'content' => 'The NIE is your tax identification number in Spain. You need it for everything from opening a bank account to signing a rental contract. You can apply at the local Foreigner\'s Office (Oficina de Extranjería) or Police Station.',
            'is_premium' => false,
            'order' => 1,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $arrivalKit->id,
            'title' => 'Register on the Padrón',
            'content' => 'Empadronamiento is the act of registering yourself with the local town hall where you reside. It is mandatory and required for access to healthcare and schools.',
            'is_premium' => false,
            'order' => 2,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $arrivalKit->id,
            'title' => 'Open a Spanish Bank Account',
            'content' => 'Having a local bank account (IBAN starting with ES) is crucial for paying utilities and receiving salary. Banks like BBVA, Santander, and Sabadell have "Newcomer" accounts.',
            'is_premium' => false,
            'order' => 3,
        ]);

        // 2. Housing Guide (Premium)
        $housingKit = RelocationKit::create([
            'country_id' => $spain->id,
            'title' => 'Housing & Neighborhoods Guide',
            'description' => 'Expert tips on finding an apartment and understanding rental laws in Spain.',
            'icon' => 'map-pin',
            'is_premium' => true,
            'order' => 2,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $housingKit->id,
            'title' => 'Understanding Spanish Rental Contracts',
            'content' => 'Spain has strict rental laws (LAU). Most long-term contracts are for 1 year, renewable up to 5 years. Always ensure the deposit (fianza) is held by the regional authority.',
            'is_premium' => true,
            'order' => 1,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $housingKit->id,
            'title' => 'Avoiding "Agencia" Fees',
            'content' => 'New laws in Spain prohibit landlords from charging agency fees to tenants for long-term rentals. Be aware of your rights to avoid unnecessary costs.',
            'is_premium' => true,
            'order' => 2,
        ]);

        // 3. Healthcare Kit (Premium)
        $healthcareKit = RelocationKit::create([
            'country_id' => $spain->id,
            'title' => 'Healthcare & Insurance Guide',
            'description' => 'How to access the public health system and choose private insurance.',
            'icon' => 'shield',
            'is_premium' => true,
            'order' => 3,
        ]);

        RelocationKitItem::create([
            'relocation_kit_id' => $healthcareKit->id,
            'title' => 'Getting your SIP Card',
            'content' => 'The SIP card is your gateway to the Public Healthcare System (Seguridad Social). You need your NIE and Padrón to apply at your local health center (CAP).',
            'is_premium' => true,
            'order' => 1,
        ]);
    }
}