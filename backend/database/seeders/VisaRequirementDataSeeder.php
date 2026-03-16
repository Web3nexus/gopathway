<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\VisaType;
use Illuminate\Database\Seeder;

class VisaRequirementDataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'GB' => [
                'Skilled Worker' => [
                    'requirements' => ['Job Offer from licensed sponsor', 'Certificate of Sponsorship (CoS)', 'Knowledge of English', 'Minimum salary threshold (£38,700 or going rate)'],
                    'processing_time' => '3-8 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'high_school',
                    'min_work_experience_years' => 1,
                    'min_ielts_score' => 4.0,
                    'min_funds_required' => 1270,
                ],
                'Student' => [
                    'requirements' => ['Confirmation of Acceptance for Studies (CAS)', 'Proof of funds for course and living costs', 'Knowledge of English', 'ATAS certificate (if applicable)'],
                    'processing_time' => '3-4 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'high_school',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => 5.5,
                    'min_funds_required' => 1334,
                ],
                'Global Talent' => [
                    'requirements' => ['Endorsement from approved body', 'Evidence of exceptional talent or promise', 'TB Test certificate (if applicable)'],
                    'processing_time' => '3-8 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'bachelors',
                    'min_work_experience_years' => 3,
                    'min_ielts_score' => null,
                    'min_funds_required' => 0,
                ],
                'Family/Spouse' => [
                    'requirements' => ['Partner must be British citizen or highly settled', 'Minimum Income Requirement (£29,000)', 'Proof of genuine relationship', 'Knowledge of English'],
                    'processing_time' => '12-24 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'none',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => 4.0,
                    'min_funds_required' => 29000,
                ],
            ],
            'CA' => [
                'Express Entry (FSW/CEC/FST)' => [
                    'requirements' => ['Educational Credential Assessment (ECA)', 'Language test results (IELTS/CELPIP/TEF)', 'Work experience proof', 'Proof of funds (if applicable)'],
                    'processing_time' => '6-8 months',
                    'pr_possibility' => true,
                    'min_education_level' => 'bachelors',
                    'min_work_experience_years' => 1,
                    'min_ielts_score' => 6.0,
                    'min_funds_required' => 13757,
                ],
                'Provincial Nominee (PNP)' => [
                    'requirements' => ['Provincial Nomination Certificate', 'Language test results', 'Proof of funds', 'Connection to province (study/work/job offer)'],
                    'processing_time' => '6-11 months',
                    'pr_possibility' => true,
                    'min_education_level' => 'high_school',
                    'min_work_experience_years' => 1,
                    'min_ielts_score' => 5.0,
                    'min_funds_required' => 13757,
                ],
                'Study Permit' => [
                    'requirements' => ['Letter of Acceptance from DLI', 'Proof of financial support', 'Provincial Attestation Letter (if applicable)', 'Medical exam'],
                    'processing_time' => '8-12 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'high_school',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => 6.0,
                    'min_funds_required' => 20635,
                ],
                'Family Sponsorship' => [
                    'requirements' => ['Sponsor must be Canadian PR/Citizen', 'Proof of relationship', 'Sponsor financial evaluation', 'Medical and police certificates'],
                    'processing_time' => '10-14 months',
                    'pr_possibility' => true,
                    'min_education_level' => 'none',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => null,
                    'min_funds_required' => 0,
                ],
            ],
            'AU' => [
                'Skilled Independent (189/190)' => [
                    'requirements' => ['Skills assessment in a relevant occupation', 'Age under 45', 'Competent English', 'Points test score of 65 or higher', 'Invitation to apply'],
                    'processing_time' => '8-14 months',
                    'pr_possibility' => true,
                    'min_education_level' => 'bachelors',
                    'min_work_experience_years' => 3,
                    'min_ielts_score' => 6.0,
                    'min_funds_required' => 5000,
                ],
                'Employer Sponsored (TSS 482)' => [
                    'requirements' => ['Nomination by an approved sponsor', 'Relevant skills and qualifications', 'English language proficiency', 'Health insurance'],
                    'processing_time' => '4-8 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'diploma',
                    'min_work_experience_years' => 2,
                    'min_ielts_score' => 5.0,
                    'min_funds_required' => 2000,
                ],
                'Student (subclass 500)' => [
                    'requirements' => ['Confirmation of Enrolment (CoE)', 'Genuine Student (GS) requirement', 'Overseas Student Health Cover (OSHC)', 'English language test'],
                    'processing_time' => '4-8 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'high_school',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => 5.5,
                    'min_funds_required' => 24505,
                ],
                'Partner Visa (820/801)' => [
                    'requirements' => ['Sponsor is an Australian citizen, PR, or eligible NZ citizen', 'Married or de facto relationship', 'Health and character checks'],
                    'processing_time' => '12-24 months',
                    'pr_possibility' => true,
                    'min_education_level' => 'none',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => null,
                    'min_funds_required' => 0,
                ],
            ],
            'NZ' => [
                'Skilled Migrant Category' => [
                    'requirements' => ['Age 55 or under', 'English language requirements', '6 points minimum (qualifications, income, NZ registration)', 'Health and character checks'],
                    'processing_time' => '6-12 months',
                    'pr_possibility' => true,
                    'min_education_level' => 'bachelors',
                    'min_work_experience_years' => 3,
                    'min_ielts_score' => 6.5,
                    'min_funds_required' => 5000,
                ],
                'Accredited Employer Work' => [
                    'requirements' => ['Job offer from an accredited employer', 'Meet median wage requirement', 'Relevant skills/experience', 'Health and character checks'],
                    'processing_time' => '4-8 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'high_school',
                    'min_work_experience_years' => 1,
                    'min_ielts_score' => null,
                    'min_funds_required' => 2000,
                ],
                'Student Visa' => [
                    'requirements' => ['Offer of place from NZ educational provider', 'Evidence of sufficient funds', 'Return ticket or proof of onward travel', 'Health insurance'],
                    'processing_time' => '6-10 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'high_school',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => 5.5,
                    'min_funds_required' => 20000,
                ],
                'Partner of a NZer' => [
                    'requirements' => ['Partner must be NZ citizen or resident', 'Living together in genuine and stable relationship', 'Health and character requirements'],
                    'processing_time' => '7-14 months',
                    'pr_possibility' => true,
                    'min_education_level' => 'none',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => null,
                    'min_funds_required' => 0,
                ],
            ],
            'DE' => [
                'EU Blue Card (Skilled Worker)' => [
                    'requirements' => ['University degree recognized in Germany', 'Employment contract or binding offer', 'Minimum gross annual salary (€45,300 or €41,041 for shortage occupations)'],
                    'processing_time' => '4-8 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'bachelors',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => null,
                    'min_funds_required' => 0,
                ],
                'Job Seeker (Opportunity Card)' => [
                    'requirements' => ['Recognized qualification or degree', 'Language skills (A1 German or B2 English)', 'Sufficient funds', 'Score minimum 6 points in the points system'],
                    'processing_time' => '4-12 weeks',
                    'pr_possibility' => false,
                    'min_education_level' => 'diploma',
                    'min_work_experience_years' => 2,
                    'min_ielts_score' => 5.5,
                    'min_funds_required' => 1027,
                ],
                'Student Visa' => [
                    'requirements' => ['Admission letter from a German university', 'Proof of financial resources (Blocked account)', 'Health insurance', 'University entrance qualification'],
                    'processing_time' => '4-8 weeks',
                    'pr_possibility' => true,
                    'min_education_level' => 'high_school',
                    'min_work_experience_years' => 0,
                    'min_ielts_score' => 6.0,
                    'min_funds_required' => 11208,
                ],
                'Freelance/Self-Employed' => [
                    'requirements' => ['Economic interest or regional need', 'Positive impact on economy', 'Financing secured', 'Adequate pension provision (if over 45)'],
                    'processing_time' => '3-6 months',
                    'pr_possibility' => true,
                    'min_education_level' => 'bachelors',
                    'min_work_experience_years' => 2,
                    'min_ielts_score' => null,
                    'min_funds_required' => 10000,
                ],
            ],
        ];

        $genericData = [
            'Student' => [
                'requirements' => ['Acceptance Letter from Institution', 'Proof of Financial Resources', 'Health Insurance Proof', 'Language Proficiency Certificate'],
                'processing_time' => '4-8 weeks',
                'pr_possibility' => true,
                'min_education_level' => 'high_school',
                'min_work_experience_years' => 0,
                'min_ielts_score' => 5.5,
                'min_funds_required' => 10000,
            ],
            'Digital Nomad' => [
                'requirements' => ['Remote work contract', 'Proof of minimum sustained income', 'Comprehensive health insurance', 'Clean criminal record'],
                'processing_time' => '4-8 weeks',
                'pr_possibility' => false,
                'min_education_level' => 'high_school',
                'min_work_experience_years' => 1,
                'min_ielts_score' => null,
                'min_funds_required' => 3000,
            ],
            'Job Seeker' => [
                'requirements' => ['University degree or recognized qualifications', 'Proof of funds to support stay', 'Comprehensive health insurance', 'Clean criminal record'],
                'processing_time' => '4-12 weeks',
                'pr_possibility' => false,
                'min_education_level' => 'diploma',
                'min_work_experience_years' => 1,
                'min_ielts_score' => null,
                'min_funds_required' => 1500,
            ],
            'Family Sponsorship' => [
                'requirements' => ['Sponsor must be PR/Citizen', 'Proof of genuine relationship', 'Sponsor financial evaluation', 'Medical and police certificates'],
                'processing_time' => '10-24 months',
                'pr_possibility' => true,
                'min_education_level' => 'none',
                'min_work_experience_years' => 0,
                'min_ielts_score' => null,
                'min_funds_required' => 0,
            ],
        ];

        $fallbackWork = [
            'requirements' => ['Valid job offer or employment contract', 'Qualifications match job requirements', 'Salary meets minimum thresholds', 'Health insurance'],
            'processing_time' => '4-12 weeks',
            'pr_possibility' => true,
            'min_education_level' => 'bachelors',
            'min_work_experience_years' => 2,
            'min_ielts_score' => 5.5,
            'min_funds_required' => 3000,
        ];

        $countries = Country::all();

        foreach ($countries as $country) {
            $visaTypes = VisaType::where('country_id', $country->id)->get();
            foreach ($visaTypes as $visa) {
                $specificData = $data[$country->code][$visa->name] ?? null;

                if (!$specificData) {
                    if (str_contains(strtolower($visa->name), 'student')) {
                        $specificData = $genericData['Student'];
                    } elseif (str_contains(strtolower($visa->name), 'nomad') || str_contains(strtolower($visa->name), 'remote')) {
                        $specificData = $genericData['Digital Nomad'];
                    } elseif (str_contains(strtolower($visa->name), 'family') || str_contains(strtolower($visa->name), 'spouse') || str_contains(strtolower($visa->name), 'partner')) {
                        $specificData = $genericData['Family Sponsorship'];
                    } elseif (str_contains(strtolower($visa->name), 'job seeker') || str_contains(strtolower($visa->name), 'orientation')) {
                        $specificData = $genericData['Job Seeker'];
                    } else {
                        // Default to skilled work
                        $specificData = $fallbackWork;
                    }
                }

                $visa->update([
                    'requirements' => $specificData['requirements'],
                    'processing_time' => $specificData['processing_time'],
                    'pr_possibility' => $specificData['pr_possibility'],
                    'min_education_level' => $specificData['min_education_level'],
                    'min_work_experience_years' => $specificData['min_work_experience_years'],
                    'min_ielts_score' => $specificData['min_ielts_score'],
                    'min_funds_required' => $specificData['min_funds_required'],
                ]);
            }
        }
    }
}
