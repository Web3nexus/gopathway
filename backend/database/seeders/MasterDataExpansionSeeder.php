<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\VisaType;
use App\Models\DocumentType;
use App\Models\CostTemplate;
use App\Models\SettlementStep;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MasterDataExpansionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::enableForeignKeyConstraints();

        $this->seedGlobalDocuments();
        $this->seedUnitedKingdom();
        $this->seedCanada();
        $this->seedGermany();
        $this->seedNetherlands();
        $this->seedAustralia();
        $this->seedNewZealand();
        $this->seedIreland();
        $this->seedSpain();
        $this->seedPortugal();
        $this->seedFrance();
        $this->seedItaly();
        $this->seedSweden();
        $this->seedFinland();
        $this->seedNorway();
        $this->seedAustria();
        $this->seedNigeria();
    }

    private function seedGlobalDocuments()
    {
        $docs = [
            ['name' => 'International Passport', 'description' => 'Valid international travel document.'],
            ['name' => 'Passport Photos', 'description' => 'Recent biometric photos meeting country-specific standards.'],
            ['name' => 'Birth Certificate', 'description' => 'Official birth record, often requires translation or apostille.'],
            ['name' => 'Academic Transcripts', 'description' => 'Official records from school, college or university.'],
            ['name' => 'English Test Result', 'description' => 'IELTS, PTE, or TOEFL scores.'],
            ['name' => 'Police Clearance', 'description' => 'Criminal background check from home country and/or residence.'],
            ['name' => 'Medical Exam Results', 'description' => 'Health clearance from approved clinics.'],
            ['name' => 'Bank Statements', 'description' => 'Proof of sufficient funds (last 3-6 months).'],
            ['name' => 'Proof of Address', 'description' => 'Utility bills or rental contracts.'],
            ['name' => 'CV / Resume', 'description' => 'Detailed work and education history.'],
            ['name' => 'Motivation Letter', 'description' => 'Statement of purpose for the relocation.'],
        ];

        foreach ($docs as $doc) {
            DocumentType::updateOrCreate(['name' => $doc['name']], $doc);
        }
    }

    private function seedUnitedKingdom()
    {
        $country = Country::updateOrCreate(['code' => 'GB'], [
            'name' => 'United Kingdom',
            'description' => 'A global hub for education, technology, and finance with diverse immigration routes.',
            'image_url' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            [
                'name' => 'Skilled Worker Visa',
                'type' => 'Skilled Work',
                'description' => 'For professionals with a job offer from an approved UK employer.',
                'processing_time' => '3-8 weeks',
                'pr_possibility' => true,
                'min_funds' => 5000,
                'requirements' => ['Certificate of Sponsorship', 'English Language Proof (B1)', 'Salary ≥ £38,700', 'Job in eligible occupation'],
                'benefits' => ['Pathway to ILR (5 years)', 'Family can join', 'Access to NHS'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Application Fee', 'min' => 719, 'max' => 1500],
                    ['category' => 'Government Fees', 'item' => 'Immigration Health Surcharge (IHS)', 'min' => 1035, 'max' => 5175, 'notes' => 'Per year of visa'],
                    ['category' => 'Travel', 'item' => 'Flight to UK', 'min' => 450, 'max' => 1200],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit (5 weeks)', 'min' => 1200, 'max' => 3500],
                ]
            ],
            [
                'name' => 'Student Visa',
                'type' => 'Study',
                'description' => 'For international students admitted to UK universities and colleges.',
                'processing_time' => '3 weeks',
                'pr_possibility' => true,
                'min_funds' => 12000,
                'requirements' => ['CAS from University', 'English Proficiency', 'Proof of Funds', 'ATAS (if applicable)'],
                'benefits' => ['Work 20h/week', 'Graduate visa eligibility', 'World-class education'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee', 'min' => 490, 'max' => 490],
                    ['category' => 'Government Fees', 'item' => 'IHS', 'min' => 776, 'max' => 2328],
                    ['category' => 'Education', 'item' => 'Tuition Deposit', 'min' => 2000, 'max' => 10000],
                    ['category' => 'Living Costs', 'item' => 'Maintenance Funds (Outside London)', 'min' => 9207, 'max' => 9207],
                ]
            ],
            ['name' => 'Global Talent Visa', 'type' => 'Skilled Work', 'pr_possibility' => true, 'processing_time' => '4 weeks'],
            ['name' => 'High Potential Individual Visa', 'type' => 'Work', 'pr_possibility' => false, 'processing_time' => '3 weeks'],
            ['name' => 'Innovator Founder Visa', 'type' => 'Startup / Entrepreneur', 'pr_possibility' => true, 'processing_time' => '8 weeks'],
            ['name' => 'Graduate Visa', 'type' => 'Post-Study Work', 'pr_possibility' => true, 'processing_time' => '8 weeks'],
            ['name' => 'Spouse Visa', 'type' => 'Family', 'pr_possibility' => true, 'processing_time' => '12 weeks'],
        ];

        $this->processPathways($country, $pathways, 'GBP');
        $this->seedSettlement($country, 'UK');
    }

    private function seedCanada()
    {
        $country = Country::updateOrCreate(['code' => 'CA'], [
            'name' => 'Canada',
            'description' => 'Vast, welcoming, and high-quality living with clear permanent residency paths.',
            'image_url' => 'https://images.unsplash.com/photo-1503614472-8c93d56e92ce?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            [
                'name' => 'Express Entry (FSW)',
                'type' => 'Skilled Work',
                'description' => 'Federal program for skilled workers outside Canada.',
                'processing_time' => '6-12 months',
                'pr_possibility' => true,
                'min_funds' => 13757,
                'requirements' => ['ECA Report', 'IELTS Score (CLB 7+)', 'Work Experience (1 year+)', 'Proof of Funds'],
                'benefits' => ['Direct PR upon entry', 'Work for any employer', 'Sponsor family'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Application Fee', 'min' => 1365, 'max' => 1365],
                    ['category' => 'Documentation', 'item' => 'IELTS Exam', 'min' => 300, 'max' => 350],
                    ['category' => 'Documentation', 'item' => 'WES Evaluation', 'min' => 220, 'max' => 300],
                    ['category' => 'Travel', 'item' => 'Relocation Flight', 'min' => 600, 'max' => 1500],
                ]
            ],
            [
                'name' => 'Study Permit',
                'type' => 'Study',
                'description' => 'Study in Canadian institutions with work rights for you and your spouse.',
                'processing_time' => '2-4 months',
                'pr_possibility' => true,
                'min_funds' => 20635,
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Permit Fee', 'min' => 150, 'max' => 150],
                    ['category' => 'Education', 'item' => 'First Year Tuition', 'min' => 15000, 'max' => 35000],
                    ['category' => 'Living Costs', 'item' => 'GIC (Blocked Account)', 'min' => 20635, 'max' => 20635],
                ]
            ],
            ['name' => 'PNP - Provincial Nominee', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Post-Graduate Work Permit', 'type' => 'Post-Study Work', 'pr_possibility' => true],
            ['name' => 'Startup Visa', 'type' => 'Startup / Entrepreneur', 'pr_possibility' => true],
            ['name' => 'Home Child Care Provider', 'type' => 'Skilled Work', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'CAD');
        $this->seedSettlement($country, 'Canada');
    }

    private function seedGermany()
    {
        $country = Country::updateOrCreate(['code' => 'DE'], [
            'name' => 'Germany',
            'description' => 'Europe\'s powerhouse with free education and a high demand for skilled professionals.',
            'image_url' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            [
                'name' => 'EU Blue Card',
                'type' => 'Skilled Work',
                'description' => 'For highly qualified non-EU citizens with a high-salary job offer.',
                'processing_time' => '4-12 weeks',
                'pr_possibility' => true,
                'requirements' => ['University degree', 'Salary > €45,300', 'Job offer in Germany'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee', 'min' => 75, 'max' => 100],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit (Kaution)', 'min' => 1500, 'max' => 4500],
                    ['category' => 'Insurance', 'item' => 'Health Insurance (Monthly)', 'min' => 110, 'max' => 400],
                ]
            ],
            [
                'name' => 'Student Visa',
                'type' => 'Study',
                'description' => 'Study at world-class public universities with zero tuition in most states.',
                'processing_time' => '6-12 weeks',
                'pr_possibility' => true,
                'min_funds' => 11208,
                'costs' => [
                    ['category' => 'Living Costs', 'item' => 'Blocked Account (Sperrkonto)', 'min' => 11208, 'max' => 11208],
                    ['category' => 'Government Fees', 'item' => 'Visa Fee', 'min' => 75, 'max' => 75],
                    ['category' => 'Education', 'item' => 'Semester Contribution', 'min' => 150, 'max' => 400],
                ]
            ],
            ['name' => 'Job Seeker Visa', 'type' => 'Job Seeker', 'pr_possibility' => true],
            ['name' => 'Chancenkarte (Opportunity Card)', 'type' => 'Job Seeker', 'pr_possibility' => true],
            ['name' => 'Freelance Visa (Selbstständiger)', 'type' => 'Self-Employment', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Germany');
    }

    private function seedNetherlands()
    {
        $country = Country::updateOrCreate(['code' => 'NL'], [
            'name' => 'Netherlands',
            'description' => 'Innovative, English-friendly, and a gateway to the European market.',
            'image_url' => 'https://images.unsplash.com/photo-1576924542622-772281b13aa6?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            [
                'name' => 'Highly Skilled Migrant',
                'type' => 'Skilled Work',
                'description' => 'For professionals hired by recognized Dutch sponsors.',
                'processing_time' => '2-4 weeks',
                'pr_possibility' => true,
                'requirements' => ['Job from recognized sponsor', 'Salary threshold met'],
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Permit Fee', 'min' => 350, 'max' => 350],
                    ['category' => 'Housing Setup', 'item' => 'Rental Deposit', 'min' => 1600, 'max' => 4000],
                ]
            ],
            ['name' => 'Orientation Year Visa', 'type' => 'Post-Study Work', 'pr_possibility' => true],
            ['name' => 'Startup Visa', 'type' => 'Startup / Entrepreneur', 'pr_possibility' => true],
            ['name' => 'EU Blue Card', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'DAFT (Dutch-American Friendship Treaty)', 'type' => 'Self-Employment', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Netherlands');
    }

    private function seedAustralia()
    {
        $country = Country::updateOrCreate(['code' => 'AU'], [
            'name' => 'Australia',
            'description' => 'Sunshine, high wages, and a points-based system built for skilled immigrants.',
            'image_url' => 'https://images.unsplash.com/photo-1523428096881-5bd79d043006?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            [
                'name' => 'Skilled Independent (189)',
                'type' => 'Skilled Work',
                'description' => 'Direct permanent residency based on points, no sponsorship needed.',
                'processing_time' => '12-24 months',
                'pr_possibility' => true,
                'min_funds' => 10000,
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee', 'min' => 4640, 'max' => 4640],
                    ['category' => 'Documentation', 'item' => 'Skills Assessment', 'min' => 500, 'max' => 1500],
                ]
            ],
            [
                'name' => 'Student Visa (500)',
                'type' => 'Study',
                'description' => 'Study in Australia with excellent post-graduate work options.',
                'processing_time' => '4-8 weeks',
                'pr_possibility' => true,
                'min_funds' => 24505,
                'costs' => [
                    ['category' => 'Government Fees', 'item' => 'Visa Fee', 'min' => 710, 'max' => 710],
                    ['category' => 'Insurance', 'item' => 'OSHC (Health Insurance)', 'min' => 1500, 'max' => 3500],
                ]
            ],
            ['name' => 'Skilled Nominated (190)', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Temporary Skill Shortage (482)', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Working Holiday (417)', 'type' => 'Working Holiday', 'pr_possibility' => false],
            ['name' => 'Global Talent Visa (858)', 'type' => 'Skilled Work', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'AUD');
        $this->seedSettlement($country, 'Australia');
    }

    private function seedNewZealand()
    {
        $country = Country::updateOrCreate(['code' => 'NZ'], [
            'name' => 'New Zealand',
            'description' => 'Unbeatable quality of life, nature, and a growing need for skilled workers.',
            'image_url' => 'https://images.unsplash.com/photo-1507699622108-4be3abd695ad?q=80&w=2671&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Skilled Migrant Category', 'type' => 'Skilled Work', 'pr_possibility' => true, 'processing_time' => '12 months'],
            ['name' => 'Accredited Employer Work Visa', 'type' => 'Skilled Work', 'pr_possibility' => true, 'processing_time' => '2 months'],
            ['name' => 'Green List Straight to Residence', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Student Visa', 'type' => 'Study', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'NZD');
        $this->seedSettlement($country, 'New Zealand');
    }

    private function seedIreland()
    {
        $country = Country::updateOrCreate(['code' => 'IE'], [
            'name' => 'Ireland',
            'description' => 'The English-speaking heart of the EU, perfect for tech and finance professionals.',
            'image_url' => 'https://images.unsplash.com/photo-1564959130747-897fb406b9af?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Critical Skills Employment Permit', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'General Employment Permit', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Stamp 1G (Graduate Scheme)', 'type' => 'Post-Study Work', 'pr_possibility' => true],
            ['name' => 'Student Visa (Stamp 2)', 'type' => 'Study', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Ireland');
    }

    private function seedSpain()
    {
        $country = Country::updateOrCreate(['code' => 'ES'], [
            'name' => 'Spain',
            'description' => 'Warm weather, rich culture, and the popular Digital Nomad Visa.',
            'image_url' => 'https://images.unsplash.com/photo-1543783207-ec64e4d95325?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Digital Nomad Visa', 'type' => 'Digital Nomad', 'pr_possibility' => true, 'processing_time' => '20 days'],
            ['name' => 'Non-Lucrative Visa', 'type' => 'Residency', 'pr_possibility' => true, 'processing_time' => '3 months'],
            ['name' => 'Highly Qualified Professional', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Golden Visa (Investment)', 'type' => 'Investment', 'pr_possibility' => true],
            ['name' => 'Student Visa', 'type' => 'Study', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Spain');
    }

    private function seedPortugal()
    {
        $country = Country::updateOrCreate(['code' => 'PT'], [
            'name' => 'Portugal',
            'description' => 'One of Europe\'s most peaceful countries with diverse residency options.',
            'image_url' => 'https://images.unsplash.com/photo-1555881400-74d7acaacd8b?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'D7 (Passive Income/Retiree)', 'type' => 'Residency', 'pr_possibility' => true],
            ['name' => 'Digital Nomad Visa (D8)', 'type' => 'Digital Nomad', 'pr_possibility' => true],
            ['name' => 'D2 (Entrepreneur Visa)', 'type' => 'Startup / Entrepreneur', 'pr_possibility' => true],
            ['name' => 'Golden Visa', 'type' => 'Investment', 'pr_possibility' => true],
            ['name' => 'Student Visa', 'type' => 'Study', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Portugal');
    }

    private function seedFrance()
    {
        $country = Country::updateOrCreate(['code' => 'FR'], [
            'name' => 'France',
            'description' => 'Culture, career, and a high standard of public security and support.',
            'image_url' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Talent Passport', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'VLS-TS Student Visa', 'type' => 'Study', 'pr_possibility' => true],
            ['name' => 'APS - Job Search permit', 'type' => 'Post-Study Work', 'pr_possibility' => true],
            ['name' => 'Entrepreneur / Profession Libérale', 'type' => 'Self-Employment', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'France');
    }

    private function seedItaly()
    {
        $country = Country::updateOrCreate(['code' => 'IT'], [
            'name' => 'Italy',
            'description' => 'Affordable living, deep history, and new pathways for remote workers.',
            'image_url' => 'https://images.unsplash.com/photo-1515542622106-078bda69bf98?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Digital Nomad Visa', 'type' => 'Digital Nomad', 'pr_possibility' => true],
            ['name' => 'Student Visa', 'type' => 'Study', 'pr_possibility' => true],
            ['name' => 'Work Permit (Decreto Flussi)', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Elective Residency Visa', 'type' => 'Residency', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Italy');
    }

    private function seedSweden()
    {
        $country = Country::updateOrCreate(['code' => 'SE'], [
            'name' => 'Sweden',
            'description' => 'Innovation hub with radical work-life balance and social equality.',
            'image_url' => 'https://images.unsplash.com/photo-1509356843151-3e7d96241e11?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Work Permit', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Student Residence Permit', 'type' => 'Study', 'pr_possibility' => true],
            ['name' => 'EU Blue Card', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'ICT Permit', 'type' => 'Skilled Work', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Sweden');
    }

    private function seedFinland()
    {
        $country = Country::updateOrCreate(['code' => 'FI'], [
            'name' => 'Finland',
            'description' => 'The world\'s happiest country with free high-level education.',
            'image_url' => 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Specialist Residence Permit', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Startup Entrepreneur Permit', 'type' => 'Startup / Entrepreneur', 'pr_possibility' => true],
            ['name' => 'Student Residence Permit', 'type' => 'Study', 'pr_possibility' => true],
            ['name' => 'Job Seeker Permit', 'type' => 'Job Seeker', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Finland');
    }

    private function seedNorway()
    {
        $country = Country::updateOrCreate(['code' => 'NO'], [
            'name' => 'Norway',
            'description' => 'Stunning nature and safe society with high job security.',
            'image_url' => 'https://images.unsplash.com/photo-1531366936337-7c912a4589a7?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Skilled Worker Visa', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Student Residence Permit', 'type' => 'Study', 'pr_possibility' => true],
            ['name' => 'Job Seeker Visa', 'type' => 'Job Seeker', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Norway');
    }

    private function seedAustria()
    {
        $country = Country::updateOrCreate(['code' => 'AT'], [
            'name' => 'Austria',
            'description' => 'Central European quality of life with excellent connectivity.',
            'image_url' => 'https://images.unsplash.com/photo-1609347644591-4b4d01c1d28c?q=80&w=1200&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Red-White-Red Card', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Student Residence Permit', 'type' => 'Study', 'pr_possibility' => true],
            ['name' => 'Job Seeker Visa', 'type' => 'Job Seeker', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'EUR');
        $this->seedSettlement($country, 'Austria');
    }

    private function seedNigeria()
    {
        $country = Country::updateOrCreate(['code' => 'NG'], [
            'name' => 'Nigeria',
            'description' => 'A vibrant source of global talent looking for international expansion.',
            'image_url' => 'https://images.unsplash.com/photo-1541469585074-066822831d4b?q=80&w=2669&auto=format&fit=crop',
        ]);

        $pathways = [
            ['name' => 'Study Abroad Preparation', 'type' => 'Study', 'pr_possibility' => true],
            ['name' => 'Tech Relocation Program', 'type' => 'Skilled Work', 'pr_possibility' => true],
            ['name' => 'Business Relocation', 'type' => 'Startup / Entrepreneur', 'pr_possibility' => true],
        ];

        $this->processPathways($country, $pathways, 'USD');
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
                'requirements' => $p['requirements'] ?? ['Passport', 'Proof of Funds', 'No Criminal Record'],
                'benefits' => $p['benefits'] ?? ['Residence Rights', 'Schengen Travel (if EU)', 'Path to Citizenship'],
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
                        'notes' => $cost['notes'] ?? null,
                    ]
                    );
                }
            }
            else {
                CostTemplate::updateOrCreate(
                ['visa_type_id' => $visa->id, 'item' => 'General Relocation Budget'],
                [
                    'category' => 'Living Costs',
                    'min_cost' => 4000,
                    'max_cost' => 12000,
                    'currency' => $defaultCurrency,
                    'notes' => 'Estimated total preparation and first month costs.'
                ]
                );
            }
        }
    }

    private function seedSettlement($country, $type)
    {
        $steps = [];
        switch ($type) {
            case 'Germany':
                $steps = [
                    ['phase' => 'week1', 'title' => 'Bürgeramt Registration', 'description' => 'Register your address at the local city hall.'],
                    ['phase' => 'week1', 'title' => 'Open Bank Account', 'description' => 'Common: N26, Deutsche Bank, Sparkasse.'],
                    ['phase' => 'month1', 'title' => 'Health Insurance', 'description' => 'Mandatory public (TK, AOK) or private insurance.'],
                ];
                break;
            case 'UK':
                $steps = [
                    ['phase' => 'week1', 'title' => 'BRP Collection', 'description' => 'Collect your Biometric Residence Permit from the Post Office.'],
                    ['phase' => 'week1', 'title' => 'GP Registration', 'description' => 'Register with a local doctor (General Practitioner).'],
                    ['phase' => 'month1', 'title' => 'National Insurance Number', 'description' => 'Apply for your NI number for work and benefits.'],
                ];
                break;
            default:
                $steps = [
                    ['phase' => 'week1', 'title' => 'Address Registration', 'description' => 'Register with the local municipality.'],
                    ['phase' => 'week1', 'title' => 'Open Local Bank Account', 'description' => 'Essential for managing expenses.'],
                    ['phase' => 'month1', 'title' => 'Tax Number ID', 'description' => 'Secure your local tax identification number.'],
                ];
        }

        foreach ($steps as $idx => $step) {
            SettlementStep::updateOrCreate(
            ['country_id' => $country->id, 'title' => $step['title']],
                array_merge($step, ['order' => $idx])
            );
        }
    }
}