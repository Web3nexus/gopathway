<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\VisaType;
use App\Models\CostTemplate;
use App\Models\SettlementStep;
use Illuminate\Database\Seeder;

class PolandSwitzerlandMaltaExpansionSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPoland();
        $this->seedSwitzerland();
        $this->seedMalta();
    }

    private function seedPoland()
    {
        $country = Country::where('code', 'PL')->first();
        if (!$country) return;

        $pathways = [
            [
                'name' => 'Student Visa (Type D)',
                'type' => 'Study',
                'description' => 'For international students admitted to Polish universities for full-time studies.',
                'processing_time' => '15-30 days',
                'pr_possibility' => true,
                'min_funds' => 3000,
                'requirements' => ['Acceptance Letter', 'Health Insurance (€30k coverage)', 'Proof of Funds (Min PLN 776/mo)', 'Accommodation Proof', 'Return Flight Ticket'],
                'benefits' => ['Work 20h/week', 'No work permit needed for full-time students', '9-month job seeker extension'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee', 'min' => 80, 'max' => 80],
                    ['category' => 'Travel', 'item' => 'Relocation Flight', 'min' => 400, 'max' => 1200],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit', 'min' => 600, 'max' => 1000],
                    ['category' => 'Monthly Living', 'item' => 'Food & Groceries', 'min' => 250, 'max' => 400],
                    ['category' => 'Monthly Living', 'item' => 'Utilities (Electricity, Water, Internet)', 'min' => 100, 'max' => 200],
                    ['category' => 'Insurance', 'item' => 'Statutory Health Insurance (NFZ)', 'min' => 15, 'max' => 50],
                ]
            ],
            [
                'name' => 'Poland.Business Harbour (PBH)',
                'type' => 'Tech/Business',
                'description' => 'Fast-track pathway for IT specialists, startups, and established companies.',
                'processing_time' => '2-4 weeks',
                'pr_possibility' => true,
                'requirements' => ['Tech degree or 1 year experience', 'Job offer/B2B contract', 'Company sponsorship'],
                'benefits' => ['No labor market test', 'B2B activity allowed', 'Family reunification'],
            ],
            [
                'name' => 'Graduate Job Seeker Visa',
                'type' => 'Job Seeker',
                'description' => 'A 9-month residence permit for non-EU graduates of Polish universities to look for work.',
                'processing_time' => '30-90 days',
                'pr_possibility' => true,
            ]
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Poland');
    }

    private function seedSwitzerland()
    {
        $country = Country::where('code', 'CH')->first();
        if (!$country) return;

        $pathways = [
            [
                'name' => 'Student Visa (Type D)',
                'type' => 'Study',
                'description' => 'For students at recognized Swiss universities. Requires strict financial self-sufficiency.',
                'processing_time' => '8-16 weeks',
                'pr_possibility' => true,
                'min_funds' => 21000,
                'requirements' => ['Enrollment confirmation', 'Pre-paid tuition receipt', 'Proof of CHF 21k-30k available', 'Motivation letter & CV', 'Exit declaration'],
                'benefits' => ['World-class HEIs', '15h/week work rights (after 6 months)', 'Schengen mobility'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee (Cantonal varies)', 'min' => 80, 'max' => 150],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit (3 months)', 'min' => 3000, 'max' => 6000],
                    ['category' => 'Insurance', 'item' => 'Mandatory Health Insurance (KVG)', 'min' => 300, 'max' => 500, 'notes' => 'Monthly premium'],
                    ['category' => 'Monthly Living', 'item' => 'Groceries & Household', 'min' => 600, 'max' => 900],
                    ['category' => 'Transport', 'item' => 'Halbtax (Half-fare card)', 'min' => 185, 'max' => 185],
                ]
            ],
            [
                'name' => 'Skilled Work (Permit B)',
                'type' => 'Skilled Work',
                'description' => 'For highly qualified professionals with a binding job offer.',
                'processing_time' => '3-6 months',
                'pr_possibility' => true,
            ]
        ];

        $this->processPathways($country, $pathways, 'CHF');
        $this->seedSettlement($country, 'Switzerland');
    }

    private function seedMalta()
    {
        $country = Country::where('code', 'MT')->first();
        if (!$country) return;

        $pathways = [
            [
                'name' => 'Student Visa (Type D)',
                'type' => 'Study',
                'description' => 'For non-EU students studying courses longer than 90 days.',
                'processing_time' => '4-8 weeks',
                'pr_possibility' => true,
                'min_funds' => 6600,
                'requirements' => ['Letter of Acceptance', 'Medical Insurance', 'Proof of funds (€18-26/day)', 'Bank statements (6 months)'],
                'benefits' => ['English instruction', 'Mediterranean lifestyle', 'EU residency'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Application Fee', 'min' => 66, 'max' => 70],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit', 'min' => 800, 'max' => 1500],
                    ['category' => 'Monthly Living', 'item' => 'Utilities & Personal', 'min' => 150, 'max' => 300],
                    ['category' => 'Insurance', 'item' => 'Private Health Insurance', 'min' => 40, 'max' => 100, 'notes' => 'Monthly estimate'],
                ]
            ],
            [
                'name' => 'Nomad Residence Permit',
                'type' => 'Digital Nomad',
                'description' => 'For remote workers who can prove an annual income of at least €42,000.',
                'processing_time' => '30 days',
                'pr_possibility' => true,
                'requirements' => ['Employment contract', 'Income proof (>€3.5k/mo)', 'Health insurance', 'Rental contract in Malta'],
                'benefits' => ['Yearly renewable', 'Tax benefits', 'Easy EU travel'],
            ],
            [
                'name' => 'Key Employee Initiative (KEI)',
                'type' => 'Skilled Work',
                'description' => 'Fast-track work permit for managerial or highly technical roles.',
                'processing_time' => '5 working days',
                'pr_possibility' => true,
            ]
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Malta');
    }

    private function processPathways($country, $pathways, $defaultCurrency)
    {
        foreach ($pathways as $p) {
            $visa = VisaType::updateOrCreate(
                ['country_id' => $country->id, 'name' => $p['name']],
                [
                    'pathway_type' => $p['type'] ?? 'Skilled Work',
                    'description' => $p['description'] ?? "Official immigration pathway for {$p['name']} in {$country->name}.",
                    'processing_time' => $p['processing_time'] ?? '4-12 weeks',
                    'pr_possibility' => $p['pr_possibility'] ?? true,
                    'requirements' => $p['requirements'] ?? ['Passport', 'Proof of Funds'],
                    'benefits' => $p['benefits'] ?? ['Residence Rights', 'Schengen Travel'],
                    'min_funds_required' => $p['min_funds'] ?? 5000,
                    'is_active' => true,
                ]
            );

            if (isset($p['costs'])) {
                foreach ($p['costs'] as $cost) {
                    CostTemplate::updateOrCreate(
                        ['visa_type_id' => $visa->id, 'item' => $cost['item']],
                        [
                            'category' => $cost['category'],
                            'min_cost' => $cost['min'],
                            'max_cost' => $cost['max'],
                            'currency' => $defaultCurrency,
                        ]
                    );
                }
            }
        }
    }

    private function seedSettlement($country, $type)
    {
        $stepsData = [
            'Poland' => [
                ['phase' => 'week1', 'title' => 'Get a PESEL Number', 'description' => 'Registration in the Universal Electronic System for Registration of the Population. Essential for tax and healthcare.'],
                ['phase' => 'week1', 'title' => 'Open a Bank Account', 'description' => 'Major banks: PKO BP, mBank, Santander. Many allow opening with just a passport.'],
                ['phase' => 'month1', 'title' => 'Secure a Local SIM', 'description' => 'Choose between Orange, T-Mobile, or Play. ID registration is mandatory.'],
                ['phase' => 'month1', 'title' => 'Transport Pass (ZTM)', 'description' => 'Apply for a city travel card for discounted public transport.'],
            ],
            'Switzerland' => [
                ['phase' => 'week1', 'title' => 'Register with the Residents\' Office (Kreisbüro)', 'description' => 'Mandatory within 14 days of arrival. This leads to your residency permit.'],
                ['phase' => 'week1', 'title' => 'Open a Bank Account (UBS/CS)', 'description' => 'Swiss banks require residence registration proof.'],
                ['phase' => 'month1', 'title' => 'Halbtax Card', 'description' => 'Buy the SBB Half-Fare travelcard to significantly reduce travel costs.'],
                ['phase' => 'month1', 'title' => 'Select Health Insurance (KVG)', 'description' => 'You have 3 months to select a mandatory basic insurance provider.'],
            ],
            'Malta' => [
                ['phase' => 'week1', 'title' => 'Apply for e-Residence Card', 'description' => 'Your main identification at Identità (the Identity Agency).'],
                ['phase' => 'week1', 'title' => 'Social Security Number', 'description' => 'Required for all employees and freelancers.'],
                ['phase' => 'month1', 'title' => 'Open Bank Account (BOV/HSBC)', 'description' => 'Proof of address and e-residence application receipt usually required.'],
                ['phase' => 'month1', 'title' => 'Tallinja Transport Card', 'description' => 'Register for the national bus card for subsidized fares.'],
            ]
        ];

        $steps = $stepsData[$type] ?? [];

        foreach ($steps as $idx => $step) {
            SettlementStep::updateOrCreate(
                ['country_id' => $country->id, 'title' => $step['title']],
                array_merge($step, ['order' => $idx])
            );
        }
    }
}
