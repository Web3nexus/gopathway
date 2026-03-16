<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CostItem;
use App\Models\Country;
use App\Models\VisaType;

class ComprehensiveRealisticCostSeeder extends Seeder
{
    public function run(): void
    {
        // Cleanup handled by CleanupSeeder

        $data = [
            'GB' => [
                'name' => 'United Kingdom',
                'pathways' => [
                    [
                        'name' => 'Skilled Worker',
                        'items' => [
                            ['name' => "Application Fee - Skilled Worker", 'amount' => 719, 'currency' => 'GBP', 'description' => '£719 - £1,500 (varies by duration)', 'is_mandatory' => true],
                            ['name' => "Health Surcharge (IHS) - Skilled Worker", 'amount' => 1035, 'currency' => 'GBP', 'description' => '£1,035/year', 'is_mandatory' => true],
                            ['name' => "Biometrics & Admin - Skilled Worker", 'amount' => 19, 'currency' => 'GBP', 'description' => '~£19.20 (UK) or ~£55 (Abroad)', 'is_mandatory' => true],
                            ['name' => "Language / Med Tests - Skilled Worker", 'amount' => 200, 'currency' => 'GBP', 'description' => 'English: ~£200; TB Test: ~£100', 'is_mandatory' => true],
                            ['name' => "Degree/Skills Eval. - Skilled Worker", 'amount' => 140, 'currency' => 'GBP', 'description' => 'Ecctis (if needed): ~£140', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living/Setup) - Skilled Worker", 'amount' => 1270, 'currency' => 'GBP', 'description' => '£1,270 for 28 days', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'items' => [
                            ['name' => "Application Fee - Student", 'amount' => 490, 'currency' => 'GBP', 'description' => '£490', 'is_mandatory' => true],
                            ['name' => "Health Surcharge (IHS) - Student", 'amount' => 776, 'currency' => 'GBP', 'description' => '£776/year', 'is_mandatory' => true],
                            ['name' => "Biometrics & Admin - Student", 'amount' => 19, 'currency' => 'GBP', 'description' => '~£19.20 (UK) or ~£55 (Abroad)', 'is_mandatory' => true],
                            ['name' => "Language / Med Tests - Student", 'amount' => 200, 'currency' => 'GBP', 'description' => 'English: ~£200; TB Test: ~£100', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living/Setup) - Student", 'amount' => 12006, 'currency' => 'GBP', 'description' => '£1,334/mo (London) or £1,023/mo (Outside) up to 9 mos', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Tuition/Capital) - Student", 'amount' => 15000, 'currency' => 'GBP', 'description' => 'Full 1st year tuition', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Global Talent',
                        'items' => [
                            ['name' => "Application Fee - Global Talent", 'amount' => 1238, 'currency' => 'GBP', 'description' => '£715 (£192 Approval + £523 Visa)', 'is_mandatory' => true],
                            ['name' => "Health Surcharge (IHS) - Global Talent", 'amount' => 1035, 'currency' => 'GBP', 'description' => '£1,035/year', 'is_mandatory' => true],
                            ['name' => "Biometrics & Admin - Global Talent", 'amount' => 19, 'currency' => 'GBP', 'description' => '~£19.20 - £55', 'is_mandatory' => true],
                            ['name' => "Degree/Skills Eval. - Global Talent", 'amount' => 524, 'currency' => 'GBP', 'description' => 'Endorsement Body fee: £524', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Family/Spouse',
                        'items' => [
                            ['name' => "Application Fee - Family/Spouse", 'amount' => 1846, 'currency' => 'GBP', 'description' => '£1,846', 'is_mandatory' => true],
                            ['name' => "Health Surcharge (IHS) - Family/Spouse", 'amount' => 1035, 'currency' => 'GBP', 'description' => '£1,035/year', 'is_mandatory' => true],
                            ['name' => "Biometrics & Admin - Family/Spouse", 'amount' => 19, 'currency' => 'GBP', 'description' => '~£19.20 - £55', 'is_mandatory' => true],
                            ['name' => "Language / Med Tests - Family/Spouse", 'amount' => 150, 'currency' => 'GBP', 'description' => 'English (£150-£200); TB Test (~£100)', 'is_mandatory' => true],
                            ['name' => "Degree/Skills Eval. - Family/Spouse", 'amount' => 50, 'currency' => 'GBP', 'description' => 'Translations: ~£50-100', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living/Setup) - Family/Spouse", 'amount' => 29000, 'currency' => 'GBP', 'description' => 'Minimum Income Req: £29,000/yr', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'CA' => [
                'name' => 'Canada',
                'pathways' => [
                    [
                        'name' => 'Express Entry (FSW/CEC/FST)',
                        'items' => [
                            ['name' => "App Fee + Right of PR Fee - Express Entry (FSW/CEC/FST)", 'amount' => 1525, 'currency' => 'CAD', 'description' => '$950 App + $575 RPRF (CAD)', 'is_mandatory' => true],
                            ['name' => "Biometrics - Express Entry (FSW/CEC/FST)", 'amount' => 85, 'currency' => 'CAD', 'description' => '$85 CAD', 'is_mandatory' => true],
                            ['name' => "Medical Exam - Express Entry (FSW/CEC/FST)", 'amount' => 200, 'currency' => 'CAD', 'description' => '~$200 CAD', 'is_mandatory' => true],
                            ['name' => "Language Test - Express Entry (FSW/CEC/FST)", 'amount' => 300, 'currency' => 'CAD', 'description' => 'IELTS/CELPIP: ~$300 CAD', 'is_mandatory' => true],
                            ['name' => "Educ. Credential Assess (ECA) - Express Entry (FSW/CEC/FST)", 'amount' => 250, 'currency' => 'CAD', 'description' => 'WES/ICAS: ~$250 CAD', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living/Setup) - Express Entry (FSW/CEC/FST)", 'amount' => 13757, 'currency' => 'CAD', 'description' => '~$13,757 CAD for 1 person', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Provincial Nominee (PNP)',
                        'items' => [
                            ['name' => "App Fee + Right of PR Fee - Provincial Nominee (PNP)", 'amount' => 1775, 'currency' => 'CAD', 'description' => '$950 App + $575 RPRF + Prov Fee ($250-$1,500)', 'is_mandatory' => true],
                            ['name' => "Biometrics - Provincial Nominee (PNP)", 'amount' => 85, 'currency' => 'CAD', 'description' => '$85 CAD', 'is_mandatory' => true],
                            ['name' => "Medical Exam - Provincial Nominee (PNP)", 'amount' => 200, 'currency' => 'CAD', 'description' => '~$200 CAD', 'is_mandatory' => true],
                            ['name' => "Language Test - Provincial Nominee (PNP)", 'amount' => 300, 'currency' => 'CAD', 'description' => 'IELTS/CELPIP: ~$300 CAD', 'is_mandatory' => true],
                            ['name' => "Educ. Credential Assess (ECA) - Provincial Nominee (PNP)", 'amount' => 250, 'currency' => 'CAD', 'description' => 'WES/ICAS: ~$250 CAD', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Study Permit',
                        'items' => [
                            ['name' => "App Fee + Right of PR Fee - Study Permit", 'amount' => 150, 'currency' => 'CAD', 'description' => '$150 CAD', 'is_mandatory' => true],
                            ['name' => "Biometrics - Study Permit", 'amount' => 85, 'currency' => 'CAD', 'description' => '$85 CAD', 'is_mandatory' => true],
                            ['name' => "Medical Exam - Study Permit", 'amount' => 200, 'currency' => 'CAD', 'description' => '~$200 CAD', 'is_mandatory' => true],
                            ['name' => "Language Test - Study Permit", 'amount' => 300, 'currency' => 'CAD', 'description' => 'IELTS/PTE (varies by school): ~$300 CAD', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living/Setup) - Study Permit", 'amount' => 20635, 'currency' => 'CAD', 'description' => '~$20,635 CAD/year (Base living exp)', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Tuition/Capital) - Study Permit", 'amount' => 15000, 'currency' => 'CAD', 'description' => 'Full 1st year tuition', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Family Sponsorship',
                        'items' => [
                            ['name' => "App Fee + Right of PR Fee - Family Sponsorship", 'amount' => 1210, 'currency' => 'CAD', 'description' => '$1,210 CAD (incl. PR Fee)', 'is_mandatory' => true],
                            ['name' => "Biometrics - Family Sponsorship", 'amount' => 85, 'currency' => 'CAD', 'description' => '$85 CAD', 'is_mandatory' => true],
                            ['name' => "Medical Exam - Family Sponsorship", 'amount' => 200, 'currency' => 'CAD', 'description' => '~$200 CAD', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'AU' => [
                'name' => 'Australia',
                'pathways' => [
                    [
                        'name' => 'Skilled Independent (189/190)',
                        'items' => [
                            ['name' => "Application Fee - Skilled Independent (189/190)", 'amount' => 4640, 'currency' => 'AUD', 'description' => '$4,640 AUD', 'is_mandatory' => true],
                            ['name' => "Biometrics & Admin - Skilled Independent (189/190)", 'amount' => 100, 'currency' => 'AUD', 'description' => '~$100 AUD (Biometrics/Police)', 'is_mandatory' => true],
                            ['name' => "Language / Med Tests - Skilled Independent (189/190)", 'amount' => 400, 'currency' => 'AUD', 'description' => 'IELTS/PTE: ~$400 AUD; Med: ~$300 AUD', 'is_mandatory' => true],
                            ['name' => "Skills Assessment - Skilled Independent (189/190)", 'amount' => 500, 'currency' => 'AUD', 'description' => '$500 - $1,200 AUD (varies by authority)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Employer Sponsored (TSS 482)',
                        'items' => [
                            ['name' => "Application Fee - Employer Sponsored (TSS 482)", 'amount' => 1455, 'currency' => 'AUD', 'description' => '$1,455 - $3,035 AUD', 'is_mandatory' => true],
                            ['name' => "Surcharges / Insurance - Employer Sponsored (TSS 482)", 'amount' => 1000, 'currency' => 'AUD', 'description' => 'OVHC: ~$1,000/year', 'is_mandatory' => true],
                            ['name' => "Biometrics & Admin - Employer Sponsored (TSS 482)", 'amount' => 100, 'currency' => 'AUD', 'description' => '~$100 AUD', 'is_mandatory' => true],
                            ['name' => "Language / Med Tests - Employer Sponsored (TSS 482)", 'amount' => 400, 'currency' => 'AUD', 'description' => 'IELTS/PTE: ~$400 AUD', 'is_mandatory' => true],
                            ['name' => "Skills Assessment - Employer Sponsored (TSS 482)", 'amount' => 500, 'currency' => 'AUD', 'description' => '$500 - $1,200 AUD (if requested)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student (subclass 500)',
                        'items' => [
                            ['name' => "Application Fee - Student (subclass 500)", 'amount' => 710, 'currency' => 'AUD', 'description' => '$710 AUD', 'is_mandatory' => true],
                            ['name' => "Surcharges / Insurance - Student (subclass 500)", 'amount' => 500, 'currency' => 'AUD', 'description' => 'OSHC: ~$500 - $800/year', 'is_mandatory' => true],
                            ['name' => "Biometrics & Admin - Student (subclass 500)", 'amount' => 100, 'currency' => 'AUD', 'description' => '~$100 AUD', 'is_mandatory' => true],
                            ['name' => "Language / Med Tests - Student (subclass 500)", 'amount' => 400, 'currency' => 'AUD', 'description' => 'IELTS/PTE/TOEFL: ~$400 AUD; Med: ~$300 AUD', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living/Setup) - Student (subclass 500)", 'amount' => 24505, 'currency' => 'AUD', 'description' => 'Minimum $24,505 AUD/year', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Tuition/Capital) - Student (subclass 500)", 'amount' => 15000, 'currency' => 'AUD', 'description' => 'Full 1st year tuition', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Partner Visa (820/801)',
                        'items' => [
                            ['name' => "Application Fee - Partner Visa (820/801)", 'amount' => 8850, 'currency' => 'AUD', 'description' => '$8,850 AUD', 'is_mandatory' => true],
                            ['name' => "Biometrics & Admin - Partner Visa (820/801)", 'amount' => 100, 'currency' => 'AUD', 'description' => '~$100 AUD (Police, Admin)', 'is_mandatory' => true],
                            ['name' => "Language / Med Tests - Partner Visa (820/801)", 'amount' => 300, 'currency' => 'AUD', 'description' => 'Med: ~$300 AUD', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'NZ' => [
                'name' => 'New Zealand',
                'pathways' => [
                    [
                        'name' => 'Skilled Migrant Category',
                        'items' => [
                            ['name' => "Application Fee - Skilled Migrant Category", 'amount' => 4290, 'currency' => 'NZD', 'description' => '$4,290 NZD', 'is_mandatory' => true],
                            ['name' => "Biometrics & Police Checks - Skilled Migrant Category", 'amount' => 150, 'currency' => 'NZD', 'description' => '~$150 NZD', 'is_mandatory' => true],
                            ['name' => "Language Test & Medical Exam - Skilled Migrant Category", 'amount' => 400, 'currency' => 'NZD', 'description' => 'IELTS/PTE: ~$400 NZD; Medical: ~$300 NZD', 'is_mandatory' => true],
                            ['name' => "Skills Assessment (NZQA) - Skilled Migrant Category", 'amount' => 746, 'currency' => 'NZD', 'description' => 'NZQA Assessment: ~$746 NZD', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Accredited Employer Work',
                        'items' => [
                            ['name' => "Application Fee - Accredited Employer Work", 'amount' => 750, 'currency' => 'NZD', 'description' => '$750 NZD', 'is_mandatory' => true],
                            ['name' => "Biometrics & Police Checks - Accredited Employer Work", 'amount' => 150, 'currency' => 'NZD', 'description' => '~$150 NZD', 'is_mandatory' => true],
                            ['name' => "Language Test & Medical Exam - Accredited Employer Work", 'amount' => 300, 'currency' => 'NZD', 'description' => 'Medical: ~$300 NZD', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'items' => [
                            ['name' => "Application Fee - Student Visa", 'amount' => 375, 'currency' => 'NZD', 'description' => '$375 NZD', 'is_mandatory' => true],
                            ['name' => "Biometrics & Police Checks - Student Visa", 'amount' => 150, 'currency' => 'NZD', 'description' => '~$150 NZD', 'is_mandatory' => true],
                            ['name' => "Language Test & Medical Exam - Student Visa", 'amount' => 400, 'currency' => 'NZD', 'description' => 'IELTS/PTE (School req): ~$400 NZD; Med: ~$300', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living/Setup) - Student Visa", 'amount' => 20000, 'currency' => 'NZD', 'description' => 'Minimum $20,000 NZD/year', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Partner of a NZer',
                        'items' => [
                            ['name' => "Application Fee - Partner of a NZer", 'amount' => 2750, 'currency' => 'NZD', 'description' => '$2,750 NZD', 'is_mandatory' => true],
                            ['name' => "Biometrics & Police Checks - Partner of a NZer", 'amount' => 150, 'currency' => 'NZD', 'description' => '~$150 NZD', 'is_mandatory' => true],
                            ['name' => "Language Test & Medical Exam - Partner of a NZer", 'amount' => 300, 'currency' => 'NZD', 'description' => 'Medical: ~$300 NZD', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'DE' => [
                'name' => 'Germany',
                'pathways' => [
                    [
                        'name' => 'EU Blue Card (Skilled Worker)',
                        'items' => [
                            ['name' => "Visa Application Fee - EU Blue Card (Skilled Worker)", 'amount' => 75, 'currency' => 'EUR', 'description' => '€75 - €100 (varies locally)', 'is_mandatory' => true],
                            ['name' => "Admin / Biometrics / Legalization - EU Blue Card (Skilled Worker)", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Reg./Translation: ~€100-€300', 'is_mandatory' => true],
                            ['name' => "Degree Recognition (ZAB) - EU Blue Card (Skilled Worker)", 'amount' => 200, 'currency' => 'EUR', 'description' => 'ZAB Evaluation: €200', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - EU Blue Card (Skilled Worker)", 'amount' => 45000, 'currency' => 'EUR', 'description' => 'Contract covers minimum threshold (~€45k+)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Job Seeker (Opportunity Card)',
                        'items' => [
                            ['name' => "Visa Application Fee - Job Seeker (Opportunity Card)", 'amount' => 75, 'currency' => 'EUR', 'description' => '€75', 'is_mandatory' => true],
                            ['name' => "Health Insurance - Job Seeker (Opportunity Card)", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Required (Private coverage, min €100/mo)', 'is_mandatory' => true],
                            ['name' => "Admin / Biometrics / Legalization - Job Seeker (Opportunity Card)", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Reg./Translation: ~€100', 'is_mandatory' => true],
                            ['name' => "Language Test - Job Seeker (Opportunity Card)", 'amount' => 150, 'currency' => 'EUR', 'description' => 'A1 DE / B2 EN certificate: ~€150', 'is_mandatory' => true],
                            ['name' => "Degree Recognition (ZAB) - Job Seeker (Opportunity Card)", 'amount' => 200, 'currency' => 'EUR', 'description' => 'ZAB Evaluation: €200', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Job Seeker (Opportunity Card)", 'amount' => 12324, 'currency' => 'EUR', 'description' => '€1,027/month (Blocked Account)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'items' => [
                            ['name' => "Visa Application Fee - Student Visa", 'amount' => 75, 'currency' => 'EUR', 'description' => '€75', 'is_mandatory' => true],
                            ['name' => "Health Insurance - Student Visa", 'amount' => 120, 'currency' => 'EUR', 'description' => 'Required (~€120/month Statutory)', 'is_mandatory' => true],
                            ['name' => "Admin / Biometrics / Legalization - Student Visa", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Reg./Translation/Notary: ~€100', 'is_mandatory' => true],
                            ['name' => "Language Test - Student Visa", 'amount' => 200, 'currency' => 'EUR', 'description' => 'DaF / IELTS: ~€200', 'is_mandatory' => true],
                            ['name' => "Degree Recognition (ZAB) - Student Visa", 'amount' => 75, 'currency' => 'EUR', 'description' => 'Uni-Assist: ~€75/application', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student Visa", 'amount' => 11208, 'currency' => 'EUR', 'description' => '€11,208/year (Blocked Account)', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Tuition/Capital) - Student Visa", 'amount' => 15000, 'currency' => 'EUR', 'description' => 'Uni Enrollment (~€150-€350/sem)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Freelance/Self-Employed',
                        'items' => [
                            ['name' => "Visa Application Fee - Freelance/Self-Employed", 'amount' => 100, 'currency' => 'EUR', 'description' => '€100', 'is_mandatory' => true],
                            ['name' => "Admin / Biometrics / Legalization - Freelance/Self-Employed", 'amount' => 500, 'currency' => 'EUR', 'description' => 'Business setup/Notary fees: ~€500+', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'NL' => [
                'name' => 'Netherlands',
                'pathways' => [
                    [
                        'name' => 'Highly Skilled Migrant',
                        'items' => [
                            ['name' => "App Fee (IND/MVV) - Highly Skilled Migrant", 'amount' => 380, 'currency' => 'EUR', 'description' => '€380', 'is_mandatory' => true],
                            ['name' => "Health Insurance - Highly Skilled Migrant", 'amount' => 150, 'currency' => 'EUR', 'description' => 'Required (~€150/month)', 'is_mandatory' => true],
                            ['name' => "Translation / Legalizations - Highly Skilled Migrant", 'amount' => 150, 'currency' => 'EUR', 'description' => '~€150 - €300 for degrees/certs', 'is_mandatory' => true],
                            ['name' => "Integration Exam Req. - Highly Skilled Migrant", 'amount' => 150, 'currency' => 'EUR', 'description' => 'Inburgering (for PR later): €150+', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student/Orientation Year',
                        'items' => [
                            ['name' => "App Fee (IND/MVV) - Student/Orientation Year", 'amount' => 228, 'currency' => 'EUR', 'description' => '€228', 'is_mandatory' => true],
                            ['name' => "Health Insurance - Student/Orientation Year", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Required (~€100-150/month)', 'is_mandatory' => true],
                            ['name' => "Translation / Legalizations - Student/Orientation Year", 'amount' => 150, 'currency' => 'EUR', 'description' => '~€150', 'is_mandatory' => true],
                            ['name' => "Integration Exam Req. - Student/Orientation Year", 'amount' => 250, 'currency' => 'EUR', 'description' => 'English test (TOEFL/IELTS): ~€250', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student/Orientation Year", 'amount' => 14400, 'currency' => 'EUR', 'description' => '~€1,200/month', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Tuition/Capital) - Student/Orientation Year", 'amount' => 15000, 'currency' => 'EUR', 'description' => 'Full tuition paid upfront (1st yr)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Self-Employed/DAFT(US/JP)',
                        'items' => [
                            ['name' => "App Fee (IND/MVV) - Self-Employed/DAFT(US/JP)", 'amount' => 1529, 'currency' => 'EUR', 'description' => '€1,529', 'is_mandatory' => true],
                            ['name' => "Health Insurance - Self-Employed/DAFT(US/JP)", 'amount' => 150, 'currency' => 'EUR', 'description' => 'Required (~€150/month)', 'is_mandatory' => true],
                            ['name' => "Translation / Legalizations - Self-Employed/DAFT(US/JP)", 'amount' => 75, 'currency' => 'EUR', 'description' => 'KVK (Chamber of Commerce): ~€75', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Self-Employed/DAFT(US/JP)", 'amount' => 18000, 'currency' => 'EUR', 'description' => 'Min standard set by IND (~€1,500/mo)', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Tuition/Capital) - Self-Employed/DAFT(US/JP)", 'amount' => 15000, 'currency' => 'EUR', 'description' => '€4,500 Business Capital (DAFT)', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'SE' => [
                'name' => 'Sweden',
                'pathways' => [
                    [
                        'name' => 'Work Permit',
                        'items' => [
                            ['name' => "Application Fee - Work Permit", 'amount' => 2200, 'currency' => 'SEK', 'description' => '2,200 SEK', 'is_mandatory' => true],
                            ['name' => "Identity / Translations - Work Permit", 'amount' => 1000, 'currency' => 'SEK', 'description' => 'Appx: 1,000 SEK', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Work Permit", 'amount' => 13000, 'currency' => 'SEK', 'description' => '13,000 SEK/month min salary req', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Job Seeker Visa',
                        'items' => [
                            ['name' => "Application Fee - Job Seeker Visa", 'amount' => 2200, 'currency' => 'SEK', 'description' => '2,200 SEK', 'is_mandatory' => true],
                            ['name' => "Health Insurance / Healthcare - Job Seeker Visa", 'amount' => 500, 'currency' => 'SEK', 'description' => 'Required (Private, ~500 SEK/mo)', 'is_mandatory' => true],
                            ['name' => "Identity / Translations - Job Seeker Visa", 'amount' => 1000, 'currency' => 'SEK', 'description' => 'Appx: 1,000 SEK', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Job Seeker Visa", 'amount' => 13000, 'currency' => 'SEK', 'description' => '13,000 SEK/month (up to 9 mo)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'items' => [
                            ['name' => "Application Fee - Student Visa", 'amount' => 1500, 'currency' => 'SEK', 'description' => '1,500 SEK', 'is_mandatory' => true],
                            ['name' => "Health Insurance / Healthcare - Student Visa", 'amount' => 1, 'currency' => 'SEK', 'description' => 'Required if <1 yr program', 'is_mandatory' => true],
                            ['name' => "Identity / Translations - Student Visa", 'amount' => 1000, 'currency' => 'SEK', 'description' => 'Appx: 1,000 SEK', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student Visa", 'amount' => 10314, 'currency' => 'SEK', 'description' => '10,314 SEK/month', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Family/Cohabiting',
                        'items' => [
                            ['name' => "Application Fee - Family/Cohabiting", 'amount' => 2000, 'currency' => 'SEK', 'description' => '2,000 SEK', 'is_mandatory' => true],
                            ['name' => "Identity / Translations - Family/Cohabiting", 'amount' => 1000, 'currency' => 'SEK', 'description' => 'Appx: 1,000 SEK', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'FI' => [
                'name' => 'Finland',
                'pathways' => [
                    [
                        'name' => 'Specialist/Skilled Worker',
                        'items' => [
                            ['name' => "App Fee (Online/Paper) - Specialist/Skilled Worker", 'amount' => 380, 'currency' => 'EUR', 'description' => '€380 / €480', 'is_mandatory' => true],
                            ['name' => "Legalizations / Biometrics - Specialist/Skilled Worker", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Document Legalization: €100+', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Specialist/Skilled Worker", 'amount' => 36000, 'currency' => 'EUR', 'description' => 'Min salary requirement (>€3,000/mo)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'items' => [
                            ['name' => "App Fee (Online/Paper) - Student", 'amount' => 350, 'currency' => 'EUR', 'description' => '€350 / €450', 'is_mandatory' => true],
                            ['name' => "Legalizations / Biometrics - Student", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Medical/Translations: €100+', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student", 'amount' => 9600, 'currency' => 'EUR', 'description' => '€800/month (€9,600/year)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Startup Entrepreneur',
                        'items' => [
                            ['name' => "App Fee (Online/Paper) - Startup Entrepreneur", 'amount' => 350, 'currency' => 'EUR', 'description' => '€350 / €480', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Family Ties',
                        'items' => [
                            ['name' => "App Fee (Online/Paper) - Family Ties", 'amount' => 470, 'currency' => 'EUR', 'description' => '€470 / €520', 'is_mandatory' => true],
                            ['name' => "Legalizations / Biometrics - Family Ties", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Translations: €100+', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Family Ties", 'amount' => 12000, 'currency' => 'EUR', 'description' => 'Sponsor income (e.g., €1,000/mo per child)', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'AT' => [
                'name' => 'Austria',
                'pathways' => [
                    [
                        'name' => 'Red-White-Red (Skilled)',
                        'items' => [
                            ['name' => "App Fee / Residence Card - Red-White-Red (Skilled)", 'amount' => 160, 'currency' => 'EUR', 'description' => '€160', 'is_mandatory' => true],
                            ['name' => "Legalizations/Translation - Red-White-Red (Skilled)", 'amount' => 200, 'currency' => 'EUR', 'description' => 'Appx: €200', 'is_mandatory' => true],
                            ['name' => "Language Exam / Deg. Eval - Red-White-Red (Skilled)", 'amount' => 1, 'currency' => 'EUR', 'description' => 'A1/A2 German (if claimed): ~€150', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Job Seeker',
                        'items' => [
                            ['name' => "App Fee / Residence Card - Job Seeker", 'amount' => 150, 'currency' => 'EUR', 'description' => '€150', 'is_mandatory' => true],
                            ['name' => "Legalizations/Translation - Job Seeker", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Appx: €100', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Job Seeker", 'amount' => 14604, 'currency' => 'EUR', 'description' => '€1,217.96/month (Single standard)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'items' => [
                            ['name' => "App Fee / Residence Card - Student Visa", 'amount' => 160, 'currency' => 'EUR', 'description' => '€160', 'is_mandatory' => true],
                            ['name' => "Health Insurance - Student Visa", 'amount' => 67, 'currency' => 'EUR', 'description' => 'Student insurance (€67/mo)', 'is_mandatory' => true],
                            ['name' => "Legalizations/Translation - Student Visa", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Appx: €100', 'is_mandatory' => true],
                            ['name' => "Language Exam / Deg. Eval - Student Visa", 'amount' => 150, 'currency' => 'EUR', 'description' => 'English/German Cert: ~€150', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student Visa", 'amount' => 8064, 'currency' => 'EUR', 'description' => '€1,217.96/month (If 24+) or ~€672 (If <24)', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Tuition) - Student Visa", 'amount' => 726, 'currency' => 'EUR', 'description' => 'Up to ~€726/semester', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'FR' => [
                'name' => 'France',
                'pathways' => [
                    [
                        'name' => 'Talent Passport (Skilled)',
                        'items' => [
                            ['name' => "App / Residence Fee (Tax) - Talent Passport (Skilled)", 'amount' => 225, 'currency' => 'EUR', 'description' => '€225', 'is_mandatory' => true],
                            ['name' => "Legalizations/Translations - Talent Passport (Skilled)", 'amount' => 250, 'currency' => 'EUR', 'description' => 'Medical (OFII): €250; Trans: €150', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Talent Passport (Skilled)", 'amount' => 42406, 'currency' => 'EUR', 'description' => 'Minimum Gross Salary (€42,406+)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'items' => [
                            ['name' => "App / Residence Fee (Tax) - Student", 'amount' => 125, 'currency' => 'EUR', 'description' => '€50 - €99 + €75 VLS-TS', 'is_mandatory' => true],
                            ['name' => "Health Insurance / CVEC - Student", 'amount' => 103, 'currency' => 'EUR', 'description' => 'CVEC (€103/year)', 'is_mandatory' => true],
                            ['name' => "Legalizations/Translations - Student", 'amount' => 250, 'currency' => 'EUR', 'description' => 'Campus France Registration (€250)', 'is_mandatory' => true],
                            ['name' => "Language Req. - Student", 'amount' => 150, 'currency' => 'EUR', 'description' => 'TCF/DELF: €150', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student", 'amount' => 7380, 'currency' => 'EUR', 'description' => '€615/month', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Profession Libérale',
                        'items' => [
                            ['name' => "App / Residence Fee (Tax) - Profession Libérale", 'amount' => 225, 'currency' => 'EUR', 'description' => '€225', 'is_mandatory' => true],
                            ['name' => "Legalizations/Translations - Profession Libérale", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Chamber of Commerce Reg: €100', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Profession Libérale", 'amount' => 21192, 'currency' => 'EUR', 'description' => 'Min wage (SMIC, ~€1,766 gross/mo)', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'ES' => [
                'name' => 'Spain',
                'pathways' => [
                    [
                        'name' => 'Highly Qualified (PAC)',
                        'items' => [
                            ['name' => "Application/Auth Fees - Highly Qualified (PAC)", 'amount' => 89, 'currency' => 'EUR', 'description' => '~€73 (Auth) + ~€16 (TIE)', 'is_mandatory' => true],
                            ['name' => "Notary / Hague Apostille - Highly Qualified (PAC)", 'amount' => 300, 'currency' => 'EUR', 'description' => 'Translation/Apostille: €300+', 'is_mandatory' => true],
                            ['name' => "TIE / NIE Cards - Highly Qualified (PAC)", 'amount' => 30, 'currency' => 'EUR', 'description' => '~€30', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Digital Nomad',
                        'items' => [
                            ['name' => "Application/Auth Fees - Digital Nomad", 'amount' => 89, 'currency' => 'EUR', 'description' => '~€73 (Auth) + ~€16 (TIE)', 'is_mandatory' => true],
                            ['name' => "Notary / Hague Apostille - Digital Nomad", 'amount' => 300, 'currency' => 'EUR', 'description' => 'Translation/Apostille: €300+', 'is_mandatory' => true],
                            ['name' => "TIE / NIE Cards - Digital Nomad", 'amount' => 30, 'currency' => 'EUR', 'description' => '~€30', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Digital Nomad", 'amount' => 30240, 'currency' => 'EUR', 'description' => '200% of SMI (~€2,520/month)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Non-Lucrative (NLV)',
                        'items' => [
                            ['name' => "Application/Auth Fees - Non-Lucrative (NLV)", 'amount' => 96, 'currency' => 'EUR', 'description' => '~€80 - €140 + ~€16 (TIE)', 'is_mandatory' => true],
                            ['name' => "Notary / Hague Apostille - Non-Lucrative (NLV)", 'amount' => 300, 'currency' => 'EUR', 'description' => 'Translation/Apostille/Med: €300+', 'is_mandatory' => true],
                            ['name' => "TIE / NIE Cards - Non-Lucrative (NLV)", 'amount' => 30, 'currency' => 'EUR', 'description' => '~€30', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Non-Lucrative (NLV)", 'amount' => 28800, 'currency' => 'EUR', 'description' => '400% of IPREM (~€28,800/year)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'items' => [
                            ['name' => "Application/Auth Fees - Student", 'amount' => 96, 'currency' => 'EUR', 'description' => '~€80 + ~€16 (TIE)', 'is_mandatory' => true],
                            ['name' => "Notary / Hague Apostille - Student", 'amount' => 150, 'currency' => 'EUR', 'description' => 'Translation/Apostille: €150+', 'is_mandatory' => true],
                            ['name' => "TIE / NIE Cards - Student", 'amount' => 30, 'currency' => 'EUR', 'description' => '~€30', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student", 'amount' => 7200, 'currency' => 'EUR', 'description' => '100% IPREM (~€600/month)', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'PT' => [
                'name' => 'Portugal',
                'pathways' => [
                    [
                        'name' => 'D3 (Highly Qualified)',
                        'items' => [
                            ['name' => "Visa + Res Permit Fee - D3 (Highly Qualified)", 'amount' => 260, 'currency' => 'EUR', 'description' => '~€90 (Visa) + ~€170 (Permit)', 'is_mandatory' => true],
                            ['name' => "Legalizations / Tax Rep - D3 (Highly Qualified)", 'amount' => 200, 'currency' => 'EUR', 'description' => 'Trans/Notary: €200+', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'D8 (Digital Nomad)',
                        'items' => [
                            ['name' => "Visa + Res Permit Fee - D8 (Digital Nomad)", 'amount' => 260, 'currency' => 'EUR', 'description' => '~€90 (Visa) + ~€170 (Permit)', 'is_mandatory' => true],
                            ['name' => "Health Insurance - D8 (Digital Nomad)", 'amount' => 4, 'currency' => 'EUR', 'description' => 'Travel Ins. (4 months)', 'is_mandatory' => true],
                            ['name' => "Legalizations / Tax Rep - D8 (Digital Nomad)", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Tax Rep. Fees: €100-300+', 'is_mandatory' => true],
                            ['name' => "Registration (NIF, NISS) - D8 (Digital Nomad)", 'amount' => 10, 'currency' => 'EUR', 'description' => 'NIF setup: ~€10-100', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - D8 (Digital Nomad)", 'amount' => 39360, 'currency' => 'EUR', 'description' => '4x Min Wage (~€3,280/month)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'D7 (Passive Income)',
                        'items' => [
                            ['name' => "Visa + Res Permit Fee - D7 (Passive Income)", 'amount' => 260, 'currency' => 'EUR', 'description' => '~€90 (Visa) + ~€170 (Permit)', 'is_mandatory' => true],
                            ['name' => "Health Insurance - D7 (Passive Income)", 'amount' => 4, 'currency' => 'EUR', 'description' => 'Travel Ins. (4 months)', 'is_mandatory' => true],
                            ['name' => "Legalizations / Tax Rep - D7 (Passive Income)", 'amount' => 500, 'currency' => 'EUR', 'description' => 'NIF/Bank Setup / Rep: €500+', 'is_mandatory' => true],
                            ['name' => "Registration (NIF, NISS) - D7 (Passive Income)", 'amount' => 10, 'currency' => 'EUR', 'description' => 'NIF setup: ~€10-100', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - D7 (Passive Income)", 'amount' => 9840, 'currency' => 'EUR', 'description' => 'Minimum Wage (~€9,840/year)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'items' => [
                            ['name' => "Visa + Res Permit Fee - Student", 'amount' => 90, 'currency' => 'EUR', 'description' => '~€90', 'is_mandatory' => true],
                            ['name' => "Legalizations / Tax Rep - Student", 'amount' => 100, 'currency' => 'EUR', 'description' => 'Document Apostille: €100', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student", 'amount' => 9840, 'currency' => 'EUR', 'description' => 'Min Wage (€820/mo) or Guarantee', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'IE' => [
                'name' => 'Ireland',
                'pathways' => [
                    [
                        'name' => 'Critical Skills Worker',
                        'items' => [
                            ['name' => "Visa App Fee - Critical Skills Worker", 'amount' => 1000, 'currency' => 'EUR', 'description' => '€1,000 (usually by employ)', 'is_mandatory' => true],
                            ['name' => "Health Insurance - Critical Skills Worker", 'amount' => 500, 'currency' => 'EUR', 'description' => 'Private Policy (~€500+/yr)', 'is_mandatory' => true],
                            ['name' => "Med/Police/Translations - Critical Skills Worker", 'amount' => 100, 'currency' => 'EUR', 'description' => '€100+', 'is_mandatory' => true],
                            ['name' => "IRP Registration - Critical Skills Worker", 'amount' => 300, 'currency' => 'EUR', 'description' => '€300/year', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Critical Skills Worker", 'amount' => 64000, 'currency' => 'EUR', 'description' => '>€32K/yr or >€64k/yr Salary', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'General Employment',
                        'items' => [
                            ['name' => "Visa App Fee - General Employment", 'amount' => 1000, 'currency' => 'EUR', 'description' => '€1,000 (usually by employ)', 'is_mandatory' => true],
                            ['name' => "Health Insurance - General Employment", 'amount' => 500, 'currency' => 'EUR', 'description' => 'Private Policy (~€500+/yr)', 'is_mandatory' => true],
                            ['name' => "Med/Police/Translations - General Employment", 'amount' => 100, 'currency' => 'EUR', 'description' => '€100+', 'is_mandatory' => true],
                            ['name' => "IRP Registration - General Employment", 'amount' => 300, 'currency' => 'EUR', 'description' => '€300/year', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'items' => [
                            ['name' => "Visa App Fee - Student", 'amount' => 60, 'currency' => 'EUR', 'description' => '€60 (Single) / €100 (Mult)', 'is_mandatory' => true],
                            ['name' => "Health Insurance - Student", 'amount' => 150, 'currency' => 'EUR', 'description' => 'Private Policy (~€150+/yr)', 'is_mandatory' => true],
                            ['name' => "Med/Police/Translations - Student", 'amount' => 200, 'currency' => 'EUR', 'description' => 'English Test: ~€200', 'is_mandatory' => true],
                            ['name' => "IRP Registration - Student", 'amount' => 300, 'currency' => 'EUR', 'description' => '€300/year', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student", 'amount' => 10000, 'currency' => 'EUR', 'description' => '€10,000/year (minimum)', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Tuition/Capital) - Student", 'amount' => 15000, 'currency' => 'EUR', 'description' => 'Full tuition paid (€10k-€25k)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Stamp 4 (Family)',
                        'items' => [
                            ['name' => "Visa App Fee - Stamp 4 (Family)", 'amount' => 60, 'currency' => 'EUR', 'description' => '€60 / €100', 'is_mandatory' => true],
                            ['name' => "Med/Police/Translations - Stamp 4 (Family)", 'amount' => 100, 'currency' => 'EUR', 'description' => '€100+', 'is_mandatory' => true],
                            ['name' => "IRP Registration - Stamp 4 (Family)", 'amount' => 300, 'currency' => 'EUR', 'description' => '€300/year', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'NO' => [
                'name' => 'Norway',
                'pathways' => [
                    [
                        'name' => 'Skilled Worker',
                        'items' => [
                            ['name' => "App Fee (UDI) - Skilled Worker", 'amount' => 6300, 'currency' => 'NOK', 'description' => '6,300 NOK', 'is_mandatory' => true],
                            ['name' => "Legalizations - Skilled Worker", 'amount' => 500, 'currency' => 'NOK', 'description' => 'Appx: 500 NOK', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student',
                        'items' => [
                            ['name' => "App Fee (UDI) - Student", 'amount' => 5900, 'currency' => 'NOK', 'description' => '5,900 NOK', 'is_mandatory' => true],
                            ['name' => "Health System - Student", 'amount' => 1, 'currency' => 'NOK', 'description' => 'Only required if <1 year', 'is_mandatory' => true],
                            ['name' => "Legalizations - Student", 'amount' => 500, 'currency' => 'NOK', 'description' => 'Appx: 500 NOK', 'is_mandatory' => true],
                            ['name' => "Language / Skills Check - Student", 'amount' => 2500, 'currency' => 'NOK', 'description' => 'English Test: 2,500 NOK', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student", 'amount' => 137907, 'currency' => 'NOK', 'description' => '137,907 NOK/year (deposit)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Job Seeker (Grads)',
                        'items' => [
                            ['name' => "App Fee (UDI) - Job Seeker (Grads)", 'amount' => 6300, 'currency' => 'NOK', 'description' => '6,300 NOK', 'is_mandatory' => true],
                            ['name' => "Health System - Job Seeker (Grads)", 'amount' => 1, 'currency' => 'NOK', 'description' => 'Private Insurance (1 yr)', 'is_mandatory' => true],
                            ['name' => "Legalizations - Job Seeker (Grads)", 'amount' => 500, 'currency' => 'NOK', 'description' => 'Appx: 500 NOK', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Job Seeker (Grads)", 'amount' => 258408, 'currency' => 'NOK', 'description' => '258,408 NOK (for 6 months)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Family Immigration',
                        'items' => [
                            ['name' => "App Fee (UDI) - Family Immigration", 'amount' => 11900, 'currency' => 'NOK', 'description' => '11,900 NOK', 'is_mandatory' => true],
                            ['name' => "Legalizations - Family Immigration", 'amount' => 1000, 'currency' => 'NOK', 'description' => 'Appx: 1,000 NOK', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Family Immigration", 'amount' => 320000, 'currency' => 'NOK', 'description' => 'Sponsor income req (~320,000 NOK/yr)', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
            'IT' => [
                'name' => 'Italy',
                'pathways' => [
                    [
                        'name' => 'EU Blue Card (Skilled)',
                        'items' => [
                            ['name' => "Visa + Permit (Permesso) Fee - EU Blue Card (Skilled)", 'amount' => 186, 'currency' => 'EUR', 'description' => '~€116 (€50 visa + €70+ Permit)', 'is_mandatory' => true],
                            ['name' => "Health Insurance / System - EU Blue Card (Skilled)", 'amount' => 200, 'currency' => 'EUR', 'description' => 'SSN Registration (~€200-700 vol)', 'is_mandatory' => true],
                            ['name' => "Legalization / Apostille / Null - EU Blue Card (Skilled)", 'amount' => 400, 'currency' => 'EUR', 'description' => '*Nulla Osta*/Degree Trans: €400+', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - EU Blue Card (Skilled)", 'amount' => 27000, 'currency' => 'EUR', 'description' => 'Min Salary (~€27,000+/year)', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Digital Nomad Visa',
                        'items' => [
                            ['name' => "Visa + Permit (Permesso) Fee - Digital Nomad Visa", 'amount' => 116, 'currency' => 'EUR', 'description' => '~€116', 'is_mandatory' => true],
                            ['name' => "Legalization / Apostille / Null - Digital Nomad Visa", 'amount' => 300, 'currency' => 'EUR', 'description' => 'Apostilles / Trans: €300+', 'is_mandatory' => true],
                            ['name' => "Registration (Codice Fiscale) - Digital Nomad Visa", 'amount' => 200, 'currency' => 'EUR', 'description' => 'CF / Partita IVA setup: €200', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Digital Nomad Visa", 'amount' => 28000, 'currency' => 'EUR', 'description' => '~€28,000/year', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Elective Residence',
                        'items' => [
                            ['name' => "Visa + Permit (Permesso) Fee - Elective Residence", 'amount' => 116, 'currency' => 'EUR', 'description' => '~€116', 'is_mandatory' => true],
                            ['name' => "Legalization / Apostille / Null - Elective Residence", 'amount' => 500, 'currency' => 'EUR', 'description' => 'Notary / House Contract: €500+', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Elective Residence", 'amount' => 31000, 'currency' => 'EUR', 'description' => '€31,000/year passive income', 'is_mandatory' => true],
                        ]
                    ],
                    [
                        'name' => 'Student Visa',
                        'items' => [
                            ['name' => "Visa + Permit (Permesso) Fee - Student Visa", 'amount' => 120, 'currency' => 'EUR', 'description' => '~€50 + ~€70 (Permesso)', 'is_mandatory' => true],
                            ['name' => "Health Insurance / System - Student Visa", 'amount' => 150, 'currency' => 'EUR', 'description' => 'SSN (~€150/year) OR Private', 'is_mandatory' => true],
                            ['name' => "Legalization / Apostille / Null - Student Visa", 'amount' => 200, 'currency' => 'EUR', 'description' => 'Degree Apostille/DoV: €200+', 'is_mandatory' => true],
                            ['name' => "Proof of Funds (Living) - Student Visa", 'amount' => 5520, 'currency' => 'EUR', 'description' => 'Minimum €460/month', 'is_mandatory' => true],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($data as $code => $countryData) {
            $country = Country::where('code', $code)->first();
            if (!$country) continue;

            // Flight estimate for every country as a non-visa-specific requirement
            CostItem::create([
                'name' => 'Flight to ' . $countryData['name'] . ' (Est.)',
                'amount' => 1000,
                'currency' => 'USD',
                'description' => 'Estimated flight cost',
                'is_mandatory' => false,
                'country_id' => $country->id
            ]);

            foreach ($countryData['pathways'] as $p) {
                $visaType = VisaType::where('country_id', $country->id)
                                    ->where('name', $p['name'])
                                    ->first();
                
                if ($visaType) {
                    foreach ($p['items'] as $item) {
                        CostItem::create([
                            'name' => $item['name'],
                            'amount' => $item['amount'],
                            'currency' => $item['currency'],
                            'description' => $item['description'],
                            'is_mandatory' => $item['is_mandatory'],
                            'country_id' => $country->id,
                            'visa_type_id' => $visaType->id
                        ]);
                    }
                }
            }
        }
    }
}
