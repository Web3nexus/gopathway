<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\RelocationKit;
use App\Models\RelocationKitItem;

class GlobalRelocationKitSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            'Canada' => [
                [
                    'title' => 'First 30 Days in Canada',
                    'description' => 'Your essential checklist for a smooth arrival in the Great White North.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Apply for SIN Number', 'content' => 'Visit a Service Canada office to get your Social Insurance Number. You need this for working and getting paid.'],
                        ['title' => 'Open a Canadian Bank Account', 'content' => 'Banks like RBC, TD, and Scotiabank have newcomer programs with no fees for the first year.'],
                        ['title' => 'Apply for a Provincial Health Card', 'content' => 'Depending on your province (eg. OHIP in Ontario), register as soon as possible.'],
                    ]
                ],
                [
                    'title' => 'Canada Housing Guide',
                    'description' => 'Renting your first apartment and understanding tenant rights.',
                    'is_premium' => true,
                    'items' => [
                        ['title' => 'Navigating Renting in Canada', 'content' => 'Look for apartments on Rentals.ca or Viewit.ca. Be prepared to provide a credit report or letter of employment.'],
                        ['title' => 'Understanding Lease Agreements', 'content' => 'In Ontario, use the Standard Form of Lease. Landlords generally cannot ask for more than first and last month rent.'],
                    ]
                ]
            ],
            'United Kingdom' => [
                [
                    'title' => 'Arriving in the UK',
                    'description' => 'The must-do list for your first two weeks in Britain.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Collect your BRP', 'content' => 'Go to the Post Office specified in your decision letter to collect your Biometric Residence Permit.'],
                        ['title' => 'Register with a GP', 'content' => 'Find a local doctor near your home and register for NHS healthcare services.'],
                        ['title' => 'Apply for National Insurance (NI) Number', 'content' => 'Apply online at GOV.UK. You need this for tax and employment purposes.'],
                    ]
                ],
                [
                    'title' => 'UK Banking & Credit',
                    'description' => 'Setting up your finances and building credit in the UK.',
                    'is_premium' => true,
                    'items' => [
                        ['title' => 'Choosing a Bank', 'content' => 'Traditional banks (Barclays, Lloyds) vs. Digital banks (Monzo, Revolut). Digital banks are often faster for newcomers.'],
                        ['title' => 'Building UK Credit History', 'content' => 'Register on the electoral roll if eligible, and consider a credit builder card once you have a steady income.'],
                    ]
                ]
            ],
            'Australia' => [
                [
                    'title' => 'Settling in Australia',
                    'description' => 'Your survival guide for the land Down Under.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Apply for TFN (Tax File Number)', 'content' => 'Apply through the ATO website. You need this to avoid being taxed at the highest rate.'],
                        ['title' => 'Register with Medicare', 'content' => 'If your country has a reciprocal healthcare agreement, enroll at a Medicare office.'],
                        ['title' => 'Open an Australian Bank Account', 'content' => 'Commonwealth Bank (CBA) and ANZ allow you to open an account online before you arrive.'],
                    ]
                ]
            ],
            'Germany' => [
                [
                    'title' => 'First Steps in Germany',
                    'description' => 'The "Anmeldung" and beyond.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Address Registration (Anmeldung)', 'content' => 'Register at the Bürgeramt within 14 days. You cannot do anything in Germany without this certificate.'],
                        ['title' => 'Health Insurance (Krankenkasse)', 'content' => 'Choose between public (GKV) or private (PKV). This is legally mandatory from day one.'],
                    ]
                ]
            ],
            'Netherlands' => [
                [
                    'title' => 'Living in the Netherlands',
                    'description' => 'Everything you need to know about bikes, BSN, and banking.',
                    'is_premium' => false,
                    'items' => [
                        ['title' => 'Get your BSN Number', 'content' => 'Register at the local municipality (Gemeente) to receive your Burger-servicenummer.'],
                        ['title' => 'Health Insurance (Zorgverzekering)', 'content' => 'You must apply for Dutch health insurance within 4 months of arrival.'],
                    ]
                ]
            ],
        ];

        foreach ($countries as $countryName => $kits) {
            $country = Country::where('name', $countryName)->first();
            if (!$country)
                continue;

            foreach ($kits as $kitData) {
                $kit = RelocationKit::updateOrCreate(
                ['country_id' => $country->id, 'title' => $kitData['title']],
                [
                    'description' => $kitData['description'],
                    'is_premium' => $kitData['is_premium'],
                    'icon' => 'globe',
                    'order' => 1
                ]
                );

                foreach ($kitData['items'] as $itemData) {
                    RelocationKitItem::updateOrCreate(
                    ['relocation_kit_id' => $kit->id, 'title' => $itemData['title']],
                    [
                        'content' => $itemData['content'],
                        'is_premium' => $kitData['is_premium'],
                        'order' => 1
                    ]
                    );
                }
            }
        }
    }
}