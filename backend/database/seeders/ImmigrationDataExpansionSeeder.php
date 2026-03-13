<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\VisaType;
use App\Models\DocumentType;
use App\Models\CostItem;
use App\Models\RelocationKit;
use App\Models\RelocationKitItem;
use Illuminate\Database\Seeder;

/**
 * Expands the GoPathway database with 15 countries and detailed immigration intelligence.
 * Covers: UK, Canada, Germany, Portugal, Spain, Ireland, Australia, New Zealand,
 * Netherlands, France, Italy, Sweden, Finland, Norway, Austria.
 *
 * Uses updateOrCreate throughout to prevent duplicates on re-run.
 */
class ImmigrationDataExpansionSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCountriesAndVisaTypes();
        $this->seedCostTemplates();
        $this->seedDocumentTypes();
        $this->seedRelocationKits();
    }

    // ─────────────────────────────────────────────────────────────
    //  COUNTRIES + VISA TYPES (Detailed Intelligence)
    // ─────────────────────────────────────────────────────────────

    private function seedCountriesAndVisaTypes(): void
    {
        $now = now();

        // ── Ireland ───────────────────────────────────────────────
        $ireland = Country::updateOrCreate(
            ['code' => 'IE'],
            [
                'name'        => 'Ireland',
                'description' => 'A vibrant English-speaking EU country with world-class tech companies and a thriving expat scene.',
                'image_url'   => 'https://images.unsplash.com/photo-1564959130747-897fb406b9af?q=80&w=2670&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $ireland->id, 'name' => 'Study Visa'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'For students accepted by Irish universities and language schools.',
                'processing_time'      => '4-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.irishimmigration.ie/coming-to-study-in-ireland/',
                'last_verified_at'     => $now,
                'benefits' => ['Part-time work (20h/week)', 'Stay back option (1-2 years)', 'EU travel access'],
                'restrictions' => ['Must maintain 85% attendance', 'Limited social welfare access'],
                'requirements' => [
                    'University Acceptance Letter',
                    'Proof of Funds (€7,000+)',
                    'Private Medical Insurance',
                    'Proof of Accommodation',
                    'English Language Proof',
                ],
                'min_funds_required'   => 7500,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $ireland->id, 'name' => 'Critical Skills Employment Permit'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'For skilled professionals in occupations on Ireland\'s critical skills list.',
                'processing_time'      => '4-6 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://enterprise.gov.ie/en/what-we-do/workplace-and-skills/employment-permits/permit-types/critical-skills-employment-permit/',
                'last_verified_at'     => $now,
                'benefits' => ['Fast-track to PR (2 years)', 'Family reunification', 'No labor market test needed'],
                'restrictions' => ['Must work for sponsoring employer for 2 years', 'Minimum salary threshold'],
                'requirements' => [
                    'Job Offer (€32,000+ salary)',
                    'Relevant Degree or Qualification',
                    'Employer Application',
                    'Passport',
                    'Police Clearance',
                ],
                'min_education_level'       => 'bachelors',
                'min_work_experience_years' => 2,
                'min_funds_required'        => 5000,
                'min_ielts_score'           => 6.5,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $ireland->id, 'name' => 'Graduate (Stay & Thrive) Permission'],
            [
                'pathway_type'         => 'Post-Study Work',
                'description'          => 'Allows graduates to remain in Ireland for up to 2 years to seek employment.',
                'processing_time'      => '2-4 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.irishimmigration.ie/coming-to-study-in-ireland/third-level-graduate-programme/',
                'last_verified_at'     => $now,
                'benefits' => ['Full-time work rights', 'No sponsorship initially required', 'Pathway to work permit'],
                'requirements' => [
                    'Proof of Graduation from Irish Institution',
                    'Valid Passport',
                    'Proof of Address',
                    'Proof of Funds',
                ],
                'min_funds_required' => 3000,
            ]
        );

        // ── Australia ─────────────────────────────────────────────
        $australia = Country::updateOrCreate(
            ['code' => 'AU'],
            [
                'name'        => 'Australia',
                'description' => 'One of the world\'s most popular immigration destinations with clear pathways to permanent residency.',
                'image_url'   => 'https://images.unsplash.com/photo-1523428096881-5bd79d043006?q=80&w=2670&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $australia->id, 'name' => 'Student Visa (Subclass 500)'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'For international students enrolled in full-time registered courses in Australia.',
                'processing_time'      => '4-6 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://immi.homeaffairs.gov.au/visas/getting-a-visa/visa-listing/student-500',
                'last_verified_at'     => $now,
                'benefits' => ['Work up to 48h/fortnight', 'Include family members', 'Post-study work eligibility'],
                'requirements' => [
                    'Confirmation of Enrolment (CoE)',
                    'Genuine Temporary Entrant statement',
                    'OSHC Health Insurance',
                    'IELTS / PTE Score',
                    'Proof of Funds (AUD 21,041/year)',
                    'Health Clearance',
                ],
                'min_funds_required'   => 14000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $australia->id, 'name' => 'Graduate Temporary Visa (Subclass 485)'],
            [
                'pathway_type'         => 'Post-Study Work',
                'description'          => 'Allows graduates to live and work in Australia after completing studies.',
                'processing_time'      => '4-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://immi.homeaffairs.gov.au/visas/getting-a-visa/visa-listing/temporary-graduate-485',
                'last_verified_at'     => $now,
                'benefits' => ['Unlimited work rights', 'Pathway to PR via points system', 'Stay for 2-4 years'],
                'requirements' => [
                    'Australian Degree/Diploma',
                    'English Proficiency',
                    'Health Insurance',
                    'Aged Under 50',
                    'Passport',
                ],
                'min_funds_required' => 5000,
                'min_ielts_score'    => 6.0,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $australia->id, 'name' => 'Skilled Independent Visa (Subclass 189)'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'Points-tested permanent residency for skilled workers not sponsored by an employer or family member.',
                'processing_time'      => '12-24 months',
                'pr_possibility'       => true,
                'official_source_link' => 'https://immi.homeaffairs.gov.au/visas/getting-a-visa/visa-listing/skilled-independent-189',
                'last_verified_at'     => $now,
                'benefits' => ['Permanent Residency', 'Medicare access', 'Subsidized education', 'Sponsor relatives'],
                'requirements' => [
                    'Skills Assessment',
                    'Expression of Interest (SkillSelect)',
                    'English Language Test',
                    'Health & Character Clearance',
                    'Points Score ≥ 65',
                ],
                'min_education_level'       => 'bachelors',
                'min_work_experience_years' => 3,
                'min_ielts_score'           => 7.0,
                'min_funds_required'        => 10000,
            ]
        );

        // ── New Zealand ───────────────────────────────────────────
        $nz = Country::updateOrCreate(
            ['code' => 'NZ'],
            [
                'name'        => 'New Zealand',
                'description' => 'A breathtaking country with open immigration policies and a high quality of life.',
                'image_url'   => 'https://images.unsplash.com/photo-1507699622108-4be3abd695ad?q=80&w=2671&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $nz->id, 'name' => 'Student Visa'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'For international students enrolled in NZ educational institutions.',
                'processing_time'      => '4-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.immigration.govt.nz/new-zealand-visas/visas/visa/student-visa',
                'last_verified_at'     => $now,
                'benefits' => ['Part-time work (20h/week)', 'Full-time work in holidays', 'Post-study pathway'],
                'requirements' => [
                    'Offer of Place from NZ Institution',
                    'Proof of Funds (NZD 15,000/year)',
                    'Insurance (AXA/Tower)',
                    'English Language Proof',
                    'Health & Police Clearance',
                ],
                'min_funds_required' => 10000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $nz->id, 'name' => 'Post-Study Work Visa'],
            [
                'pathway_type'         => 'Post-Study Work',
                'description'          => 'Allows graduates to work in NZ for up to 3 years after study.',
                'processing_time'      => '2-4 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.immigration.govt.nz/new-zealand-visas/visas/visa/post-study-work-visa',
                'last_verified_at'     => $now,
                'benefits' => ['Work for any employer', 'Stay up to 3 years', 'Pathway to Resident visa'],
                'requirements' => [
                    'NZ Degree/Diploma Completion',
                    'Valid Passport',
                    'Health Insurance',
                    'Proof of Enrolment Completion',
                ],
                'min_funds_required' => 4000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $nz->id, 'name' => 'Skilled Migrant Category Resident Visa'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'Points-based permanent residency for skilled workers.',
                'processing_time' => '12-18 months',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.immigration.govt.nz/new-zealand-visas/visas/visa/skilled-migrant-category-resident-visa',
                'last_verified_at'     => $now,
                'benefits' => ['Indefinite stay', 'Public health access', 'Vote after 12 months', 'Citizenship pathway'],
                'requirements' => [
                    'Skilled Job in NZ or Job Offer',
                    'English Language',
                    'Points Score ≥ 160',
                    'Health & Character',
                    'Skills Assessment',
                ],
                'min_education_level'       => 'bachelors',
                'min_work_experience_years' => 2,
                'min_ielts_score'           => 6.5,
                'min_funds_required'        => 12000,
            ]
        );

        // ── Netherlands ───────────────────────────────────────────
        $nl = Country::updateOrCreate(
            ['code' => 'NL'],
            [
                'name'        => 'Netherlands',
                'description' => 'A progressive, international country and gateway to Europe with a strong English-speaking workforce.',
                'image_url'   => 'https://images.unsplash.com/photo-1576924542622-772281b13aa6?q=80&w=2670&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $nl->id, 'name' => 'MVV Study Visa'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'Entry visa + residence permit for students at Dutch universities.',
                'processing_time'      => '4-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://ind.nl/en/residence-permits/study',
                'last_verified_at'     => $now,
                'benefits' => ['Orientation year after study', 'High English proficiency environment', 'Schengen travel'],
                'requirements' => [
                    'University Admission Letter',
                    'Proof of Funds (€13,000+)',
                    'Academic Transcripts',
                    'English Proficiency (IELTS/TOEFL)',
                    'Health Insurance',
                    'Tuberculosis Test (if required)',
                ],
                'min_funds_required' => 14000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $nl->id, 'name' => 'Highly Skilled Migrant (Kennismigrant)'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'For professionals hired by IND-recognised Dutch companies.',
                'processing_time'      => '2-4 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://ind.nl/en/residence-permits/work/highly-skilled-migrant',
                'last_verified_at'     => $now,
                'benefits' => ['30% Tax Ruling eligibility', 'Fast-track processing', 'Family can work without permit'],
                'requirements' => [
                    'Job Offer from Recognised Sponsor',
                    'Salary ≥ €3,672/month (under 30) or €5,008/month (30+)',
                    'Educational Credentials',
                    'Passport',
                    'Residence Application Form',
                ],
                'min_education_level'       => 'bachelors',
                'min_work_experience_years' => 1,
                'min_funds_required'        => 5000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $nl->id, 'name' => 'Startup Visa'],
            [
                'pathway_type'         => 'Startup / Entrepreneur',
                'description'          => 'For innovative entrepreneurs with a facilitator to help develop their business.',
                'processing_time'      => '4-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://ind.nl/en/residence-permits/work/start-up',
                'last_verified_at'     => $now,
                'benefits' => ['1 year to launch business', 'Guided by expert facilitator', 'Can transition to self-employed permit'],
                'requirements' => [
                    'Recognised Facilitator',
                    'Business Plan',
                    'Innovative Product/Service',
                    'Proof of Funds',
                ],
                'min_funds_required' => 15000,
            ]
        );

        // ── France ────────────────────────────────────────────────
        $france = Country::updateOrCreate(
            ['code' => 'FR'],
            [
                'name'        => 'France',
                'description' => 'Europe\'s cultural capital with world-class universities and strong career opportunities.',
                'image_url'   => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?q=80&w=2673&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $france->id, 'name' => 'Long-Stay Student Visa (VLS-TS)'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'For international students enrolled in French universities or grandes écoles.',
                'processing_time'      => '2-6 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.france-visas.gouv.fr/en/web/france-visas/study',
                'last_verified_at'     => $now,
                'benefits' => ['Housing subsidy (CAF)', 'Work (964h/year)', 'Student discounts', 'Schengen travel'],
                'requirements' => [
                    'Campus France Approval',
                    'University Acceptance Letter',
                    'Proof of Funds (€615/month)',
                    'Accommodation Proof',
                    'Health Insurance',
                    'Academic Transcripts',
                    'Motivation Letter',
                ],
                'min_funds_required' => 8000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $france->id, 'name' => 'Talent Passport Visa'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'For skilled professionals, researchers, entrepreneurs and artists.',
                'processing_time'      => '4-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.service-public.fr/particuliers/vosdroits/F34593?lang=en',
                'last_verified_at'     => $now,
                'benefits' => ['Valid for 4 years', 'Family included with work rights', 'No separate work permit needed'],
                'requirements' => [
                    'Work Contract',
                    'Salary Above Threshold (€35,000+)',
                    'Employer Sponsorship',
                    'Degree Certificate',
                    'CV/Resume',
                ],
                'min_education_level'       => 'bachelors',
                'min_work_experience_years' => 2,
                'min_funds_required'        => 5000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $france->id, 'name' => 'Job Seeker Visa (APS)'],
            [
                'pathway_type'         => 'Post-Study Work',
                'description'          => 'Graduates from French institutions can seek work for up to 12 months post-study.',
                'processing_time'      => '2-4 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.campusfrance.org/en/the-temporary-resident-permit-aps',
                'last_verified_at'     => $now,
                'benefits' => ['Seek work or start business', 'Unlimited work duration during validity', 'Transition to work permit'],
                'requirements' => [
                    'French Degree/Master\'s Certificate',
                    'Proof of Graduation',
                    'Valid Residence Permit',
                ],
                'min_funds_required' => 4200,
            ]
        );

        // ── Italy ─────────────────────────────────────────────────
        $italy = Country::updateOrCreate(
            ['code' => 'IT'],
            [
                'name'        => 'Italy',
                'description' => 'Rich in culture, history, and opportunity with affordable living costs and EU access.',
                'image_url'   => 'https://images.unsplash.com/photo-1515542622106-078bda69bf98?q=80&w=2670&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $italy->id, 'name' => 'Student Visa (Type D)'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'For students admitted to Italian universities requiring more than 90 days of stay.',
                'processing_time'      => '3-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://vistoperitalia.esteri.it/home/en',
                'last_verified_at'     => $now,
                'benefits' => ['Work 20h/week', 'Low tuition fees', 'Schengen travel', 'Post-study conversion'],
                'requirements' => [
                    'Universitaly Pre-enrollment',
                    'University Admission Letter',
                    'Academic Transcripts',
                    'Proof of Accommodation',
                    'Financial Proof (€6,000–7,000/year)',
                    'Health Insurance',
                ],
                'min_funds_required' => 7500,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $italy->id, 'name' => 'Work Residence Permit'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'Employment-based permit tied to a job offer during the annual "Decreto Flussi" quota window.',
                'processing_time'      => '2-6 months',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.interno.gov.it/it/temi/immigrazione-e-asilo/modalita-dingresso/decreto-flussi',
                'last_verified_at'     => $now,
                'benefits' => ['Full residence rights', 'Access to public healthcare', 'Citizenship pathway (10 years)'],
                'requirements' => [
                    'Job Offer from Italian Employer',
                    'Within Annual Immigration Quotas',
                    'Employer NULLA Osta',
                    'Passport',
                    'Health Insurance',
                ],
                'min_education_level'       => 'high_school',
                'min_work_experience_years' => 1,
                'min_funds_required'        => 5000,
            ]
        );

        // ── Sweden ────────────────────────────────────────────────
        $sweden = Country::updateOrCreate(
            ['code' => 'SE'],
            [
                'name'        => 'Sweden',
                'description' => 'A progressive, innovative Scandinavian nation with free public education and excellent work-life balance.',
                'image_url'   => 'https://images.unsplash.com/photo-1509356843151-3e7d96241e11?q=80&w=2670&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $sweden->id, 'name' => 'Student Residence Permit'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'For students enrolled in Swedish higher education programmes.',
                'processing_time'      => '1-3 months',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.migrationsverket.se/English/Private-individuals/Studying-in-Sweden.html',
                'last_verified_at'     => $now,
                'benefits' => ['No limit on work hours while studying', 'High standard of living', 'Innovation hub'],
                'requirements' => [
                    'University Admission Letter',
                    'Bank Statements (SEK 10,314/month)',
                    'Academic Transcripts',
                    'Health Insurance',
                    'Passport Photos',
                ],
                'min_funds_required' => 11000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $sweden->id, 'name' => 'Work Permit'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'For professionals with a job offer from a Swedish employer.',
                'processing_time'      => '3-4 months',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.migrationsverket.se/English/Private-individuals/Working-in-Sweden.html',
                'last_verified_at'     => $now,
                'benefits' => ['Family members get work permits', 'Pathway to permanent residence (4 years)', 'Union-backed worker rights'],
                'requirements' => [
                    'Job Offer from Swedish Employer',
                    'Salary Meeting Union Standard (min SEK 13,000/month)',
                    'Employer Insurance Coverage',
                    'Passport',
                ],
                'min_education_level'       => 'high_school',
                'min_work_experience_years' => 2,
                'min_funds_required'        => 3000,
            ]
        );

        // ── Finland ───────────────────────────────────────────────
        $finland = Country::updateOrCreate(
            ['code' => 'FI'],
            [
                'name'        => 'Finland',
                'description' => 'Ranked the world\'s happiest country, with free higher education and a tech-forward economy.',
                'image_url'   => 'https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?q=80&w=2670&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $finland->id, 'name' => 'Student Residence Permit'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'For students accepted to Finnish universities or universities of applied sciences.',
                'processing_time'      => '2-3 months',
                'pr_possibility'       => true,
                'official_source_link' => 'https://migri.fi/en/studying-in-finland',
                'last_verified_at'     => $now,
                'benefits' => ['Work 30h/week', 'Excellent student support', 'Clean & safe environment'],
                'requirements' => [
                    'University Admission Letter',
                    'Financial Proof (€560/month)',
                    'Academic Transcripts',
                    'Insurance Coverage',
                    'Passport',
                ],
                'min_funds_required' => 7000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $finland->id, 'name' => 'Job Seeker Permit (Post-Study)'],
            [
                'pathway_type'         => 'Post-Study Work',
                'description'          => 'Graduates from Finnish institutions receive a 2-year job search permit.',
                'processing_time'      => '4-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://migri.fi/en/extended-permit-to-look-for-work',
                'last_verified_at'     => $now,
                'benefits' => ['Stay for 2 years', 'Full work rights', 'Can search for any job'],
                'requirements' => [
                    'Finnish Degree Certificate',
                    'Proof of Study Completion',
                    'Valid Passport',
                    'Insurance',
                ],
                'min_funds_required' => 2500,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $finland->id, 'name' => 'Employed Person\'s Residence Permit'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'For non-EU nationals with a job offer in Finland.',
                'processing_time'      => '2-3 months',
                'pr_possibility'       => true,
                'official_source_link' => 'https://migri.fi/en/residence-permit-for-an-employed-person',
                'last_verified_at'     => $now,
                'benefits' => ['Access to KELA (social security)', 'Family reunification', 'Permanent residence (4 years)'],
                'requirements' => [
                    'Job Offer from Finnish Employer',
                    'Employer Partial Decision',
                    'Passport',
                    'Professional Qualifications',
                ],
                'min_education_level'       => 'bachelors',
                'min_work_experience_years' => 1,
                'min_funds_required'        => 4500,
            ]
        );

        // ── Norway ────────────────────────────────────────────────
        $norway = Country::updateOrCreate(
            ['code' => 'NO'],
            [
                'name'        => 'Norway',
                'description' => 'A wealthy Nordic nation with free public universities and one of the highest living standards globally.',
                'image_url'   => 'https://images.unsplash.com/photo-1531366936337-7c912a4589a7?q=80&w=2670&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $norway->id, 'name' => 'Student Residence Permit'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'For students admitted to Norwegian universities (most public universities charge no tuition).',
                'processing_time'      => '1-3 months',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.udi.no/en/want-to-apply/studies/',
                'last_verified_at'     => $now,
                'benefits' => ['Zero tuition (public unis)', 'Work 20h/week', 'Incredible natural beauty'],
                'requirements' => [
                    'University Admission Letter',
                    'Financial Proof (NOK 137,907/year)',
                    'Accommodation Proof',
                    'Health Insurance',
                    'Passport',
                ],
                'min_education_level'       => 'masters',
                'min_work_experience_years' => 3,
                'min_funds_required'        => 10000,
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $norway->id, 'name' => 'Skilled Worker Permit'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'For non-EU/EEA nationals with a relevant job offer or vocational skills.',
                'processing_time'      => '7-10 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.udi.no/en/want-to-apply/work-immigration/skilled-workers/',
                'last_verified_at'     => $now,
                'benefits' => ['High salaries', 'Work-life balance', 'Permanent residence (3 years)'],
                'requirements' => [
                    'Job Offer Covering Standard Terms',
                    'Relevant Qualifications',
                    'Passport',
                    'Proof of Accommodation',
                ],
            ]
        );

        // ── Austria ───────────────────────────────────────────────
        $austria = Country::updateOrCreate(
            ['code' => 'AT'],
            [
                'name'        => 'Austria',
                'description' => 'A central European gem with world-class universities, strong economy, and high quality of life.',
                'image_url'   => 'https://images.unsplash.com/photo-1609347644591-4b4d01c1d28c?q=80&w=2670&auto=format&fit=crop',
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $austria->id, 'name' => 'Student Residence Permit'],
            [
                'pathway_type'         => 'Study',
                'description'          => 'For students enrolled at Austrian universities or language schools.',
                'processing_time'      => '4-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.migration.gv.at/en/types-of-immigration/fixed-term-settlement/student/',
                'last_verified_at'     => $now,
                'benefits' => ['Work 20h/week', 'Low tuition fees', 'Central EU location', 'Post-study orientation'],
                'requirements' => [
                    'University Admission Letter',
                    'Financial Proof (€11,000/year)',
                    'Accommodation Proof',
                    'Health Insurance',
                    'Academic Transcripts',
                    'Language Certificate',
                ],
            ]
        );

        VisaType::updateOrCreate(
            ['country_id' => $austria->id, 'name' => 'Red-White-Red Card'],
            [
                'pathway_type'         => 'Skilled Work',
                'description'          => 'Points-based work and residence permit for highly qualified workers.',
                'processing_time'      => '6-8 weeks',
                'pr_possibility'       => true,
                'official_source_link' => 'https://www.migration.gv.at/en/types-of-immigration/permanent-settlement/red-white-red-card/',
                'last_verified_at'     => $now,
                'benefits' => ['Fast-track to citizenship (6-10 years)', 'EU Blue Card compatible', 'Family included'],
                'requirements' => [
                    'Points Score (min 70 of 100)',
                    'Job Offer (min salary €3,448.49/month)',
                    'Qualifications',
                    'Language Proof',
                    'Health Insurance',
                ],
            ]
        );

        // ── Also add missing visa types to existing countries ──────
        $this->expandExistingCountries($now);
    }

    // ─────────────────────────────────────────────────────────────
    //  EXPAND EXISTING COUNTRIES (Detailed Logic)
    // ─────────────────────────────────────────────────────────────

    private function expandExistingCountries($now): void
    {
        // ── UK ────────────────────────────────────────────────────
        $uk = Country::where('code', 'GB')->first();
        if ($uk) {
            VisaType::updateOrCreate(
                ['country_id' => $uk->id, 'name' => 'Student Visa (Tier 4)'],
                [
                    'pathway_type'         => 'Study',
                    'description'          => 'For international students enrolled in UK institutions.',
                    'processing_time'      => '3 weeks',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://www.gov.uk/student-visa',
                    'last_verified_at'     => $now,
                    'benefits' => ['Work 20h/week', 'Graduate visa eligibility', 'World-class education'],
                    'requirements' => ['CAS from University', 'Financial Evidence (£10,000+)', 'TB Test Certificate', 'English Language Proof'],
                    'min_funds_required'   => 13000,
                    'min_ielts_score'      => 5.5,
                ]
            );
            VisaType::updateOrCreate(
                ['country_id' => $uk->id, 'name' => 'Skilled Worker Visa'],
                [
                    'pathway_type'         => 'Skilled Work',
                    'description'          => 'For skilled professionals with a sponsoring UK employer.',
                    'processing_time'      => '8 weeks',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://www.gov.uk/skilled-worker-visa',
                    'last_verified_at'     => $now,
                    'benefits' => ['Settlement (ILR) after 5 years', 'Family sponsorship', 'NHS access'],
                    'requirements' => ['Job Offer (CoS)', 'English Language Proof', 'Salary ≥ £26,200', 'Criminal Record Check'],
                    'min_education_level'       => 'high_school',
                    'min_work_experience_years' => 2,
                    'min_ielts_score'           => 6.0,
                    'min_funds_required'        => 5000,
                ]
            );
            VisaType::updateOrCreate(
                ['country_id' => $uk->id, 'name' => 'Global Talent Visa'],
                [
                    'pathway_type'         => 'Skilled Work',
                    'description'          => 'For leaders in academia, research, arts, culture, and digital technology.',
                    'processing_time'      => '3-8 weeks',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://www.gov.uk/global-talent',
                    'last_verified_at'     => $now,
                    'benefits' => ['No employer sponsorship needed', 'Flexible work & relocation', 'Fast-track to PR (3 years)'],
                    'requirements' => [
                        'Endorsement from Approved Body',
                        'Evidence of Exceptional Talent or Promise',
                        'Passport',
                    ],
                    'min_education_level'       => 'masters',
                    'min_work_experience_years' => 3,
                    'min_funds_required'        => 10000,
                ]
            );
            VisaType::updateOrCreate(
                ['country_id' => $uk->id, 'name' => 'Graduate Visa'],
                [
                    'pathway_type'         => 'Post-Study Work',
                    'description'          => 'Allows international graduates to stay and work after study.',
                    'processing_time'      => '8 weeks',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://www.gov.uk/graduate-visa',
                    'last_verified_at'     => $now,
                    'benefits' => ['Work for any employer', 'Stay 2-3 years', 'Switch to Skilled Worker permit'],
                    'requirements' => [
                        'UK Degree Completion',
                        'Valid Student Visa',
                        'Inside the UK',
                    ],
                    'min_funds_required' => 3000,
                ]
            );
        }

        // ── Canada ────────────────────────────────────────────────
        $canada = Country::where('code', 'CA')->first();
        if ($canada) {
            VisaType::updateOrCreate(
                ['country_id' => $canada->id, 'name' => 'Study Permit'],
                [
                    'pathway_type'         => 'Study',
                    'description'          => 'For students accepted into a Canadian designated learning institution.',
                    'processing_time'      => '8 weeks',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://www.canada.ca/en/immigration-refugees-citizenship/services/study-canada/study-permit.html',
                    'last_verified_at'     => $now,
                    'benefits' => ['Off-campus work (20h/week)', 'PGWP eligibility', 'Spousal open work permit'],
                    'requirements' => ['Acceptance Letter (DLI)', 'Proof of Funds', 'Language Test Score', 'Biometrics'],
                    'min_funds_required' => 12000,
                    'min_ielts_score'    => 6.0,
                ]
            );
            VisaType::updateOrCreate(
                ['country_id' => $canada->id, 'name' => 'Express Entry (FSW)'],
                [
                    'pathway_type'         => 'Skilled Work',
                    'description'          => 'Federal Skilled Worker Programme for professionals.',
                    'processing_time'      => '6 months',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://www.canada.ca/en/immigration-refugees-citizenship/services/immigrate-canada/express-entry.html',
                    'last_verified_at'     => $now,
                    'benefits' => ['Immediate Permanent Residency', 'Universal healthcare', 'Citizenship pathway (3 years)'],
                    'requirements' => ['ECA Report', 'IELTS / CELPIP Score', 'Proof of Funds', 'Work Experience Proof'],
                    'min_education_level'       => 'bachelors',
                    'min_work_experience_years' => 1,
                    'min_ielts_score'           => 7.0,
                    'min_funds_required'        => 11000,
                ]
            );
            VisaType::updateOrCreate(
                ['country_id' => $canada->id, 'name' => 'Start-up Visa'],
                [
                    'pathway_type'         => 'Startup / Entrepreneur',
                    'description'          => 'For entrepreneurs with innovative business ideas.',
                    'processing_time'      => '12-16 months',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://www.canada.ca/en/immigration-refugees-citizenship/services/immigrate-canada/start-visa.html',
                    'last_verified_at'     => $now,
                    'benefits' => ['PR for up to 5 founders', 'No minimum investment required', 'Business support network'],
                    'requirements' => [
                        'Letter of Support from Designated Organization',
                        'Language Test (CLB 5)',
                        'Settlement Funds',
                        'Qualifying Business',
                    ],
                    'min_funds_required' => 20000,
                    'min_ielts_score'    => 5.0,
                ]
            );
        }

        // ── Germany ────────────────────────────────────────────────
        $germany = Country::where('code', 'DE')->first();
        if ($germany) {
            VisaType::updateOrCreate(
                ['country_id' => $germany->id, 'name' => 'Student Visa'],
                [
                    'pathway_type'         => 'Study',
                    'description'          => 'For students enrolled in German universities.',
                    'processing_time'      => '4 weeks',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://www.make-it-in-germany.com/en/visa-residence/types/studying',
                    'last_verified_at'     => $now,
                    'benefits' => ['Tuition-free public unis', 'Work 120 full days/year', '18-month job search after study'],
                    'requirements' => ['University Admission Letter', 'Proof of Funds (€11,208/year)', 'Health Insurance', 'Language Certificate'],
                    'min_funds_required'   => 12000,
                ]
            );
            VisaType::updateOrCreate(
                ['country_id' => $germany->id, 'name' => 'Opportunity Card (Chancenkarte)'],
                [
                    'pathway_type'         => 'Job Seeker',
                    'description'          => 'Points-based job search visa for 1 year.',
                    'processing_time'      => '4-8 weeks',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://www.make-it-in-germany.com/en/visa-residence/types/job-search-opportunity-card',
                    'last_verified_at'     => $now,
                    'benefits' => ['Search for jobs while in Germany', 'Part-time work allowed (20h/week)', 'Probationary work allowed'],
                    'requirements' => [
                        'Recognised Qualification',
                        'Points Score ≥ 6',
                        'B1 German or C1 English',
                        'Proof of Funds (€12,000+)',
                    ],
                    'min_education_level'       => 'high_school',
                    'min_work_experience_years' => 2,
                    'min_ielts_score'           => 5.5,
                    'min_funds_required'        => 13000,
                ]
            );
        }

        // ── Portugal ───────────────────────────────────────────────
        $portugal = Country::where('code', 'PT')->first();
        if ($portugal) {
            VisaType::updateOrCreate(
                ['country_id' => $portugal->id, 'name' => 'D7 Passive Income Visa'],
                [
                    'pathway_type'         => 'Self-Employment / Freelancer',
                    'description'          => 'For retirees or remote workers with passive/remote income.',
                    'processing_time'      => '2-3 months',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://vistos.mne.gov.pt/en/national-visas/general-information/type-of-visa',
                    'last_verified_at'     => $now,
                    'benefits' => ['Low cost of living', 'Schengen travel', 'Citizenship after 5 years', 'Tax benefits'],
                    'requirements' => ['Proof of Income (€760+/month)', 'Portuguese NIF', 'Bank Account', 'Accommodation Proof'],
                    'min_funds_required'   => 10000,
                ]
            );
            VisaType::updateOrCreate(
                ['country_id' => $portugal->id, 'name' => 'Digital Nomad Visa (D8)'],
                [
                    'pathway_type'         => 'Digital Nomad',
                    'description'          => 'For remote workers earning from outside Portugal.',
                    'processing_time'      => '2-3 months',
                    'pr_possibility'       => true,
                    'official_source_link' => 'https://vistos.mne.gov.pt/en/national-visas/general-information/type-of-visa',
                    'last_verified_at'     => $now,
                    'benefits' => ['Work remotely in Portugal', 'Schengen access', 'Renewable for 5 years'],
                    'requirements' => [
                        'Remote Contract or Freelance Contracts',
                        'Income Proof (€3,040+/month)',
                        'Accommodation Proof',
                        'Criminal Record',
                    ],
                    'min_funds_required'   => 5000,
                ]
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  COST TEMPLATES
    // ─────────────────────────────────────────────────────────────

    private function seedCostTemplates(): void
    {
        $countries = [
            'IE' => ['Ireland', 'EUR'],
            'AU' => ['Australia', 'AUD'],
            'NZ' => ['New Zealand', 'NZD'],
            'NL' => ['Netherlands', 'EUR'],
            'FR' => ['France', 'EUR'],
            'IT' => ['Italy', 'EUR'],
            'SE' => ['Sweden', 'SEK'],
            'FI' => ['Finland', 'EUR'],
            'NO' => ['Norway', 'NOK'],
            'AT' => ['Austria', 'EUR'],
        ];

        $templates = [
            'IE' => [
                ['name' => 'Student Visa Fee',         'amount' => 0,        'desc' => 'No student visa fee (stamp given on arrival for EU/EEA; others vary)'],
                ['name' => 'Registration (GNIB/IRP)',  'amount' => 300,      'desc' => 'Irish Residence Permit registration fee'],
                ['name' => 'Health Insurance',         'amount' => 500,      'desc' => 'Annual private health insurance'],
                ['name' => 'IELTS / PTE Test',         'amount' => 200,      'desc' => 'English language proficiency test'],
                ['name' => 'Tuition Fees',             'amount' => 10000,    'desc' => 'Average annual university tuition'],
                ['name' => 'Accommodation (per year)', 'amount' => 9600,     'desc' => 'Average rent €800/month in Dublin'],
                ['name' => 'Living Expenses (per year)','amount' => 7200,    'desc' => 'Food, transport, misc'],
            ],
            'AU' => [
                ['name' => 'Student Visa (Subclass 500)',   'amount' => 710,     'desc' => 'Australian student visa application fee'],
                ['name' => 'OSHC Health Insurance',         'amount' => 600,     'desc' => 'Overseas Student Health Cover per year'],
                ['name' => 'IELTS / PTE Test',              'amount' => 250,     'desc' => 'English proficiency test fee (AUD)'],
                ['name' => 'Tuition Fees',                  'amount' => 30000,   'desc' => 'Average annual university tuition'],
                ['name' => 'Accommodation (per year)',      'amount' => 14400,   'desc' => 'Average rent AUD 1,200/month'],
                ['name' => 'Living Expenses (per year)',    'amount' => 10000,   'desc' => 'AUD 21,041 proof of funds requirement'],
                ['name' => 'Skills Assessment Fee',         'amount' => 500,     'desc' => 'For skilled migration pathways'],
            ],
            'NZ' => [
                ['name' => 'Student Visa Fee',          'amount' => 330,     'desc' => 'NZD student visa application fee'],
                ['name' => 'Health & Travel Insurance', 'amount' => 400,     'desc' => 'Annual insurance coverage (NZD)'],
                ['name' => 'IELTS / PTE Test',          'amount' => 350,     'desc' => 'English proficiency test (NZD)'],
                ['name' => 'Tuition Fees',              'amount' => 22000,   'desc' => 'Average annual university tuition (NZD)'],
                ['name' => 'Accommodation (per year)',  'amount' => 13200,   'desc' => 'Average rent NZD 1,100/month'],
                ['name' => 'Proof of Funds Required',  'amount' => 15000,   'desc' => 'NZD 15,000 minimum proof of funds'],
            ],
            'NL' => [
                ['name' => 'Visa / MVV Fee',            'amount' => 210,     'desc' => 'Dutch student visa application fee'],
                ['name' => 'Residence Permit (VVR)',    'amount' => 210,     'desc' => 'Dutch residence permit fee'],
                ['name' => 'Health Insurance',          'amount' => 1200,    'desc' => 'Annual health insurance (~€100/month)'],
                ['name' => 'Tuition Fees',              'amount' => 14000,   'desc' => 'Average annual university tuition (EUR)'],
                ['name' => 'Accommodation (per year)',  'amount' => 12000,   'desc' => 'Average rent €1,000/month in Dutch cities'],
                ['name' => 'Proof of Funds Required',  'amount' => 13000,   'desc' => '€13,000 minimum financial requirement'],
            ],
            'FR' => [
                ['name' => 'Long-Stay Visa Fee',        'amount' => 99,      'desc' => 'French long-stay visa fee'],
                ['name' => 'Campus France Fee',         'amount' => 150,     'desc' => 'French procedure fee'],
                ['name' => 'OFII Stamp Fee',            'amount' => 200,     'desc' => 'Validation fee for residence permit'],
                ['name' => 'Tuition (Public)',          'amount' => 3770,    'desc' => 'Annual public university tuition'],
                ['name' => 'Accommodation (per year)', 'amount' => 9600,    'desc' => 'Average rent €800/month'],
                ['name' => 'Proof of Funds (per year)','amount' => 7380,    'desc' => '€615/month minimum requirement'],
                ['name' => 'Health Insurance',          'amount' => 600,     'desc' => 'Annual student health coverage'],
            ],
            'IT' => [
                ['name' => 'Student Visa Fee',          'amount' => 50,      'desc' => 'Italian national student visa fee'],
                ['name' => 'Residence Permit (SdS)',    'amount' => 100,     'desc' => 'Permesso di Soggiorno fee'],
                ['name' => 'Tuition Fees',              'amount' => 2000,    'desc' => 'Average public university tuition (EUR)'],
                ['name' => 'Accommodation (per year)', 'amount' => 8400,    'desc' => 'Average rent €700/month'],
                ['name' => 'Proof of Funds Required',  'amount' => 6500,    'desc' => 'Annual financial support required'],
                ['name' => 'Health Insurance',          'amount' => 500,     'desc' => 'Annual private health insurance'],
            ],
            'SE' => [
                ['name' => 'Residence Permit Fee',      'amount' => 1500,    'desc' => 'SEK Swedish Migration Agency fee'],
                ['name' => 'Tuition Fees',              'amount' => 140000,  'desc' => 'Average annual tuition in SEK (~€12,000)'],
                ['name' => 'Accommodation (per year)', 'amount' => 96000,   'desc' => 'Average SEK 8,000/month'],
                ['name' => 'Health Insurance',          'amount' => 5000,    'desc' => 'Supplemental health coverage (SEK)'],
                ['name' => 'Proof of Funds (monthly)', 'amount' => 10314,   'desc' => 'SEK 10,314/month required'],
            ],
            'FI' => [
                ['name' => 'Residence Permit Fee',      'amount' => 350,     'desc' => 'Finnish Immigration Service fee (EUR)'],
                ['name' => 'Tuition Fees',              'amount' => 13000,   'desc' => 'Average annual tuition for non-EU students'],
                ['name' => 'Accommodation (per year)', 'amount' => 9600,    'desc' => 'Average rent €800/month'],
                ['name' => 'Proof of Funds (monthly)', 'amount' => 560,     'desc' => '€560/month minimum requirement'],
                ['name' => 'Health Insurance',          'amount' => 600,     'desc' => 'Annual insurance coverage'],
            ],
            'NO' => [
                ['name' => 'Residence Permit Fee',      'amount' => 5900,    'desc' => 'Norwegian UDI application fee (NOK)'],
                ['name' => 'Tuition Fees',              'amount' => 0,       'desc' => 'Free at most public universities (NOK)'],
                ['name' => 'Accommodation (per year)', 'amount' => 144000,  'desc' => 'Average NOK 12,000/month'],
                ['name' => 'Proof of Funds Required',  'amount' => 137907,  'desc' => 'NOK 137,907/year minimum'],
                ['name' => 'Health Insurance',          'amount' => 5000,    'desc' => 'Travel & health insurance (NOK)'],
            ],
            'AT' => [
                ['name' => 'Visa / Residence Fee',      'amount' => 160,     'desc' => 'Austrian residence permit application fee'],
                ['name' => 'Long-Stay Visa Fee',        'amount' => 150,     'desc' => 'Austrian D Visa fee if applying abroad'],
                ['name' => 'Tuition Fees',              'amount' => 1452,    'desc' => '€726 per semester at public universities'],
                ['name' => 'Accommodation (per year)', 'amount' => 12000,   'desc' => 'Average rent €1,000/month in Vienna'],
                ['name' => 'Proof of Funds Required',  'amount' => 11000,   'desc' => 'Annual financial proof requirement'],
                ['name' => 'Health Insurance',          'amount' => 700,     'desc' => 'Annual health insurance coverage'],
            ],
        ];

        foreach ($templates as $code => $items) {
            $country = Country::where('code', $code)->first();
            if (!$country) continue;

            $currency = $countries[$code][1];
            foreach ($items as $item) {
                CostItem::updateOrCreate(
                    ['name' => $item['name'], 'country_id' => $country->id, 'visa_type_id' => null],
                    [
                        'amount'       => $item['amount'],
                        'currency'     => $currency,
                        'description'  => $item['desc'],
                        'is_mandatory' => true,
                    ]
                );
            }
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  DOCUMENT TYPES (Global Additions)
    // ─────────────────────────────────────────────────────────────

    private function seedDocumentTypes(): void
    {
        $docs = [
            ['name' => 'Admission / Acceptance Letter',   'description' => 'Official letter from university or institution confirming enrolment'],
            ['name' => 'Motivation / Personal Statement', 'description' => 'Essay explaining purpose of study or immigration'],
            ['name' => 'Medical Examination Certificate', 'description' => 'Health clearance from approved physician'],
            ['name' => 'Accommodation Proof',             'description' => 'Rental contract or university accommodation offer'],
            ['name' => 'Health Insurance Certificate',    'description' => 'Valid insurance covering the period of stay'],
            ['name' => 'Tuberculosis (TB) Test Result',   'description' => 'Required by UK and some other countries'],
            ['name' => 'Sponsor Letter',                  'description' => 'Letter from sponsor guaranteeing financial support'],
            ['name' => 'Certificate of Sponsorship (CoS)','description' => 'UK employer-issued reference number for Skilled Worker visa'],
            ['name' => 'Blocked Account Certificate',     'description' => 'German proof of funds via blocked account (Sperrkonto)'],
            ['name' => 'Credential Evaluation (WES/ECA)','description' => 'International recognition of academic qualifications'],
            ['name' => 'Employment Contract / Job Offer', 'description' => 'Signed offer of employment from a licensed employer'],
            ['name' => 'National Identity Card',          'description' => 'Valid national identity document (EU citizens)'],
            ['name' => 'Biometrics Appointment',          'description' => 'Fingerprints and photo for visa processing'],
            ['name' => 'Proof of Marital Status',         'description' => 'Marriage certificate or single status certificate'],
            ['name' => 'Curriculum Vitae (CV)',            'description' => 'Professional resume detailing work history and skills'],
            ['name' => 'Campus France Attestation',       'description' => 'Required for French student visa applicants'],
            ['name' => 'Universitaly Pre-enrollment',     'description' => 'Required online pre-enrollment for Italian student visa'],
        ];

        foreach ($docs as $doc) {
            DocumentType::updateOrCreate(
                ['name' => $doc['name']],
                ['description' => $doc['description'], 'visa_type_id' => null]
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  RELOCATION KITS (Arrival Checklists)
    // ─────────────────────────────────────────────────────────────

    private function seedRelocationKits(): void
    {
        $kitData = [
            'AU' => [
                'title' => 'First 30 Days in Australia',
                'desc'  => 'Essential checklist for your first month after landing in Australia.',
                'items' => [
                    ['title' => 'Get your Tax File Number (TFN)', 'content' => 'Apply online at the ATO website. You need this to work legally in Australia and to open financial accounts.', 'premium' => false],
                    ['title' => 'Open an Australian Bank Account', 'content' => 'Major banks (CommBank, ANZ, Westpac, NAB) offer newcomer accounts.', 'premium' => false],
                    ['title' => 'Register with Medicare (if eligible)', 'content' => 'Students from reciprocal agreement countries can access Medicare.', 'premium' => false],
                    ['title' => 'Get an Australian SIM card', 'content' => 'Telstra, Optus, and Vodafone are the main carriers.', 'premium' => false],
                    ['title' => 'Arrange Accommodation', 'content' => 'Inspect the property before signing the lease.', 'premium' => true],
                    ['title' => 'Bond & Rental Ledger Setup', 'content' => 'Your bond must be lodged with the state authority.', 'premium' => true],
                ],
            ],
            'IE' => [
                'title' => 'First 30 Days in Ireland',
                'desc'  => 'Your arrival checklist for settling into Ireland successfully.',
                'items' => [
                    ['title' => 'Register with your local Garda Station (IRP)', 'content' => 'All non-EEA nationals must register within 90 days of arrival.', 'premium' => false],
                    ['title' => 'Get a PPS Number', 'content' => 'Your Personal Public Service number is needed for work, tax, and social services.', 'premium' => false],
                    ['title' => 'Open an Irish Bank Account', 'content' => 'AIB, Bank of Ireland, and Revolut are popular options.', 'premium' => false],
                    ['title' => 'Register with a GP', 'content' => 'Find a local General Practitioner.', 'premium' => false],
                    ['title' => 'Understand Irish Rental Market', 'content' => 'Ireland has a very competitive rental market. Use Daft.ie.', 'premium' => true],
                ],
            ],
            'NZ' => [
                'title' => 'First 30 Days in New Zealand',
                'desc'  => 'Your essential guide to settling into New Zealand.',
                'items' => [
                    ['title' => 'Get an IRD Number', 'content' => 'Inland Revenue Department number required for employment and tax purposes.', 'premium' => false],
                    ['title' => 'Open a NZ Bank Account', 'content' => 'ANZ, ASB, BNZ, and Westpac are the major banks.', 'premium' => false],
                    ['title' => 'Register with a GP', 'content' => 'Find a local GP practice and enrol as soon as possible.', 'premium' => false],
                    ['title' => 'Get a NZ SIM Card', 'content' => 'Spark, One NZ, and 2degrees are the main providers.', 'premium' => false],
                    ['title' => 'Understand NZ Tenancy Rights', 'content' => 'The Residential Tenancies Act protects you as a tenant.', 'premium' => true],
                ],
            ],
            'NL' => [
                'title' => 'First 30 Days in the Netherlands',
                'desc'  => 'Your arrival checklist for the Netherlands.',
                'items' => [
                    ['title' => 'Register at the Municipality (BRP)', 'content' => 'Within 5 days of arrival, register for your BSN (citizen service number).', 'premium' => false],
                    ['title' => 'Get your BSN Number', 'content' => 'Your Burgerservicenummer is required for employment, banking, and healthcare.', 'premium' => false],
                    ['title' => 'Open a Dutch Bank Account', 'content' => 'ING, ABN AMRO, and Rabobank are major options.', 'premium' => false],
                    ['title' => 'Arrange Dutch Health Insurance', 'content' => 'All residents must have Dutch basic health insurance within 4 months.', 'premium' => false],
                    ['title' => 'Navigate Dutch Housing', 'content' => 'The Dutch rental market is highly competitive. Use Pararius or Funda.', 'premium' => true],
                ],
            ],
            'FR' => [
                'title' => 'First 30 Days in France',
                'desc'  => 'Your essential guide to settling into France.',
                'items' => [
                    ['title' => 'Validate your Visa (OFII)', 'content' => 'Within 3 months of arrival, validate your long-stay visa online.', 'premium' => false],
                    ['title' => 'Open a French Bank Account', 'content' => 'BNP Paribas, Société Générale, and Crédit Agricole are major banks.', 'premium' => false],
                    ['title' => 'Register for Social Security', 'content' => 'Register for the French health insurance system (Assurance Maladie).', 'premium' => false],
                    ['title' => 'Find Your Local CAF Office', 'content' => 'The CAF provides housing assistance (APL) for students and workers.', 'premium' => false],
                    ['title' => 'French Rental Market', 'content' => 'French landlords often require a guarantor (garant).', 'premium' => true],
                ],
            ],
        ];

        foreach ($kitData as $code => $kit) {
            $country = Country::where('code', $code)->first();
            if (!$country) continue;

            $arrivalKit = RelocationKit::updateOrCreate(
                ['country_id'  => $country->id, 'title' => $kit['title']],
                ['description' => $kit['desc'], 'icon' => 'home', 'is_premium' => false, 'order' => 1]
            );

            foreach ($kit['items'] as $i => $item) {
                RelocationKitItem::updateOrCreate(
                    ['relocation_kit_id' => $arrivalKit->id, 'title' => $item['title']],
                    ['content' => $item['content'], 'is_premium' => $item['premium'], 'order' => $i + 1]
                );
            }
        }
    }
}
