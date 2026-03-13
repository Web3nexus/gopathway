<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\VisaType;
use App\Models\CostTemplate;

class PathwayExpansionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'GB' => [
                'name' => 'United Kingdom',
                'pathways' => [
                    [
                        'name' => 'Skilled Worker',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'GBP',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 719, 'max' => 1500, 'notes' => '£719 - £1,500 (varies by duration)'],
                            ['category' => 'Requirements', 'item' => 'Health Surcharge (IHS)', 'min' => 1035, 'max' => 1035, 'notes' => '£1,035/year'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Admin', 'min' => 19, 'max' => 55, 'notes' => '~£19.20 (UK) or ~£55 (Abroad)'],
                            ['category' => 'Documentation', 'item' => 'Language / Med Tests', 'min' => 100, 'max' => 200, 'notes' => 'English: ~£200; TB Test: ~£100'],
                            ['category' => 'Requirements', 'item' => 'Degree/Skills Eval.', 'min' => 140, 'max' => 140, 'notes' => 'Ecctis (if needed): ~£140'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living/Setup)', 'min' => 28, 'max' => 1270, 'notes' => '£1,270 for 28 days'],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'GBP',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 490, 'max' => 490, 'notes' => '£490'],
                            ['category' => 'Requirements', 'item' => 'Health Surcharge (IHS)', 'min' => 776, 'max' => 776, 'notes' => '£776/year'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Admin', 'min' => 19, 'max' => 55, 'notes' => '~£19.20 (UK) or ~£55 (Abroad)'],
                            ['category' => 'Documentation', 'item' => 'Language / Med Tests', 'min' => 100, 'max' => 200, 'notes' => 'English: ~£200; TB Test: ~£100'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living/Setup)', 'min' => 9, 'max' => 1334, 'notes' => '£1,334/mo (London) or £1,023/mo (Outside) up to 9 mos'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Tuition/Capital)', 'min' => 1, 'max' => 1, 'notes' => 'Full 1st year tuition'],
                        ]
                    ],
                    [
                        'name' => 'Global Talent',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'GBP',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 192, 'max' => 715, 'notes' => '£715 (£192 Approval + £523 Visa)'],
                            ['category' => 'Requirements', 'item' => 'Health Surcharge (IHS)', 'min' => 1035, 'max' => 1035, 'notes' => '£1,035/year'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Admin', 'min' => 19, 'max' => 55, 'notes' => '~£19.20 - £55'],
                            ['category' => 'Requirements', 'item' => 'Degree/Skills Eval.', 'min' => 524, 'max' => 524, 'notes' => 'Endorsement Body fee: £524'],
                        ]
                    ],
                    [
                        'name' => 'Family/Spouse',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'GBP',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 1846, 'max' => 1846, 'notes' => '£1,846'],
                            ['category' => 'Requirements', 'item' => 'Health Surcharge (IHS)', 'min' => 1035, 'max' => 1035, 'notes' => '£1,035/year'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Admin', 'min' => 19, 'max' => 55, 'notes' => '~£19.20 - £55'],
                            ['category' => 'Documentation', 'item' => 'Language / Med Tests', 'min' => 100, 'max' => 200, 'notes' => 'English (£150-£200); TB Test (~£100)'],
                            ['category' => 'Requirements', 'item' => 'Degree/Skills Eval.', 'min' => 50, 'max' => 100, 'notes' => 'Translations: ~£50-100'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living/Setup)', 'min' => 29000, 'max' => 29000, 'notes' => 'Minimum Income Req: £29,000/yr'],
                        ]
                    ],
                ]
            ],
            'CA' => [
                'name' => 'Canada',
                'pathways' => [
                    [
                        'name' => 'Express Entry (FSW/CEC/FST)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'CAD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee + Right of PR Fee', 'min' => 575, 'max' => 950, 'notes' => '$950 App + $575 RPRF (CAD)'],
                            ['category' => 'Requirements', 'item' => 'Biometrics', 'min' => 85, 'max' => 85, 'notes' => '$85 CAD'],
                            ['category' => 'Requirements', 'item' => 'Medical Exam', 'min' => 200, 'max' => 200, 'notes' => '~$200 CAD'],
                            ['category' => 'Documentation', 'item' => 'Language Test', 'min' => 300, 'max' => 300, 'notes' => 'IELTS/CELPIP: ~$300 CAD'],
                            ['category' => 'Requirements', 'item' => 'Educ. Credential Assess (ECA)', 'min' => 250, 'max' => 250, 'notes' => 'WES/ICAS: ~$250 CAD'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living/Setup)', 'min' => 1, 'max' => 13757, 'notes' => '~$13,757 CAD for 1 person'],
                        ]
                    ],
                    [
                        'name' => 'Provincial Nominee (PNP)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'CAD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee + Right of PR Fee', 'min' => 250, 'max' => 1500, 'notes' => '$950 App + $575 RPRF + Prov Fee ($250-$1,500)'],
                            ['category' => 'Requirements', 'item' => 'Biometrics', 'min' => 85, 'max' => 85, 'notes' => '$85 CAD'],
                            ['category' => 'Requirements', 'item' => 'Medical Exam', 'min' => 200, 'max' => 200, 'notes' => '~$200 CAD'],
                            ['category' => 'Documentation', 'item' => 'Language Test', 'min' => 300, 'max' => 300, 'notes' => 'IELTS/CELPIP: ~$300 CAD'],
                            ['category' => 'Requirements', 'item' => 'Educ. Credential Assess (ECA)', 'min' => 250, 'max' => 250, 'notes' => 'WES/ICAS: ~$250 CAD'],
                        ]
                    ],
                    [
                        'name' => 'Study Permit',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'CAD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee + Right of PR Fee', 'min' => 150, 'max' => 150, 'notes' => '$150 CAD'],
                            ['category' => 'Requirements', 'item' => 'Biometrics', 'min' => 85, 'max' => 85, 'notes' => '$85 CAD'],
                            ['category' => 'Requirements', 'item' => 'Medical Exam', 'min' => 200, 'max' => 200, 'notes' => '~$200 CAD'],
                            ['category' => 'Documentation', 'item' => 'Language Test', 'min' => 300, 'max' => 300, 'notes' => 'IELTS/PTE (varies by school): ~$300 CAD'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living/Setup)', 'min' => 20635, 'max' => 20635, 'notes' => '~$20,635 CAD/year (Base living exp)'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Tuition/Capital)', 'min' => 1, 'max' => 1, 'notes' => 'Full 1st year tuition'],
                        ]
                    ],
                    [
                        'name' => 'Family Sponsorship',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'CAD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee + Right of PR Fee', 'min' => 1210, 'max' => 1210, 'notes' => '$1,210 CAD (incl. PR Fee)'],
                            ['category' => 'Requirements', 'item' => 'Biometrics', 'min' => 85, 'max' => 85, 'notes' => '$85 CAD'],
                            ['category' => 'Requirements', 'item' => 'Medical Exam', 'min' => 200, 'max' => 200, 'notes' => '~$200 CAD'],
                        ]
                    ],
                ]
            ],
            'AU' => [
                'name' => 'Australia',
                'pathways' => [
                    [
                        'name' => 'Skilled Independent (189/190)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'AUD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 4640, 'max' => 4640, 'notes' => '$4,640 AUD'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Admin', 'min' => 100, 'max' => 100, 'notes' => '~$100 AUD (Biometrics/Police)'],
                            ['category' => 'Documentation', 'item' => 'Language / Med Tests', 'min' => 300, 'max' => 400, 'notes' => 'IELTS/PTE: ~$400 AUD; Med: ~$300 AUD'],
                            ['category' => 'Requirements', 'item' => 'Skills Assessment', 'min' => 500, 'max' => 1200, 'notes' => '$500 - $1,200 AUD (varies by authority)'],
                        ]
                    ],
                    [
                        'name' => 'Employer Sponsored (TSS 482)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'AUD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 1455, 'max' => 3035, 'notes' => '$1,455 - $3,035 AUD'],
                            ['category' => 'Requirements', 'item' => 'Surcharges / Insurance', 'min' => 1000, 'max' => 1000, 'notes' => 'OVHC: ~$1,000/year'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Admin', 'min' => 100, 'max' => 100, 'notes' => '~$100 AUD'],
                            ['category' => 'Documentation', 'item' => 'Language / Med Tests', 'min' => 400, 'max' => 400, 'notes' => 'IELTS/PTE: ~$400 AUD'],
                            ['category' => 'Requirements', 'item' => 'Skills Assessment', 'min' => 500, 'max' => 1200, 'notes' => '$500 - $1,200 AUD (if requested)'],
                        ]
                    ],
                    [
                        'name' => 'Student (subclass 500)',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'AUD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 710, 'max' => 710, 'notes' => '$710 AUD'],
                            ['category' => 'Requirements', 'item' => 'Surcharges / Insurance', 'min' => 500, 'max' => 800, 'notes' => 'OSHC: ~$500 - $800/year'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Admin', 'min' => 100, 'max' => 100, 'notes' => '~$100 AUD'],
                            ['category' => 'Documentation', 'item' => 'Language / Med Tests', 'min' => 300, 'max' => 400, 'notes' => 'IELTS/PTE/TOEFL: ~$400 AUD; Med: ~$300 AUD'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living/Setup)', 'min' => 24505, 'max' => 24505, 'notes' => 'Minimum $24,505 AUD/year'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Tuition/Capital)', 'min' => 1, 'max' => 1, 'notes' => 'Full 1st year tuition'],
                        ]
                    ],
                    [
                        'name' => 'Partner Visa (820/801)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'AUD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 8850, 'max' => 8850, 'notes' => '$8,850 AUD'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Admin', 'min' => 100, 'max' => 100, 'notes' => '~$100 AUD (Police, Admin)'],
                            ['category' => 'Documentation', 'item' => 'Language / Med Tests', 'min' => 300, 'max' => 300, 'notes' => 'Med: ~$300 AUD'],
                        ]
                    ],
                ]
            ],
            'NZ' => [
                'name' => 'New Zealand',
                'pathways' => [
                    [
                        'name' => 'Skilled Migrant Category',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'NZD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 4290, 'max' => 4290, 'notes' => '$4,290 NZD'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Police Checks', 'min' => 150, 'max' => 150, 'notes' => '~$150 NZD'],
                            ['category' => 'Documentation', 'item' => 'Language Test & Medical Exam', 'min' => 300, 'max' => 400, 'notes' => 'IELTS/PTE: ~$400 NZD; Medical: ~$300 NZD'],
                            ['category' => 'Requirements', 'item' => 'Skills Assessment (NZQA)', 'min' => 746, 'max' => 746, 'notes' => 'NZQA Assessment: ~$746 NZD'],
                        ]
                    ],
                    [
                        'name' => 'Accredited Employer Work',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'NZD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 750, 'max' => 750, 'notes' => '$750 NZD'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Police Checks', 'min' => 150, 'max' => 150, 'notes' => '~$150 NZD'],
                            ['category' => 'Documentation', 'item' => 'Language Test & Medical Exam', 'min' => 300, 'max' => 300, 'notes' => 'Medical: ~$300 NZD'],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'NZD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 375, 'max' => 375, 'notes' => '$375 NZD'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Police Checks', 'min' => 150, 'max' => 150, 'notes' => '~$150 NZD'],
                            ['category' => 'Documentation', 'item' => 'Language Test & Medical Exam', 'min' => 300, 'max' => 400, 'notes' => 'IELTS/PTE (School req): ~$400 NZD; Med: ~$300'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living/Setup)', 'min' => 20000, 'max' => 20000, 'notes' => 'Minimum $20,000 NZD/year'],
                        ]
                    ],
                    [
                        'name' => 'Partner of a NZer',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'NZD',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 2750, 'max' => 2750, 'notes' => '$2,750 NZD'],
                            ['category' => 'Requirements', 'item' => 'Biometrics & Police Checks', 'min' => 150, 'max' => 150, 'notes' => '~$150 NZD'],
                            ['category' => 'Documentation', 'item' => 'Language Test & Medical Exam', 'min' => 300, 'max' => 300, 'notes' => 'Medical: ~$300 NZD'],
                        ]
                    ],
                ]
            ],
            'DE' => [
                'name' => 'Germany',
                'pathways' => [
                    [
                        'name' => 'EU Blue Card (Skilled Worker)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa Application Fee', 'min' => 75, 'max' => 100, 'notes' => '€75 - €100 (varies locally)'],
                            ['category' => 'Requirements', 'item' => 'Admin / Biometrics / Legalization', 'min' => 100, 'max' => 300, 'notes' => 'Reg./Translation: ~€100-€300'],
                            ['category' => 'Requirements', 'item' => 'Degree Recognition (ZAB)', 'min' => 200, 'max' => 200, 'notes' => 'ZAB Evaluation: €200'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 45, 'max' => 45, 'notes' => 'Contract covers minimum threshold (~€45k+)'],
                        ]
                    ],
                    [
                        'name' => 'Job Seeker (Opportunity Card)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa Application Fee', 'min' => 75, 'max' => 75, 'notes' => '€75'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 100, 'max' => 100, 'notes' => 'Required (Private coverage, min €100/mo)'],
                            ['category' => 'Requirements', 'item' => 'Admin / Biometrics / Legalization', 'min' => 100, 'max' => 100, 'notes' => 'Reg./Translation: ~€100'],
                            ['category' => 'Documentation', 'item' => 'Language Test', 'min' => 1, 'max' => 150, 'notes' => 'A1 DE / B2 EN certificate: ~€150'],
                            ['category' => 'Requirements', 'item' => 'Degree Recognition (ZAB)', 'min' => 200, 'max' => 200, 'notes' => 'ZAB Evaluation: €200'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 1027, 'max' => 1027, 'notes' => '€1,027/month (Blocked Account)'],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa Application Fee', 'min' => 75, 'max' => 75, 'notes' => '€75'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 120, 'max' => 120, 'notes' => 'Required (~€120/month Statutory)'],
                            ['category' => 'Requirements', 'item' => 'Admin / Biometrics / Legalization', 'min' => 100, 'max' => 100, 'notes' => 'Reg./Translation/Notary: ~€100'],
                            ['category' => 'Documentation', 'item' => 'Language Test', 'min' => 200, 'max' => 200, 'notes' => 'DaF / IELTS: ~€200'],
                            ['category' => 'Requirements', 'item' => 'Degree Recognition (ZAB)', 'min' => 75, 'max' => 75, 'notes' => 'Uni-Assist: ~€75/application'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 11208, 'max' => 11208, 'notes' => '€11,208/year (Blocked Account)'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Tuition/Capital)', 'min' => 150, 'max' => 350, 'notes' => 'Uni Enrollment (~€150-€350/sem)'],
                        ]
                    ],
                    [
                        'name' => 'Freelance/Self-Employed',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa Application Fee', 'min' => 100, 'max' => 100, 'notes' => '€100'],
                            ['category' => 'Requirements', 'item' => 'Admin / Biometrics / Legalization', 'min' => 500, 'max' => 500, 'notes' => 'Business setup/Notary fees: ~€500+'],
                        ]
                    ],
                ]
            ],
            'NL' => [
                'name' => 'Netherlands',
                'pathways' => [
                    [
                        'name' => 'Highly Skilled Migrant',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (IND/MVV)', 'min' => 380, 'max' => 380, 'notes' => '€380'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 150, 'max' => 150, 'notes' => 'Required (~€150/month)'],
                            ['category' => 'Requirements', 'item' => 'Translation / Legalizations', 'min' => 150, 'max' => 300, 'notes' => '~€150 - €300 for degrees/certs'],
                            ['category' => 'Requirements', 'item' => 'Integration Exam Req.', 'min' => 150, 'max' => 150, 'notes' => 'Inburgering (for PR later): €150+'],
                        ]
                    ],
                    [
                        'name' => 'Student/Orientation Year',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (IND/MVV)', 'min' => 228, 'max' => 228, 'notes' => '€228'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 100, 'max' => 150, 'notes' => 'Required (~€100-150/month)'],
                            ['category' => 'Requirements', 'item' => 'Translation / Legalizations', 'min' => 150, 'max' => 150, 'notes' => '~€150'],
                            ['category' => 'Requirements', 'item' => 'Integration Exam Req.', 'min' => 250, 'max' => 250, 'notes' => 'English test (TOEFL/IELTS): ~€250'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 1200, 'max' => 1200, 'notes' => '~€1,200/month'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Tuition/Capital)', 'min' => 1, 'max' => 1, 'notes' => 'Full tuition paid upfront (1st yr)'],
                        ]
                    ],
                    [
                        'name' => 'Self-Employed/DAFT(US/JP)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (IND/MVV)', 'min' => 1529, 'max' => 1529, 'notes' => '€1,529'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 150, 'max' => 150, 'notes' => 'Required (~€150/month)'],
                            ['category' => 'Requirements', 'item' => 'Translation / Legalizations', 'min' => 75, 'max' => 75, 'notes' => 'KVK (Chamber of Commerce): ~€75'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 1500, 'max' => 1500, 'notes' => 'Min standard set by IND (~€1,500/mo)'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Tuition/Capital)', 'min' => 4500, 'max' => 4500, 'notes' => '€4,500 Business Capital (DAFT)'],
                        ]
                    ],
                ]
            ],
            'SE' => [
                'name' => 'Sweden',
                'pathways' => [
                    [
                        'name' => 'Work Permit',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'SEK',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 2200, 'max' => 2200, 'notes' => '2,200 SEK'],
                            ['category' => 'Requirements', 'item' => 'Identity / Translations', 'min' => 1000, 'max' => 1000, 'notes' => 'Appx: 1,000 SEK'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 13000, 'max' => 13000, 'notes' => '13,000 SEK/month min salary req'],
                        ]
                    ],
                    [
                        'name' => 'Job Seeker Visa',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'SEK',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 2200, 'max' => 2200, 'notes' => '2,200 SEK'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance / Healthcare', 'min' => 500, 'max' => 500, 'notes' => 'Required (Private, ~500 SEK/mo)'],
                            ['category' => 'Requirements', 'item' => 'Identity / Translations', 'min' => 1000, 'max' => 1000, 'notes' => 'Appx: 1,000 SEK'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 9, 'max' => 13000, 'notes' => '13,000 SEK/month (up to 9 mo)'],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'SEK',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 1500, 'max' => 1500, 'notes' => '1,500 SEK'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance / Healthcare', 'min' => 1, 'max' => 1, 'notes' => 'Required if <1 yr program'],
                            ['category' => 'Requirements', 'item' => 'Identity / Translations', 'min' => 1000, 'max' => 1000, 'notes' => 'Appx: 1,000 SEK'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 10314, 'max' => 10314, 'notes' => '10,314 SEK/month'],
                        ]
                    ],
                    [
                        'name' => 'Family/Cohabiting',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'SEK',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application Fee', 'min' => 2000, 'max' => 2000, 'notes' => '2,000 SEK'],
                            ['category' => 'Requirements', 'item' => 'Identity / Translations', 'min' => 1000, 'max' => 1000, 'notes' => 'Appx: 1,000 SEK'],
                        ]
                    ],
                ]
            ],
            'FI' => [
                'name' => 'Finland',
                'pathways' => [
                    [
                        'name' => 'Specialist/Skilled Worker',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (Online/Paper)', 'min' => 380, 'max' => 480, 'notes' => '€380 / €480'],
                            ['category' => 'Requirements', 'item' => 'Legalizations / Biometrics', 'min' => 100, 'max' => 100, 'notes' => 'Document Legalization: €100+'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 3000, 'max' => 3000, 'notes' => 'Min salary requirement (>€3,000/mo)'],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (Online/Paper)', 'min' => 350, 'max' => 450, 'notes' => '€350 / €450'],
                            ['category' => 'Requirements', 'item' => 'Legalizations / Biometrics', 'min' => 100, 'max' => 100, 'notes' => 'Medical/Translations: €100+'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 800, 'max' => 9600, 'notes' => '€800/month (€9,600/year)'],
                        ]
                    ],
                    [
                        'name' => 'Startup Entrepreneur',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (Online/Paper)', 'min' => 350, 'max' => 480, 'notes' => '€350 / €480'],
                        ]
                    ],
                    [
                        'name' => 'Family Ties',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (Online/Paper)', 'min' => 470, 'max' => 520, 'notes' => '€470 / €520'],
                            ['category' => 'Requirements', 'item' => 'Legalizations / Biometrics', 'min' => 100, 'max' => 100, 'notes' => 'Translations: €100+'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 1000, 'max' => 1000, 'notes' => 'Sponsor income (e.g., €1,000/mo per child)'],
                        ]
                    ],
                ]
            ],
            'AT' => [
                'name' => 'Austria',
                'pathways' => [
                    [
                        'name' => 'Red-White-Red (Skilled)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee / Residence Card', 'min' => 160, 'max' => 160, 'notes' => '€160'],
                            ['category' => 'Requirements', 'item' => 'Legalizations/Translation', 'min' => 200, 'max' => 200, 'notes' => 'Appx: €200'],
                            ['category' => 'Requirements', 'item' => 'Language Exam / Deg. Eval', 'min' => 1, 'max' => 150, 'notes' => 'A1/A2 German (if claimed): ~€150'],
                        ]
                    ],
                    [
                        'name' => 'Job Seeker',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee / Residence Card', 'min' => 150, 'max' => 150, 'notes' => '€150'],
                            ['category' => 'Requirements', 'item' => 'Legalizations/Translation', 'min' => 100, 'max' => 100, 'notes' => 'Appx: €100'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 1217, 'max' => 1217, 'notes' => '€1,217.96/month (Single standard)'],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee / Residence Card', 'min' => 160, 'max' => 160, 'notes' => '€160'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 67, 'max' => 67, 'notes' => 'Student insurance (€67/mo)'],
                            ['category' => 'Requirements', 'item' => 'Legalizations/Translation', 'min' => 100, 'max' => 100, 'notes' => 'Appx: €100'],
                            ['category' => 'Requirements', 'item' => 'Language Exam / Deg. Eval', 'min' => 150, 'max' => 150, 'notes' => 'English/German Cert: ~€150'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 24, 'max' => 1217, 'notes' => '€1,217.96/month (If 24+) or ~€672 (If <24)'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Tuition)', 'min' => 726, 'max' => 726, 'notes' => 'Up to ~€726/semester'],
                        ]
                    ],
                ]
            ],
            'FR' => [
                'name' => 'France',
                'pathways' => [
                    [
                        'name' => 'Talent Passport (Skilled)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App / Residence Fee (Tax)', 'min' => 225, 'max' => 225, 'notes' => '€225'],
                            ['category' => 'Requirements', 'item' => 'Legalizations/Translations', 'min' => 150, 'max' => 250, 'notes' => 'Medical (OFII): €250; Trans: €150'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 42406, 'max' => 42406, 'notes' => 'Minimum Gross Salary (€42,406+)'],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App / Residence Fee (Tax)', 'min' => 50, 'max' => 99, 'notes' => '€50 - €99 + €75 VLS-TS'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance / CVEC', 'min' => 103, 'max' => 103, 'notes' => 'CVEC (€103/year)'],
                            ['category' => 'Requirements', 'item' => 'Legalizations/Translations', 'min' => 250, 'max' => 250, 'notes' => 'Campus France Registration (€250)'],
                            ['category' => 'Requirements', 'item' => 'Language Req.', 'min' => 150, 'max' => 150, 'notes' => 'TCF/DELF: €150'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 615, 'max' => 615, 'notes' => '€615/month'],
                        ]
                    ],
                    [
                        'name' => 'Profession Libérale',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App / Residence Fee (Tax)', 'min' => 225, 'max' => 225, 'notes' => '€225'],
                            ['category' => 'Requirements', 'item' => 'Legalizations/Translations', 'min' => 100, 'max' => 100, 'notes' => 'Chamber of Commerce Reg: €100'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 1766, 'max' => 1766, 'notes' => 'Min wage (SMIC, ~€1,766 gross/mo)'],
                        ]
                    ],
                ]
            ],
            'ES' => [
                'name' => 'Spain',
                'pathways' => [
                    [
                        'name' => 'Highly Qualified (PAC)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application/Auth Fees', 'min' => 16, 'max' => 73, 'notes' => '~€73 (Auth) + ~€16 (TIE)'],
                            ['category' => 'Requirements', 'item' => 'Notary / Hague Apostille', 'min' => 300, 'max' => 300, 'notes' => 'Translation/Apostille: €300+'],
                            ['category' => 'Requirements', 'item' => 'TIE / NIE Cards', 'min' => 30, 'max' => 30, 'notes' => '~€30'],
                        ]
                    ],
                    [
                        'name' => 'Digital Nomad',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application/Auth Fees', 'min' => 16, 'max' => 73, 'notes' => '~€73 (Auth) + ~€16 (TIE)'],
                            ['category' => 'Requirements', 'item' => 'Notary / Hague Apostille', 'min' => 300, 'max' => 300, 'notes' => 'Translation/Apostille: €300+'],
                            ['category' => 'Requirements', 'item' => 'TIE / NIE Cards', 'min' => 30, 'max' => 30, 'notes' => '~€30'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 200, 'max' => 2520, 'notes' => '200% of SMI (~€2,520/month)'],
                        ]
                    ],
                    [
                        'name' => 'Non-Lucrative (NLV)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application/Auth Fees', 'min' => 16, 'max' => 140, 'notes' => '~€80 - €140 + ~€16 (TIE)'],
                            ['category' => 'Requirements', 'item' => 'Notary / Hague Apostille', 'min' => 300, 'max' => 300, 'notes' => 'Translation/Apostille/Med: €300+'],
                            ['category' => 'Requirements', 'item' => 'TIE / NIE Cards', 'min' => 30, 'max' => 30, 'notes' => '~€30'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 400, 'max' => 28800, 'notes' => '400% of IPREM (~€28,800/year)'],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Application/Auth Fees', 'min' => 16, 'max' => 80, 'notes' => '~€80 + ~€16 (TIE)'],
                            ['category' => 'Requirements', 'item' => 'Notary / Hague Apostille', 'min' => 150, 'max' => 150, 'notes' => 'Translation/Apostille: €150+'],
                            ['category' => 'Requirements', 'item' => 'TIE / NIE Cards', 'min' => 30, 'max' => 30, 'notes' => '~€30'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 100, 'max' => 600, 'notes' => '100% IPREM (~€600/month)'],
                        ]
                    ],
                ]
            ],
            'PT' => [
                'name' => 'Portugal',
                'pathways' => [
                    [
                        'name' => 'D3 (Highly Qualified)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa + Res Permit Fee', 'min' => 90, 'max' => 170, 'notes' => '~€90 (Visa) + ~€170 (Permit)'],
                            ['category' => 'Requirements', 'item' => 'Legalizations / Tax Rep', 'min' => 200, 'max' => 200, 'notes' => 'Trans/Notary: €200+'],
                        ]
                    ],
                    [
                        'name' => 'D8 (Digital Nomad)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa + Res Permit Fee', 'min' => 90, 'max' => 170, 'notes' => '~€90 (Visa) + ~€170 (Permit)'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 4, 'max' => 4, 'notes' => 'Travel Ins. (4 months)'],
                            ['category' => 'Requirements', 'item' => 'Legalizations / Tax Rep', 'min' => 100, 'max' => 300, 'notes' => 'Tax Rep. Fees: €100-300+'],
                            ['category' => 'Requirements', 'item' => 'Registration (NIF, NISS)', 'min' => 10, 'max' => 100, 'notes' => 'NIF setup: ~€10-100'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 4, 'max' => 3280, 'notes' => '4x Min Wage (~€3,280/month)'],
                        ]
                    ],
                    [
                        'name' => 'D7 (Passive Income)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa + Res Permit Fee', 'min' => 90, 'max' => 170, 'notes' => '~€90 (Visa) + ~€170 (Permit)'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 4, 'max' => 4, 'notes' => 'Travel Ins. (4 months)'],
                            ['category' => 'Requirements', 'item' => 'Legalizations / Tax Rep', 'min' => 500, 'max' => 500, 'notes' => 'NIF/Bank Setup / Rep: €500+'],
                            ['category' => 'Requirements', 'item' => 'Registration (NIF, NISS)', 'min' => 10, 'max' => 100, 'notes' => 'NIF setup: ~€10-100'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 9840, 'max' => 9840, 'notes' => 'Minimum Wage (~€9,840/year)'],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa + Res Permit Fee', 'min' => 90, 'max' => 90, 'notes' => '~€90'],
                            ['category' => 'Requirements', 'item' => 'Legalizations / Tax Rep', 'min' => 100, 'max' => 100, 'notes' => 'Document Apostille: €100'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 820, 'max' => 820, 'notes' => 'Min Wage (€820/mo) or Guarantee'],
                        ]
                    ],
                ]
            ],
            'IE' => [
                'name' => 'Ireland',
                'pathways' => [
                    [
                        'name' => 'Critical Skills Worker',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa App Fee', 'min' => 1000, 'max' => 1000, 'notes' => '€1,000 (usually by employ)'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 500, 'max' => 500, 'notes' => 'Private Policy (~€500+/yr)'],
                            ['category' => 'Requirements', 'item' => 'Med/Police/Translations', 'min' => 100, 'max' => 100, 'notes' => '€100+'],
                            ['category' => 'Requirements', 'item' => 'IRP Registration', 'min' => 300, 'max' => 300, 'notes' => '€300/year'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 32, 'max' => 64, 'notes' => '>€32K/yr or >€64k/yr Salary'],
                        ]
                    ],
                    [
                        'name' => 'General Employment',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa App Fee', 'min' => 1000, 'max' => 1000, 'notes' => '€1,000 (usually by employ)'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 500, 'max' => 500, 'notes' => 'Private Policy (~€500+/yr)'],
                            ['category' => 'Requirements', 'item' => 'Med/Police/Translations', 'min' => 100, 'max' => 100, 'notes' => '€100+'],
                            ['category' => 'Requirements', 'item' => 'IRP Registration', 'min' => 300, 'max' => 300, 'notes' => '€300/year'],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa App Fee', 'min' => 60, 'max' => 100, 'notes' => '€60 (Single) / €100 (Mult)'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance', 'min' => 150, 'max' => 150, 'notes' => 'Private Policy (~€150+/yr)'],
                            ['category' => 'Requirements', 'item' => 'Med/Police/Translations', 'min' => 200, 'max' => 200, 'notes' => 'English Test: ~€200'],
                            ['category' => 'Requirements', 'item' => 'IRP Registration', 'min' => 300, 'max' => 300, 'notes' => '€300/year'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 10000, 'max' => 10000, 'notes' => '€10,000/year (minimum)'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Tuition/Capital)', 'min' => 10, 'max' => 25, 'notes' => 'Full tuition paid (€10k-€25k)'],
                        ]
                    ],
                    [
                        'name' => 'Stamp 4 (Family)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa App Fee', 'min' => 60, 'max' => 100, 'notes' => '€60 / €100'],
                            ['category' => 'Requirements', 'item' => 'Med/Police/Translations', 'min' => 100, 'max' => 100, 'notes' => '€100+'],
                            ['category' => 'Requirements', 'item' => 'IRP Registration', 'min' => 300, 'max' => 300, 'notes' => '€300/year'],
                        ]
                    ],
                ]
            ],
            'NO' => [
                'name' => 'Norway',
                'pathways' => [
                    [
                        'name' => 'Skilled Worker',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'NOK',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (UDI)', 'min' => 6300, 'max' => 6300, 'notes' => '6,300 NOK'],
                            ['category' => 'Requirements', 'item' => 'Legalizations', 'min' => 500, 'max' => 500, 'notes' => 'Appx: 500 NOK'],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'NOK',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (UDI)', 'min' => 5900, 'max' => 5900, 'notes' => '5,900 NOK'],
                            ['category' => 'Requirements', 'item' => 'Health System', 'min' => 1, 'max' => 1, 'notes' => 'Only required if <1 year'],
                            ['category' => 'Requirements', 'item' => 'Legalizations', 'min' => 500, 'max' => 500, 'notes' => 'Appx: 500 NOK'],
                            ['category' => 'Requirements', 'item' => 'Language / Skills Check', 'min' => 2500, 'max' => 2500, 'notes' => 'English Test: 2,500 NOK'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 137907, 'max' => 137907, 'notes' => '137,907 NOK/year (deposit)'],
                        ]
                    ],
                    [
                        'name' => 'Job Seeker (Grads)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'NOK',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (UDI)', 'min' => 6300, 'max' => 6300, 'notes' => '6,300 NOK'],
                            ['category' => 'Requirements', 'item' => 'Health System', 'min' => 1, 'max' => 1, 'notes' => 'Private Insurance (1 yr)'],
                            ['category' => 'Requirements', 'item' => 'Legalizations', 'min' => 500, 'max' => 500, 'notes' => 'Appx: 500 NOK'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 6, 'max' => 258408, 'notes' => '258,408 NOK (for 6 months)'],
                        ]
                    ],
                    [
                        'name' => 'Family Immigration',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'NOK',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'App Fee (UDI)', 'min' => 11900, 'max' => 11900, 'notes' => '11,900 NOK'],
                            ['category' => 'Requirements', 'item' => 'Legalizations', 'min' => 1000, 'max' => 1000, 'notes' => 'Appx: 1,000 NOK'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 320000, 'max' => 320000, 'notes' => 'Sponsor income req (~320,000 NOK/yr)'],
                        ]
                    ],
                ]
            ],
            'IT' => [
                'name' => 'Italy',
                'pathways' => [
                    [
                        'name' => 'EU Blue Card (Skilled)',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa + Permit (Permesso) Fee', 'min' => 50, 'max' => 116, 'notes' => '~€116 (€50 visa + €70+ Permit)'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance / System', 'min' => 200, 'max' => 700, 'notes' => 'SSN Registration (~€200-700 vol)'],
                            ['category' => 'Requirements', 'item' => 'Legalization / Apostille / Null', 'min' => 400, 'max' => 400, 'notes' => '*Nulla Osta*/Degree Trans: €400+'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 27000, 'max' => 27000, 'notes' => 'Min Salary (~€27,000+/year)'],
                        ]
                    ],
                    [
                        'name' => 'Digital Nomad Visa',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa + Permit (Permesso) Fee', 'min' => 116, 'max' => 116, 'notes' => '~€116'],
                            ['category' => 'Requirements', 'item' => 'Legalization / Apostille / Null', 'min' => 300, 'max' => 300, 'notes' => 'Apostilles / Trans: €300+'],
                            ['category' => 'Requirements', 'item' => 'Registration (Codice Fiscale)', 'min' => 200, 'max' => 200, 'notes' => 'CF / Partita IVA setup: €200'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 28000, 'max' => 28000, 'notes' => '~€28,000/year'],
                        ]
                    ],
                    [
                        'name' => 'Elective Residence',
                        'type' => 'Work',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa + Permit (Permesso) Fee', 'min' => 116, 'max' => 116, 'notes' => '~€116'],
                            ['category' => 'Requirements', 'item' => 'Legalization / Apostille / Null', 'min' => 500, 'max' => 500, 'notes' => 'Notary / House Contract: €500+'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 31000, 'max' => 31000, 'notes' => '€31,000/year passive income'],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'type' => 'Study',
                        'min' => 0,
                        'max' => 0,
                        'currency' => 'EUR',
                        'items' => [
                            ['category' => 'Fees', 'item' => 'Visa + Permit (Permesso) Fee', 'min' => 50, 'max' => 70, 'notes' => '~€50 + ~€70 (Permesso)'],
                            ['category' => 'Requirements', 'item' => 'Health Insurance / System', 'min' => 150, 'max' => 150, 'notes' => 'SSN (~€150/year) OR Private'],
                            ['category' => 'Requirements', 'item' => 'Legalization / Apostille / Null', 'min' => 200, 'max' => 200, 'notes' => 'Degree Apostille/DoV: €200+'],
                            ['category' => 'Living Costs', 'item' => 'Proof of Funds (Living)', 'min' => 460, 'max' => 460, 'notes' => 'Minimum €460/month'],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($data as $code => $countryData) {
            $country = Country::where('code', $code)->first();
            if (!$country) continue;

            $currentVisaNames = array_column($countryData['pathways'], 'name');

            // Deduplicate: remove visa types that are not in the new list and have no pathways
            VisaType::where('country_id', $country->id)
                ->whereNotIn('name', $currentVisaNames)
                ->whereDoesntHave('pathways')
                ->delete();

            foreach ($countryData['pathways'] as $p) {
                $visaType = VisaType::updateOrCreate(
                    ['country_id' => $country->id, 'name' => $p['name']],
                    [
                        'pathway_type' => $p['type'] ?? 'Work',
                        'description' => "Official immigration pathway for {$p['name']} in {$countryData['name']}.",
                        'is_active' => true,
                    ]
                );

                if (isset($p['items'])) {
                    foreach ($p['items'] as $item) {
                        CostTemplate::updateOrCreate(
                            ['visa_type_id' => $visaType->id, 'item' => $item['item']],
                            [
                                'category' => $item['category'],
                                'min_cost' => $item['min'],
                                'max_cost' => $item['max'],
                                'currency' => $p['currency'] ?? 'USD',
                                'notes' => $item['notes'] ?? null,
                            ]
                        );
                    }
                }
            }
        }
    }
}
