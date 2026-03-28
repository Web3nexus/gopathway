<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\RelocationKit;
use App\Models\RelocationKitItem;

class PolandSwitzerlandMaltaRelocationKitSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            'Poland' => [
                [
                    'title' => 'Preparing for Poland',
                    'description' => 'A comprehensive checklist for your move to the heart of Europe.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Academic Document Legalization', 'content' => 'Ensure your degrees and transcripts are apostilled or legalized by the Polish embassy in your country.'],
                        ['title' => 'Power Adaptors (Type E/F)', 'content' => 'Poland uses the standard European two-pin plugs. If you are coming from the UK or US, buy a universal adaptor.'],
                        ['title' => 'Packing for Polish Seasons', 'content' => 'Bring high-quality winter gear (it can drop to -15°C) and light clothes for hot summers (up to 30°C).'],
                        ['title' => 'Health Records', 'content' => 'Bring a copy of your immunization records and any ongoing prescriptions translated into English or Polish.'],
                    ]
                ],
            ],
            'Switzerland' => [
                [
                    'title' => 'The Swiss Moving Checklist',
                    'description' => 'Precision planning for your relocation to Switzerland.',
                    'is_premium' => true,
                    'items' => [
                        ['title' => 'Power Adaptors (Type J)', 'content' => 'Switzerland has a unique three-pin plug (Type J). Standard European Type C (two-pin) fits, but grounded Type E/F will not.'],
                        ['title' => 'Exit Declaration Proof', 'content' => 'For the student visa, you often need to sign a declaration promising to leave Switzerland after graduation. Have a copy ready.'],
                        ['title' => 'Private Liability Insurance', 'content' => 'Unlike most countries, "Haftpflichtversicherung" is often mandatory for renting apartments in Switzerland.'],
                        ['title' => 'Mountain & Outdoor Gear', 'content' => 'Invest in good hiking boots and waterproof layers. Switzerland is an outdoor paradise year-round.'],
                    ]
                ],
            ],
            'Malta' => [
                [
                    'title' => 'Malta: Island Life Checklist',
                    'description' => 'Essential items for your new life in the Mediterranean.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Power Adaptors (Type G)', 'content' => 'Malta uses the UK-style three-pin rectangular plug. If coming from EU/US, bring several adaptors.'],
                        ['title' => 'Sun Protection & Hydration', 'content' => 'The Maltese sun is intense. Pack high SPF sunscreen and a reusable water bottle (tap water is safe but often preferred filtered).'],
                        ['title' => 'Light & Breathable Clothing', 'content' => 'Focus on cotton and linen. Winters are mild but humid, so a light windbreaker is essential.'],
                        ['title' => 'Public Transport Tallinja App', 'content' => 'Download the Tallinja app before arrival to start planning your bus routes; the bus is the primary mode of transport.'],
                    ]
                ],
            ],
        ];

        foreach ($countries as $countryName => $kits) {
            $country = Country::where('name', $countryName)->first();
            if (!$country) continue;

            foreach ($kits as $kitData) {
                $kit = RelocationKit::updateOrCreate(
                    ['country_id' => $country->id, 'title' => $kitData['title']],
                    [
                        'description' => $kitData['description'],
                        'is_premium' => $kitData['is_premium'],
                        'icon' => 'truck-moving',
                        'order' => 1
                    ]
                );

                foreach ($kitData['items'] as $idx => $itemData) {
                    RelocationKitItem::updateOrCreate(
                        ['relocation_kit_id' => $kit->id, 'title' => $itemData['title']],
                        [
                            'content' => $itemData['content'],
                            'is_premium' => $kitData['is_premium'],
                            'order' => $idx
                        ]
                    );
                }
            }
        }
    }
}
