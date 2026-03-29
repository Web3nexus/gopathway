<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\VisaType;
use App\Models\CostTemplate;
use App\Models\SettlementStep;
use App\Models\ResidencyRule;
use App\Models\JobPlatform;
use App\Models\CvTemplate;
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
                    ['category' => 'Housing Setup', 'item' => 'Rent (Studio/1BR)', 'min' => 450, 'max' => 1100],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit', 'min' => 600, 'max' => 1000],
                    ['category' => 'Monthly Living', 'item' => 'Groceries', 'min' => 250, 'max' => 400],
                    ['category' => 'Monthly Living', 'item' => 'Utilities (Elec, Water, Gas)', 'min' => 80, 'max' => 150],
                    ['category' => 'Monthly Living', 'item' => 'High-Speed Internet', 'min' => 15, 'max' => 30],
                    ['category' => 'Insurance', 'item' => 'Statutory Health Insurance (NFZ)', 'min' => 15, 'max' => 50],
                    ['category' => 'Transport', 'item' => 'Monthly City Pass (Student)', 'min' => 12, 'max' => 25],
                ]
            ],
            [
                'name' => 'Skilled Worker Visa (National Type D)',
                'type' => 'Skilled Work',
                'description' => 'For professionals with a job offer. Note: The PBH program is currently suspended; apply via standard Type D.',
                'processing_time' => '4-8 weeks',
                'pr_possibility' => true,
                'min_funds' => 5000,
                'requirements' => ['Employment Contract', 'Work Permit (Zezwolenie)', 'Health Insurance', 'Rent Agreement'],
                'benefits' => ['Long-term residency', 'Family reunification possible', 'Access to EU market'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee (Type D)', 'min' => 200, 'max' => 200],
                    ['category' => 'Housing Setup', 'item' => 'Rent (1BR Center)', 'min' => 600, 'max' => 950],
                    ['category' => 'Housing Setup', 'item' => 'Deposit (2 months)', 'min' => 1200, 'max' => 1800],
                    ['category' => 'Monthly Living', 'item' => 'Groceries', 'min' => 250, 'max' => 400],
                    ['category' => 'Monthly Living', 'item' => 'Utilities & Internet', 'min' => 120, 'max' => 200],
                    ['category' => 'Insurance', 'item' => 'Private/State Health Mix', 'min' => 40, 'max' => 100],
                    ['category' => 'Transport', 'item' => 'Monthly Pass', 'min' => 25, 'max' => 50],
                ]
            ],
            [
                'name' => 'Graduate Job Seeker Visa',
                'type' => 'Job Seeker',
                'description' => 'A 9-month residence permit for non-EU graduates of Polish universities to look for work.',
                'processing_time' => '30-90 days',
                'pr_possibility' => true,
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Application Fee', 'min' => 100, 'max' => 150],
                    ['category' => 'Monthly Living', 'item' => 'Groceries', 'min' => 250, 'max' => 350],
                    ['category' => 'Monthly Living', 'item' => 'Utilities', 'min' => 80, 'max' => 150],
                    ['category' => 'Housing Setup', 'item' => 'Shared Room/Studio', 'min' => 350, 'max' => 600],
                ]
            ]
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Poland');
        $this->seedResidency($country, 'Poland');
        $this->seedJobs($country, 'Poland');
        $this->seedCV($country, 'Poland');
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
                    ['category' => 'Housing Setup', 'item' => 'Rent (Studio/1BR)', 'min' => 1800, 'max' => 2800],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit (3 months)', 'min' => 3000, 'max' => 6000],
                    ['category' => 'Insurance', 'item' => 'Mandatory Health Insurance (KVG)', 'min' => 300, 'max' => 500, 'notes' => 'Monthly premium'],
                    ['category' => 'Monthly Living', 'item' => 'Groceries & Household', 'min' => 600, 'max' => 900],
                    ['category' => 'Monthly Living', 'item' => 'Utilities & Internet', 'min' => 150, 'max' => 250],
                    ['category' => 'Transport', 'item' => 'Halbtax (Half-fare card)', 'min' => 185, 'max' => 185],
                ]
            ],
            [
                'name' => 'Skilled Work (Permit B)',
                'type' => 'Skilled Work',
                'description' => 'For highly qualified professionals with a binding job offer.',
                'processing_time' => '3-6 months',
                'pr_possibility' => true,
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Cantonal Administrative Fee', 'min' => 250, 'max' => 600],
                    ['category' => 'Housing Setup', 'item' => 'Rent (1BR Executive)', 'min' => 2500, 'max' => 4500],
                    ['category' => 'Monthly Living', 'item' => 'High-end Groceries', 'min' => 800, 'max' => 1200],
                    ['category' => 'Insurance', 'item' => 'Comprehensive Health (KVG)', 'min' => 400, 'max' => 700],
                    ['category' => 'Transport', 'item' => 'GA Travelcard (Monthly)', 'min' => 340, 'max' => 340],
                ]
            ]
        ];

        $this->processPathways($country, $pathways, 'CHF');
        $this->seedSettlement($country, 'Switzerland');
        $this->seedResidency($country, 'Switzerland');
        $this->seedJobs($country, 'Switzerland');
        $this->seedCV($country, 'Switzerland');
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
                    ['category' => 'Housing Setup', 'item' => 'Rent (Studio/1BR)', 'min' => 800, 'max' => 1300],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit', 'min' => 800, 'max' => 1500],
                    ['category' => 'Monthly Living', 'item' => 'Groceries', 'min' => 300, 'max' => 450],
                    ['category' => 'Monthly Living', 'item' => 'Utilities & Internet', 'min' => 100, 'max' => 200],
                    ['category' => 'Insurance', 'item' => 'Private Health Insurance', 'min' => 40, 'max' => 100, 'notes' => 'Monthly estimate'],
                    ['category' => 'Transport', 'item' => 'Tallinja App (Free for residents)', 'min' => 0, 'max' => 30],
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
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Application Admin Fee', 'min' => 300, 'max' => 300],
                    ['category' => 'Housing Setup', 'item' => 'High-end Apartment', 'min' => 1200, 'max' => 2200],
                    ['category' => 'Monthly Living', 'item' => 'Lifestyle & Dining', 'min' => 500, 'max' => 1000],
                    ['category' => 'Insurance', 'item' => 'Private Premium Health', 'min' => 60, 'max' => 120],
                ]
            ],
            [
                'name' => 'Key Employee Initiative (KEI)',
                'type' => 'Skilled Work',
                'description' => 'Fast-track work permit for managerial or highly technical roles.',
                'processing_time' => '5 working days',
                'pr_possibility' => true,
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'KEI Application Fee', 'min' => 280, 'max' => 280],
                    ['category' => 'Housing Setup', 'item' => 'Corporate Housing', 'min' => 1500, 'max' => 3000],
                    ['category' => 'Monthly Living', 'item' => 'Groceries', 'min' => 400, 'max' => 600],
                    ['category' => 'Transport', 'item' => 'Leased Car/Transport', 'min' => 200, 'max' => 500],
                ]
            ]
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Malta');
        $this->seedResidency($country, 'Malta');
        $this->seedJobs($country, 'Malta');
        $this->seedCV($country, 'Malta');
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
                [
                    'phase' => 'week1', 
                    'title' => 'Get a PESEL Number', 
                    'description' => 'Registration in the Universal Electronic System for Registration of the Population. Essential for tax and healthcare.',
                    'official_link' => 'https://www.gov.pl/web/gov/uzyskaj-numer-pesel-dla-cudzoziemcow',
                    'required_documents' => "Valid Passport\nRental Agreement\nApplication Form",
                    'estimated_time' => 'Immediate',
                    'mandatory' => true
                ],
                [
                    'phase' => 'week1', 
                    'title' => 'Open a Bank Account', 
                    'description' => 'Major banks: PKO BP, mBank, Santander. Many allow opening with just a passport.',
                    'official_link' => 'https://www.mbank.pl/klient-indywidualny/konta/konta-osobiste/konto-dla-obcokrajowca/',
                    'required_documents' => "Passport\nPESEL Number (Recommended)\nProof of Address",
                    'estimated_time' => '1 day',
                    'mandatory' => true
                ],
                [
                    'phase' => 'month1', 
                    'title' => 'Secure a Local SIM', 
                    'description' => 'Choose between Orange, T-Mobile, or Play. ID registration is mandatory.',
                    'official_link' => 'https://www.orange.pl/view/oferta-dla-obcokrajowcow',
                    'required_documents' => "Passport or Residence Card",
                    'estimated_time' => '1 hour',
                    'mandatory' => true
                ],
                [
                    'phase' => 'month1', 
                    'title' => 'Transport Pass (ZTM)', 
                    'description' => 'Apply for a city travel card for discounted public transport.',
                    'official_link' => 'https://www.wtp.waw.pl/en/',
                    'required_documents' => "Passport\nStudent ID (for discount)\nPassport Photo",
                    'estimated_time' => 'On-site',
                    'mandatory' => false
                ],
            ],
            'Switzerland' => [
                [
                    'phase' => 'week1', 
                    'title' => 'Register with the Residents\' Office (Kreisbüro)', 
                    'description' => 'Mandatory within 14 days of arrival. This leads to your residency permit.',
                    'official_link' => 'https://www.stadt-zuerich.ch/prd/de/index/bevoelkerungsamt/personenmeldeamt/anmelden.html',
                    'required_documents' => "Passport\nEmployment Contract\nSigned Lease Agreement\nHealth Insurance Proof",
                    'estimated_time' => '1-2 weeks',
                    'mandatory' => true
                ],
                [
                    'phase' => 'week1', 
                    'title' => 'Open a Bank Account (UBS/CS)', 
                    'description' => 'Swiss banks require residence registration proof.',
                    'official_link' => 'https://www.ubs.com/ch/en/swissbank/private/accounts/foreign-clients.html',
                    'required_documents' => "Passport\nResidence Permit (or proof of application)\nEmployment Contract",
                    'estimated_time' => '2-5 days',
                    'mandatory' => true
                ],
                [
                    'phase' => 'month1', 
                    'title' => 'Halbtax Card', 
                    'description' => 'Buy the SBB Half-Fare travelcard to significantly reduce travel costs.',
                    'official_link' => 'https://www.sbb.ch/en/travelcards-and-tickets/travelcards/half-fare-travelcard.html',
                    'required_documents' => "Passport\nSwissPass (if available)\nPayment method",
                    'estimated_time' => 'Immediate (Digital)',
                    'mandatory' => false
                ],
                [
                    'phase' => 'month1', 
                    'title' => 'Select Health Insurance (KVG)', 
                    'description' => 'You have 3 months to select a mandatory basic insurance provider.',
                    'official_link' => 'https://www.priminfo.admin.ch/',
                    'required_documents' => "Passport\nResidence Registration",
                    'estimated_time' => 'Ongoing',
                    'mandatory' => true
                ],
            ],
            'Malta' => [
                [
                    'phase' => 'week1', 
                    'title' => 'Apply for e-Residence Card', 
                    'description' => 'Your main identification at Identità (the Identity Agency).',
                    'official_link' => 'https://identita.gov.mt/expatriates-unit-main-page/',
                    'required_documents' => "Form ID 1A\nPassport Copies (all pages)\nRegistered Lease Agreement\nHealth Insurance Policy (€30k)",
                    'estimated_time' => '2-4 months',
                    'mandatory' => true
                ],
                [
                    'phase' => 'week1', 
                    'title' => 'Social Security Number', 
                    'description' => 'Required for all employees and freelancers.',
                    'official_link' => 'https://socialsecurity.gov.mt/en/',
                    'required_documents' => "Passport\nJob Offer/Contract",
                    'estimated_time' => '7-14 days',
                    'mandatory' => true
                ],
                [
                    'phase' => 'month1', 
                    'title' => 'Open Bank Account (BOV/HSBC)', 
                    'description' => 'Proof of address and e-residence application receipt usually required.',
                    'official_link' => 'https://www.bov.com/content/applying-for-an-account',
                    'required_documents' => "Passport\ne-Residence Application Receipt\nEmployment Letter",
                    'estimated_time' => '2-4 weeks',
                    'mandatory' => true
                ],
                [
                    'phase' => 'month1', 
                    'title' => 'Tallinja Transport Card', 
                    'description' => 'Register for the national bus card for subsidized fares.',
                    'official_link' => 'https://www.publictransport.com.mt/en/register-now',
                    'required_documents' => "Passport\nMalta Address\nPassport Photo",
                    'estimated_time' => '7-10 days',
                    'mandatory' => false
                ],
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

    private function seedResidency($country, $type)
    {
        $rules = [
            'Poland' => [
                'temporary_reqs' => [
                    'permit_name' => 'Karta Czasowego Pobytu',
                    'validity' => '1-3 years',
                    'documents' => ['Annex 1 (Employer completion)', 'Medical Insurance', 'Lease Agreement', 'Tax Clearance']
                ],
                'permanent_reqs' => [
                    'years' => 5,
                    'income' => 'Stable and regular income',
                    'language' => 'B1 Polish Certificate'
                ],
                'citizenship_reqs' => [
                    'years' => '8-10 years total',
                    'tests' => ['Polish Language (B1)', 'Civic/History Test']
                ]
            ],
            'Switzerland' => [
                'temporary_reqs' => [
                    'permit_name' => 'Permit B (Residence)',
                    'validity' => '1-5 years',
                    'documents' => ['Employment Contract', 'Lease Agreement', 'Health Insurance Proof']
                ],
                'permanent_reqs' => [
                    'years' => '5 (VINT) or 10 (Standard)',
                    'income' => 'Financial independence',
                    'language' => 'A2/B1 Local Language (German/French/Italian)'
                ],
                'citizenship_reqs' => [
                    'years' => '10 years total',
                    'tests' => ['C Permit required', 'Local Cantonal stay (2-5 yrs)', 'Integration test']
                ]
            ],
            'Malta' => [
                'temporary_reqs' => [
                    'permit_name' => 'e-Residence Card / Single Permit',
                    'validity' => '1 year (Renewable)',
                    'documents' => ['Job Contract', 'Health Insurance', 'Police Conduct']
                ],
                'permanent_reqs' => [
                    'years' => 5,
                    'income' => 'Sufficient resources for family',
                    'language' => 'Integration Course + Maltese Basics'
                ],
                'citizenship_reqs' => [
                    'years' => '5-7 years residency',
                    'tests' => ['Police clearance', 'Community integration']
                ]
            ]
        ];

        $data = $rules[$type] ?? null;
        if ($data) {
            ResidencyRule::updateOrCreate(
                ['country_id' => $country->id],
                $data
            );
        }
    }

    private function seedJobs($country, $type)
    {
        $platforms = [
            'Poland' => [
                ['name' => 'Pracuj.pl', 'url' => 'https://www.pracuj.pl', 'cat' => 'General', 'tips' => 'Largest portal in Poland. Use "Direct Apply" filters.'],
                ['name' => 'LinkedIn Poland', 'url' => 'https://www.linkedin.com', 'cat' => 'Corporate/Tech', 'tips' => 'Crucial for multinational roles and networking.'],
                ['name' => 'JustJoin.it', 'url' => 'https://justjoin.it', 'cat' => 'Tech/IT', 'tips' => 'Best for salary transparency and remote dev roles.'],
            ],
            'Switzerland' => [
                ['name' => 'Jobs.ch', 'url' => 'https://www.jobs.ch', 'cat' => 'General', 'tips' => 'Market leader. Set up immediate match alerts.'],
                ['name' => 'Jobup.ch', 'url' => 'https://www.jobup.ch', 'cat' => 'General (French regions)', 'tips' => 'Primary source for Geneva and Lausanne regions.'],
                ['name' => 'SwissDevJobs.ch', 'url' => 'https://swissdevjobs.ch', 'cat' => 'Tech', 'tips' => 'Focuses on salary transparency and tech stacks.'],
            ],
            'Malta' => [
                ['name' => 'Keepmeposted.com.mt', 'url' => 'https://www.keepmeposted.com.mt', 'cat' => 'General', 'tips' => 'Largest reach for local jobs in Malta.'],
                ['name' => 'Jobsplus', 'url' => 'https://jobsplus.gov.mt', 'cat' => 'Gov/General', 'tips' => 'Mandatory registration for many work-study paths.'],
                ['name' => 'Konnekt', 'url' => 'https://www.konnekt.com', 'cat' => 'Finance/Tech', 'tips' => 'Excellent for specialized roles in iGaming and Fintech.'],
            ]
        ];

        foreach ($platforms[$type] ?? [] as $job) {
            JobPlatform::updateOrCreate(
                ['country_id' => $country->id, 'name' => $job['name']],
                ['website_url' => $job['url'], 'category' => $job['cat'], 'tips' => $job['tips']]
            );
        }
    }

    private function seedCV($country, $type)
    {
        $cvs = [
            'Poland' => [
                'name' => 'Polish Professional CV',
                'rules' => ['Photo expected', 'Age/DOB expected', 'GDPR/RODO clause mandatory', 'Polish for local firms, English for Tech'],
                'structure' => ['Summary', 'Contact', 'Experience', 'Education', 'Skills', 'Languages', 'RODO Clause']
            ],
            'Switzerland' => [
                'name' => 'Swiss Corporate CV',
                'rules' => ['Photo Mandatory', 'DOB/Age Mandatory', 'Marital Status common', 'Arbeitszeugnis (Work Certificates) attached'],
                'structure' => ['Contact & Profile', 'Core Competencies', 'Professional Experience', 'Education', 'Certifications', 'References']
            ],
            'Malta' => [
                'name' => 'Maltese Standard CV',
                'rules' => ['Photo NOT expected', 'Age NOT expected (UK style)', 'English language only', 'Europass format highly preferred'],
                'structure' => ['Contact Info', 'Personal Profile', 'Employment History', 'Education & Training', 'Digital Skills', 'Interests']
            ]
        ];

        $data = $cvs[$type] ?? null;
        if ($data) {
            CvTemplate::updateOrCreate(
                ['country_id' => $country->id],
                [
                    'name' => $data['name'],
                    'format_rules' => $data['rules'],
                    'structure_json' => $data['structure']
                ]
            );
        }
    }
}
