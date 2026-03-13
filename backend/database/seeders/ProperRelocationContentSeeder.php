<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\RelocationKit;
use App\Models\RelocationKitItem;
use App\Models\SettlementStep;

class ProperRelocationContentSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        RelocationKit::truncate();
        RelocationKitItem::truncate();
        SettlementStep::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $countries = [
            'Spain' => [
                'kits' => [
                    [
                        'title' => 'Spain: Pre-Arrival Survival Guide',
                        'description' => 'Preparation, Packing, and Spanish Culture.',
                        'items' => [
                            ['title' => 'The "NIE" Mystery', 'content' => 'Before you land, understand the difference between the White NIE and the TIE. You normally apply for the TIE after arrival.'],
                            ['title' => 'Financial Preparation', 'content' => 'Bring at least €3,000 - €5,000 for initial deposits and fees. Use Wise/Revolut for the first few weeks.'],
                            ['title' => 'Shipping & Logistics', 'content' => 'Shipping furniture is expensive. Consider selling and buying new at IKEA or local marketplaces like Wallapop.'],
                            ['title' => 'Cultural Do\'s and Don\'ts', 'content' => 'Lunch is the main meal (2 PM). Tipping is appreciated but not mandatory. Learn basic Spanish; it goes a long way!'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Airport Transfer & SIM', 'description' => 'Get a local SIM from Orange or Movistar immediately. Install Citymapper for Madrid/Barcelona.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'week1', 'title' => 'Empadronamiento', 'description' => 'Register your address at the local Ayuntamiento. You need a rental contract.', 'mandatory' => true, 'estimated_time' => '3 days', 'required_documents' => ['Passport', 'Rental Contract']],
                    ['phase' => 'week1', 'title' => 'Residencía Appointment', 'description' => 'Book your TIE appointment on the Sede Electronica portal. Slots fill up fast!', 'mandatory' => true, 'estimated_time' => '30 days'],
                    ['phase' => 'month1', 'title' => 'Spanish Bank Account', 'description' => 'Open a resident or non-resident account. N26 is great for digital-first users.', 'mandatory' => true, 'estimated_time' => '2 days'],
                    ['phase' => 'month1', 'title' => 'Social Security Number', 'description' => 'Get your Numero de Seguridad Social to start working.', 'mandatory' => true, 'estimated_time' => '5 days'],
                    ['phase' => 'month1', 'title' => 'Healthcare (SIP/CAP Card)', 'description' => 'Register at your local CAP health center with your padrón and social security info.', 'mandatory' => true, 'estimated_time' => '7 days'],
                    ['phase' => 'long_term', 'title' => 'Convert Driving License', 'description' => 'If your country has a treaty, exchange your license for a Spanish one.', 'mandatory' => false, 'estimated_time' => '3 months'],
                    ['phase' => 'long_term', 'title' => 'Digital Certificate', 'description' => 'Get a Certificado Digital (FNMT) to sign documents online.', 'mandatory' => true, 'estimated_time' => '14 days'],
                ]
            ],
            'United Kingdom' => [
                'kits' => [
                    [
                        'title' => 'UK: The Ultimate Relocation Guide',
                        'description' => 'From Visa collection to British Etiquette.',
                        'items' => [
                            ['title' => 'Right to Rent', 'content' => 'Landlords must check your immigration status. Have your BRP or share code ready.'],
                            ['title' => 'Healthcare System (NHS)', 'content' => 'Healthcare is mostly free at the point of use, but you must pay the Immigration Health Surcharge (IHS).'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Collect BRP Card', 'description' => 'Collect your Biometric Residence Permit from the designated Post Office.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'week1', 'title' => 'Local SIM & Bank (Monzo)', 'description' => 'Get a Giffgaff SIM. Open a Monzo account—it’s the fastest for newcomers.', 'mandatory' => true, 'estimated_time' => '2 days'],
                    ['phase' => 'week1', 'title' => 'GP Registration', 'description' => 'Register with a local GP. Use the NHS website to find the nearest one.', 'mandatory' => true, 'estimated_time' => '7 days'],
                    ['phase' => 'month1', 'title' => 'National Insurance Number', 'description' => 'Apply online. This is your unique ID for tax and pension.', 'mandatory' => true, 'estimated_time' => '21 days'],
                    ['phase' => 'month1', 'title' => 'Council Tax Registration', 'description' => 'Register with your local council to pay for local services.', 'mandatory' => true, 'estimated_time' => '5 days'],
                    ['phase' => 'month1', 'title' => 'Utility Bills Setup', 'description' => 'Register with Octopus or British Gas for electricity/gas.', 'mandatory' => true, 'estimated_time' => '3 days'],
                    ['phase' => 'long_term', 'title' => 'Building Credit History', 'description' => 'Build UK credit by getting a phone contract and eventually a credit card.', 'mandatory' => false, 'estimated_time' => '6 months'],
                ]
            ],
            'Canada' => [
                'kits' => [
                    [
                        'title' => 'Canada: Pre-Departure Check',
                        'description' => 'Packing for winters and provincial differences.',
                        'items' => [
                            ['title' => 'Weather Reality', 'content' => 'Winter lasts from Nov to April. Buy high-quality thermal gear BEFORE it hits -20C.'],
                            ['title' => 'Credit Scores', 'content' => 'Your credit history starts at zero here. Be prepared for higher deposits.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'SIN Number Application', 'description' => 'Visit a Service Canada office for your Social Insurance Number.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'week1', 'title' => 'Provincial Health Card', 'description' => 'Apply for OHIP (Ontario) or MSP (BC) immediately.', 'mandatory' => true, 'estimated_time' => '90 days'],
                    ['phase' => 'week1', 'title' => 'Get a Canadian SIM', 'description' => 'Data is expensive. Look at Fido, Koodo, or Virgin.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Newcomer Bank Account', 'description' => 'RBC, BMO, and TD have great newcomer packages with no fees.', 'mandatory' => true, 'estimated_time' => '2 days'],
                    ['phase' => 'month1', 'title' => 'Equifax/TransUnion Setup', 'description' => 'Register to monitor your Canadian credit score.', 'mandatory' => true, 'estimated_time' => '7 days'],
                    ['phase' => 'long_term', 'title' => 'Driver\'s License Exchange', 'description' => 'Exchange your foreign license for a local provincial one.', 'mandatory' => false, 'estimated_time' => '30 days'],
                    ['phase' => 'long_term', 'title' => 'Canadian Resume Formatting', 'description' => 'Update your resume to follow Canadian standards (no photos, specific length).', 'mandatory' => true, 'estimated_time' => '14 days'],
                ]
            ],
            'Germany' => [
                'kits' => [
                    [
                        'title' => 'Germany: Pre-Arrival Essentials',
                        'description' => 'Insurance, Language, and Rules.',
                        'items' => [
                            ['title' => 'The "Anmeldung" Importance', 'content' => 'Without registration, you cannot get a bank account or a tax ID.'],
                            ['title' => 'Paperwork Culture', 'content' => 'Germany loves paper. Buy a binder and keep every letter you receive.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Bürgeramt Registration', 'description' => 'Register your address (Anmeldung) within 14 days of moving in.', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'week1', 'title' => 'Open a Bank Account', 'description' => 'Banks like N26 or Tomorrow are very expat-friendly.', 'mandatory' => true, 'estimated_time' => '2 days'],
                    ['phase' => 'week1', 'title' => 'Health Insurance (TK/AOK)', 'description' => 'Legally required from day 1. Public or Private options available.', 'mandatory' => true, 'estimated_time' => '5 days'],
                    ['phase' => 'month1', 'title' => 'Tax ID (Steuer-ID)', 'description' => 'Received by mail usually 2 weeks after Anmeldung.', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'month1', 'title' => 'Liability Insurance (Haftpflicht)', 'description' => 'Highly recommended in Germany to cover accidental damage.', 'mandatory' => false, 'estimated_time' => '1 day'],
                    ['phase' => 'long_term', 'title' => 'TV & Radio Tax (GEZ)', 'description' => 'Every household must pay this. Look out for the letter in the mail.', 'mandatory' => true, 'estimated_time' => '30 days'],
                    ['phase' => 'long_term', 'title' => 'Schufa (Credit Report)', 'description' => 'Essential for renting your own apartment long-term.', 'mandatory' => true, 'estimated_time' => '7 days'],
                ]
            ],
            'Portugal' => [
                'kits' => [
                    [
                        'title' => 'Portugal: Preparation Guide',
                        'description' => 'The NIF, housing, and lifestyle.',
                        'items' => [
                            ['title' => 'The NIF Importance', 'content' => 'You need a NIF for everything. Get it before arrival via a fiscal representative.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Fiscal Representative Update', 'description' => 'Update your NIF to your Portuguese address at Finanças.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'week1', 'title' => 'Get a Portuguese SIM', 'description' => 'Vodafone, MEO, or NOS. Essential for digital keys.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Open a Portuguese Bank', 'description' => 'Millennium BCP or Novo Banco. Digital options like Moey are great.', 'mandatory' => true, 'estimated_time' => '3 days'],
                    ['phase' => 'month1', 'title' => 'Social Security (NISS)', 'description' => 'Obtain your NISS if you plan to work locally.', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'month1', 'title' => 'Utente (Healthcare) Number', 'description' => 'Register at your local Centro de Saúde.', 'mandatory' => true, 'estimated_time' => '30 days'],
                    ['phase' => 'long_term', 'title' => 'Residencía (SEF/AIMA)', 'description' => 'Finalize your residency card appointment.', 'mandatory' => true, 'estimated_time' => '6 months'],
                ]
            ],
            'Australia' => [
                'kits' => [
                    [
                        'title' => 'Australia: The Pre-Flight Guide',
                        'description' => 'Packing, TFN, and Medicare.',
                        'items' => [
                            ['title' => 'Sun Safety', 'content' => 'High UV ratings mean you need SPF 50+ at all times.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Apply for TFN', 'description' => 'Tax File Number application is your first priority for work.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'week1', 'title' => 'Medicare Enrollment', 'description' => 'Visit a Centrelink office to register for healthcare.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'week1', 'title' => 'Australian SIM Card', 'description' => 'Boost, Telstra, Optus. Good coverage is essential.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Open Bank Account', 'description' => 'CBA, Westpac, ANZ. Newcomer packages often available.', 'mandatory' => true, 'estimated_time' => '2 days'],
                    ['phase' => 'month1', 'title' => 'MyGov & MyHealth Setup', 'description' => 'Link your services to one portal for easy management.', 'mandatory' => true, 'estimated_time' => '7 days'],
                    ['phase' => 'long_term', 'title' => 'Driving License Conversion', 'description' => 'Most states allow conversion if your license is in English.', 'mandatory' => false, 'estimated_time' => '14 days'],
                ]
            ],
            'Ireland' => [
                'kits' => [
                    [
                        'title' => 'Ireland: Pre-Departure Check',
                        'description' => 'Housing crisis and logistics.',
                        'items' => [
                            ['title' => 'Housing Warning', 'content' => 'Finding rent in Dublin is extremely difficult. Have temporary stays booked.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'IRP Appointment', 'description' => 'Register with GNIB/Immigration for your residence permit card.', 'mandatory' => true, 'estimated_time' => '30 days'],
                    ['phase' => 'week1', 'title' => 'Apply for PPSN', 'description' => 'Public Service Number is required for work and tax.', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'week1', 'title' => 'Leap Card (Transport)', 'description' => 'Get a Leap card for discounted bus and rail travel.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Open Irish Bank Account', 'description' => 'AIB, Bank of Ireland, or Revolut (very common).', 'mandatory' => true, 'estimated_time' => '5 days'],
                    ['phase' => 'month1', 'title' => 'Revenue.ie Registration', 'description' => 'Ensure you’re not on "emergency tax" by registering work.', 'mandatory' => true, 'estimated_time' => '10 days'],
                    ['phase' => 'long_term', 'title' => 'HPSC / GP Registration', 'description' => 'Register with a GP for healthcare access.', 'mandatory' => true, 'estimated_time' => '30 days'],
                ]
            ],
            'Netherlands' => [
                'kits' => [
                    [
                        'title' => 'Netherlands: Living Guide',
                        'description' => 'Bikes, BSN, and Dutch culture.',
                        'items' => [
                            ['title' => 'The Bike Culture', 'content' => 'Everyone cycles. Buy a sturdy lock (or two) immediately.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Municipality Appt (BSN)', 'description' => 'Register at your local Gemeente to get your citizen number.', 'mandatory' => true, 'estimated_time' => '7 days'],
                    ['phase' => 'week1', 'title' => 'Dutch SIM Card', 'description' => 'KPN, Vodafone, or T-Mobile. Essential for DigiD.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Zorgverzekering (Health Ins)', 'description' => 'Mandatory to have Dutch health insurance within 4 months.', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'month1', 'title' => 'Open Dutch Bank Account', 'description' => 'ING or ABN AMRO. They work with iDEAL payment system.', 'mandatory' => true, 'estimated_time' => '3 days'],
                    ['phase' => 'month1', 'title' => 'Apply for DigiD', 'description' => 'Your digital identity for all government portals.', 'mandatory' => true, 'estimated_time' => '10 days'],
                    ['phase' => 'long_term', 'title' => 'Water & Waste Tax', 'description' => 'Be aware of the annual taxes for waste and water.', 'mandatory' => true, 'estimated_time' => '1 year'],
                ]
            ],
            'New Zealand' => [
                'kits' => [
                    [
                        'title' => 'New Zealand: Kia Ora!',
                        'description' => 'IRD, Banking, and Kiwi lifestyle.',
                        'items' => [
                            ['title' => 'Outdoor Safety', 'content' => 'The sun is strong. Wear SPF 50+. Respect the land and follow local guidelines.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Apply for IRD Number', 'description' => 'Apply online for your tax number (Inland Revenue). Essential for work.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'week1', 'title' => 'Local SIM Card', 'description' => 'Spark, One NZ (Vodafone), or 2degrees. Good coverage matters.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Open a NZ Bank Account', 'description' => 'ANZ, BNZ, or ASB. Most offer migrant packages.', 'mandatory' => true, 'estimated_time' => '3 days'],
                    ['phase' => 'month1', 'title' => 'Enrol in PHO (Doctor)', 'description' => 'Find a local GP and enrol in a Primary Health Organisation.', 'mandatory' => true, 'estimated_time' => '30 days'],
                    ['phase' => 'long_term', 'title' => 'Driver License Conversion', 'description' => 'You can drive for 12 months on a foreign license before converting.', 'mandatory' => false, 'estimated_time' => '30 days'],
                ]
            ],
            'France' => [
                'kits' => [
                    [
                        'title' => 'France: Bienvenue!',
                        'description' => 'Bureaucracy, Health, and Culture.',
                        'items' => [
                            ['title' => 'Language First', 'content' => 'French bureaucracy is complex. Learning basic French is highly recommended.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'VLS-TS Validation', 'description' => 'Validate your long-stay visa online (ANEF portal) within 3 months.', 'mandatory' => true, 'estimated_time' => '3 days'],
                    ['phase' => 'week1', 'title' => 'French SIM Card', 'description' => 'Free Mobile, Orange, or Bouygues. Free is cheapest for data.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Open a French Bank Account', 'description' => 'Societe Generale, BNP Paribas, or online banks like Hello Bank.', 'mandatory' => true, 'estimated_time' => '7 days'],
                    ['phase' => 'month1', 'title' => 'Carte Vitale (Assurance Maladie)', 'description' => 'Register for the national healthcare system via Ameli.', 'mandatory' => true, 'estimated_time' => '60 days'],
                    ['phase' => 'long_term', 'title' => 'CAF (Housing Subsidy)', 'description' => 'Apply for APL if you are eligible for housing support.', 'mandatory' => false, 'estimated_time' => '30 days'],
                ]
            ],
            'Italy' => [
                'kits' => [
                    [
                        'title' => 'Italy: La Dolce Vita',
                        'description' => 'Codice Fiscale, Permesso, and Lifestyle.',
                        'items' => [
                            ['title' => 'Patience is Key', 'content' => 'Bureaucracy can be slow. Always keep copies of everything.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Codice Fiscale', 'description' => 'Get your unique tax ID from the Agenzia delle Entrate.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'week1', 'title' => 'Apply for Permesso di Soggiorno', 'description' => 'Apply at the Post Office (Sportello Amico) within 8 days of arrival.', 'mandatory' => true, 'estimated_time' => '8 days'],
                    ['phase' => 'month1', 'title' => 'Italian Bank Account', 'description' => 'Intesa Sanpaolo, UniCredit, or PostePay for ease.', 'mandatory' => true, 'estimated_time' => '5 days'],
                    ['phase' => 'month1', 'title' => 'ASL Registration (SSN)', 'description' => 'Register with the local health authority for your health card (Tessera Sanitaria).', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'long_term', 'title' => 'Residenza (Municipality)', 'description' => 'Register your residency at the local Anagrafe office.', 'mandatory' => true, 'estimated_time' => '45 days'],
                ]
            ],
            'Sweden' => [
                'kits' => [
                    [
                        'title' => 'Sweden: The Nordic Way',
                        'description' => 'Personnummer, Banking, and Fika.',
                        'items' => [
                            ['title' => 'Digital Everything', 'content' => 'Sweden is almost cashless. You need BankID for almost everything.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Skatteverket (Personnummer)', 'description' => 'Apply for your personal identity number. Essential for Swedish life.', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'week1', 'title' => 'Swedish SIM Card', 'description' => 'Telia, Tele2, or Comviq. Prepaid is easy to get.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Get Swedish ID Card', 'description' => 'Once you have your Personnummer, apply for an ID card via Skatteverket.', 'mandatory' => true, 'estimated_time' => '21 days'],
                    ['phase' => 'month1', 'title' => 'Open Swedish Bank & BankID', 'description' => 'SEB, Swedbank, or Nordea. Essential for Mobile BankID.', 'mandatory' => true, 'estimated_time' => '10 days'],
                    ['phase' => 'long_term', 'title' => 'Försäkringskassan (Health)', 'description' => 'Register for social insurance benefits and EHIC.', 'mandatory' => true, 'estimated_time' => '30 days'],
                ]
            ],
            'Finland' => [
                'kits' => [
                    [
                        'title' => 'Finland: Sisu & Snow',
                        'description' => 'DVV, Kela, and Nordic Life.',
                        'items' => [
                            ['title' => 'Sisu Spirit', 'content' => 'Prepare for cold winters and embrace the Finnish resilience.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'DVV Registration', 'description' => 'Register your personal details at the Digital and Population Data Services Agency.', 'mandatory' => true, 'estimated_time' => '7 days'],
                    ['phase' => 'week1', 'title' => 'Finnish SIM Card', 'description' => 'Elisa, Telia, or DNA. Speed is usually excellent.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Finnish ID & Bank Account', 'description' => 'Nordea or OP. Required for strong electronic identification.', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'month1', 'title' => 'Kela Card (Social Security)', 'description' => 'Apply for your national health insurance card.', 'mandatory' => true, 'estimated_time' => '30 days'],
                    ['phase' => 'long_term', 'title' => 'HSL / VR (Transport)', 'description' => 'Set up your local transport accounts for commuting.', 'mandatory' => true, 'estimated_time' => '1 day'],
                ]
            ],
            'Norway' => [
                'kits' => [
                    [
                        'title' => 'Norway: The Fjords Life',
                        'description' => 'D-number, BankID, and Nature.',
                        'items' => [
                            ['title' => 'High Cost of Living', 'content' => 'Be prepared for high prices but excellent public services.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Skatteetaten (ID Check)', 'description' => 'Visit the tax office for an ID check to get your D-number or Personnummer.', 'mandatory' => true, 'estimated_time' => '7 days'],
                    ['phase' => 'week1', 'title' => 'Norwegian SIM Card', 'description' => 'Telenor or Telia. Essential for digital authentication.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Open a Norwegian Bank', 'description' => 'DNB or SpareBank. Required for BankID.', 'mandatory' => true, 'estimated_time' => '10 days'],
                    ['phase' => 'month1', 'title' => 'Helfo (Healthcare)', 'description' => 'Register for a GP (Fastlege) once you have your personal number.', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'long_term', 'title' => 'Digital Mailbox', 'description' => 'Set up Digipost or e-Boks for government communications.', 'mandatory' => true, 'estimated_time' => '5 days'],
                ]
            ],
            'Austria' => [
                'kits' => [
                    [
                        'title' => 'Austria: Alpine Living',
                        'description' => 'Meldezettel, e-card, and Culture.',
                        'items' => [
                            ['title' => 'Sunday Closures', 'content' => 'Almost all shops are closed on Sundays. Plan ahead!'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Meldezettel Registration', 'description' => 'Register your address within 3 days of moving in at the Meldeamt.', 'mandatory' => true, 'estimated_time' => '3 days'],
                    ['phase' => 'week1', 'title' => 'Austrian SIM Card', 'description' => 'A1, Magenta, or Drei. Competitive data plans.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'month1', 'title' => 'Open an Austrian Bank', 'description' => 'Erste Bank, Raiffeisen, or online banks like George.', 'mandatory' => true, 'estimated_time' => '3 days'],
                    ['phase' => 'month1', 'title' => 'e-card (Social Security)', 'description' => 'Received by mail for healthcare access.', 'mandatory' => true, 'estimated_time' => '14 days'],
                    ['phase' => 'long_term', 'title' => 'Handy-Signatur / ID Austria', 'description' => 'Your digital signature for government services.', 'mandatory' => true, 'estimated_time' => '7 days'],
                ]
            ],
            'Nigeria' => [
                'kits' => [
                    [
                        'title' => 'Nigeria: Giant of Africa',
                        'description' => 'NIN, BVN, and Logistics.',
                        'items' => [
                            ['title' => 'Power Backup', 'content' => 'Be prepared with power banks or solar solutions for consistency.'],
                        ]
                    ]
                ],
                'steps' => [
                    ['phase' => 'week1', 'title' => 'Get a Nigerian SIM', 'description' => 'MTN, Airtel, or Glo. NIN registration is required.', 'mandatory' => true, 'estimated_time' => '1 day'],
                    ['phase' => 'week1', 'title' => 'NIN Registration', 'description' => 'Get your National Identification Number if not already held.', 'mandatory' => true, 'estimated_time' => '3 days'],
                    ['phase' => 'month1', 'title' => 'BVN & Bank Account', 'description' => 'GTBank, Zenith, or Access. Bank Verification Number (BVN) is mandatory.', 'mandatory' => true, 'estimated_time' => '2 days'],
                    ['phase' => 'month1', 'title' => 'NHIS (Healthcare)', 'description' => 'Register for national or private health insurance schemes.', 'mandatory' => false, 'estimated_time' => '14 days'],
                    ['phase' => 'long_term', 'title' => 'PVC Registration', 'description' => 'Register for your Permanent Voter Card (PVC) for civic participation.', 'mandatory' => false, 'estimated_time' => '30 days'],
                ]
            ]
        ];

        foreach ($countries as $countryName => $data) {
            $country = Country::where('name', $countryName)->first();
            if (!$country)
                continue;

            // Seed Kits (Preparation)
            foreach ($data['kits'] as $kitData) {
                $kit = RelocationKit::create([
                    'country_id' => $country->id,
                    'title' => $kitData['title'],
                    'description' => $kitData['description'],
                    'icon' => 'globe',
                    'is_premium' => false,
                    'order' => 1
                ]);

                foreach ($kitData['items'] as $itemData) {
                    RelocationKitItem::create([
                        'relocation_kit_id' => $kit->id,
                        'title' => $itemData['title'],
                        'content' => $itemData['content'],
                        'is_premium' => false,
                        'order' => 1
                    ]);
                }
            }

            // Seed Steps (Action)
            foreach ($data['steps'] as $idx => $stepData) {
                SettlementStep::create(array_merge($stepData, [
                    'country_id' => $country->id,
                    'order' => $idx,
                    'required_documents' => $stepData['required_documents'] ?? []
                ]));
            }
        }
    }
}