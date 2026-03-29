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
                    ]
                ],
                [
                    'title' => 'First 30 Days in Poland',
                    'description' => 'Essential administrative steps after landing.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Get a PESEL Number', 'content' => 'Mandatory for taxes and healthcare. Apply at any municipal office (Urząd Gminy) with your passport and rental agreement.'],
                        ['title' => 'Register Your Address (Zameldowanie)', 'content' => 'If staying more than 30 days, you must register your address at the local office.'],
                        ['title' => 'Open a Bank Account', 'content' => 'mBank and PKO BP have strong English support. Bring your passport and proof of legal stay.'],
                    ]
                ],
                [
                    'title' => 'Poland Housing & Rental Guide',
                    'description' => 'How to find an apartment and understand Polish lease laws.',
                    'is_premium' => true,
                    'items' => [
                        ['title' => 'OLX.pl and Otodom.pl', 'content' => 'The two most popular platforms. Note that "Czynsz" is often the administrative fee, while "Media" are utilities.'],
                        ['title' => 'Rental Deposits', 'content' => 'Standard is 1 month rent. Ensure you get a signed "Protokół zdawczo-odbiorczy" (condition report).'],
                    ]
                ]
            ],
            'Switzerland' => [
                [
                    'title' => 'The Swiss Moving Checklist',
                    'description' => 'Precision planning for your relocation to Switzerland.',
                    'is_premium' => true,
                    'items' => [
                        ['title' => 'Power Adaptors (Type J)', 'content' => 'Switzerland has a unique three-pin plug (Type J). Standard European Type C (two-pin) fits, but grounded Type E/F will not.'],
                        ['title' => 'Private Liability Insurance', 'content' => 'Unlike most countries, "Haftpflichtversicherung" is often mandatory for renting apartments in Switzerland.'],
                    ]
                ],
                [
                    'title' => 'First 30 Days in Switzerland',
                    'description' => 'Navigating the Swiss administrative landscape.',
                    'is_premium' => true,
                    'items' => [
                        ['title' => 'Register with the Residents\' Office', 'content' => 'Mandatory within 14 days of arrival at your local "Kreisbüro" or "Einwohnerkontrolle".'],
                        ['title' => 'Mandatory Health Insurance', 'content' => 'You have 90 days to sign up for a Swiss health insurance policy (KVG). It is backdated to your arrival date.'],
                        ['title' => 'GA/Half-Fare Travelcard', 'content' => 'Crucial for saving money on Swiss public transport. Buy a SwissPass at any SBB station.'],
                    ]
                ],
                [
                    'title' => 'Swiss Housing Guide',
                    'description' => 'Navigating one of the world\'s most competitive rental markets.',
                    'is_premium' => true,
                    'items' => [
                        ['title' => 'The Application Dossier', 'content' => 'Swiss landlords expect a "Betreibungsauszug" (debt record), copy of contract, and references.'],
                        ['title' => 'Rental Deposits (SwissCaution)', 'content' => 'Deposits are often 3 months\' rent. Services like SwissCaution allow you to pay a yearly fee instead of the full cash deposit.'],
                    ]
                ]
            ],
            'Malta' => [
                [
                    'title' => 'Malta Arrival Guide',
                    'description' => 'Everything you need to know about island life.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Power Adaptors (Type G)', 'content' => 'Malta uses the UK-style three-pin rectangular plug. If coming from EU/US, bring several adaptors.'],
                        ['title' => 'The Tallinja Bus Card', 'content' => 'Register online immediately for your personalized bus card. Most bus travel is free for residents once registered.'],
                    ]
                ],
                [
                    'title' => 'First 30 Days in Malta',
                    'description' => 'Setting up your base in the Mediterranean.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Apply for e-Residence Card', 'content' => 'Identity Malta handles all residence permits. Ensure your lease is registered with the Housing Authority first.'],
                        ['title' => 'Tax & Social Security', 'content' => 'If employed, ensure your employer registers you for a social security number (NI).'],
                    ]
                ],
                [
                    'title' => 'Malta Property & Rentals',
                    'description' => 'Finding your Mediterranean home.',
                    'is_premium' => true,
                    'items' => [
                        ['title' => 'Lease Registration', 'content' => 'Mandatory by law. Landlords must register your lease with the Malta Housing Authority online.'],
                        ['title' => 'ARMS Ltd (Utilities)', 'content' => 'Ensure you are registered on the "Residential Rate" (for primary residents) rather than the "Domestic Rate" to save on bills.'],
                    ]
                ]
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
