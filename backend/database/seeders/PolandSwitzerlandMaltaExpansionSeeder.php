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
                'description' => 'For international students admitted to Polish universities.',
                'processing_time' => '2-4 weeks',
                'pr_possibility' => true,
                'min_funds' => 3000,
                'requirements' => ['Acceptance Letter', 'Health Insurance', 'Proof of Funds', 'Accommodation Proof'],
                'benefits' => ['Work 20h/week', 'Schengen Access', 'Post-study permit'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee', 'min' => 80, 'max' => 80],
                    ['category' => 'Travel', 'item' => 'Relocation Flight', 'min' => 150, 'max' => 1000],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit', 'min' => 300, 'max' => 1000],
                ]
            ],
            [
                'name' => 'Skilled Worker Visa',
                'type' => 'Skilled Work',
                'description' => 'For professionals with a job offer from a Polish company.',
                'processing_time' => '4-8 weeks',
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
                'description' => 'For international students at recognized Swiss higher education institutions.',
                'processing_time' => '8-12 weeks',
                'pr_possibility' => true,
                'min_funds' => 21000,
                'requirements' => ['Enrollment Certificate', 'Financial Proof (~21k CHF)', 'CV', 'Motivation Letter'],
                'benefits' => ['High quality research', 'Part-time work rights', 'Travel in Schengen'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee', 'min' => 80, 'max' => 150],
                    ['category' => 'Education', 'item' => 'Tuition Fees (Avg Public)', 'min' => 1000, 'max' => 4000],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit (3 months)', 'min' => 2000, 'max' => 6000],
                ]
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
                'description' => 'For non-EU students studying in Malta for more than 90 days.',
                'processing_time' => '4-8 weeks',
                'pr_possibility' => true,
                'min_funds' => 6600,
                'requirements' => ['Letter of Acceptance', 'Medical Insurance', 'Proof of Funds', 'Accommodation'],
                'benefits' => ['English speaking environment', 'Warm climate', 'Work rights (20h/week)'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee', 'min' => 66, 'max' => 66],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit', 'min' => 500, 'max' => 1500],
                    ['category' => 'Health', 'item' => 'Private Insurance Premium', 'min' => 150, 'max' => 400],
                ]
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
        $steps = [
            ['phase' => 'week1', 'title' => 'Address Registration', 'description' => 'Register with the local municipality.'],
            ['phase' => 'week1', 'title' => 'Local Bank Account', 'description' => 'Open a local account for rent and salary.'],
            ['phase' => 'month1', 'title' => 'Tax Number ID', 'description' => 'Get your tax identification number.'],
        ];

        foreach ($steps as $idx => $step) {
            SettlementStep::updateOrCreate(
                ['country_id' => $country->id, 'title' => $step['title']],
                array_merge($step, ['order' => $idx])
            );
        }
    }
}
