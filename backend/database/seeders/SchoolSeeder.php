<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\School;
use App\Models\SchoolProgram;
use App\Models\StudentVisaRequirement;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $data = $this->getData();

        foreach ($data as $countryCode => $info) {
            $country = Country::where('code', $countryCode)->first();
            if (!$country)
                continue;

            // Seed student visa requirement
            if (!empty($info['visa'])) {
                StudentVisaRequirement::updateOrCreate(
                ['country_id' => $country->id],
                    array_merge($info['visa'], ['country_id' => $country->id])
                );
            }

            // Seed schools and programs
            foreach ($info['schools'] as $schoolData) {
                $programs = $schoolData['programs'] ?? [];
                unset($schoolData['programs']);

                $school = School::updateOrCreate(
                ['country_id' => $country->id, 'name' => $schoolData['name']],
                    array_merge($schoolData, ['country_id' => $country->id])
                );

                foreach ($programs as $programData) {
                    SchoolProgram::updateOrCreate(
                    ['school_id' => $school->id, 'name' => $programData['name']],
                        array_merge($programData, ['school_id' => $school->id])
                    );
                }
            }

            $this->command->info("Seeded schools for: {$country->name}");
        }
    }

    private function getData(): array
    {
        return [
            'GB' => [
                'visa' => [
                    'visa_name' => 'Student Visa (Tier 4)',
                    'visa_fee' => 363,
                    'visa_fee_currency' => 'GBP',
                    'processing_time' => '3–8 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 9135,
                    'min_funds_currency' => 'GBP',
                    'min_funds_description' => 'per year for living costs',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '2 years (Graduate Route)',
                    'required_documents' => ['Passport', 'Confirmation of Acceptance for Studies (CAS)', 'Proof of funds', 'IELTS certificate', 'Tuberculosis test results'],
                    'notes' => 'You must have a CAS from a licensed sponsor before applying.',
                ],
                'schools' => [
                    [
                        'name' => 'University of London',
                        'location' => 'London, England',
                        'type' => 'public',
                        'ranking' => 'Top 50 QS World',
                        'website' => 'https://london.ac.uk',
                        'application_portal' => 'https://london.ac.uk/apply',
                        'description' => 'A collegiate research university and one of the largest in the UK.',
                        'programs' => [
                            ['name' => 'MSc Computer Science', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 1, 'tuition_per_year' => 21000, 'currency' => 'GBP', 'application_deadline' => 'June 30', 'intake_periods' => ['Fall'], 'min_gpa' => 3.0, 'ielts_min' => 6.5, 'admission_requirements' => ['Bachelor degree in CS or related', 'Two references', 'Personal statement']],
                            ['name' => 'BSc Business Administration', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3, 'tuition_per_year' => 16000, 'currency' => 'GBP', 'application_deadline' => 'January 15 (UCAS)', 'intake_periods' => ['Fall'], 'min_gpa' => 2.8, 'ielts_min' => 6.0, 'admission_requirements' => ['A-levels or equivalent', 'UCAS application']],
                        ],
                    ],
                    [
                        'name' => 'University of Edinburgh',
                        'location' => 'Edinburgh, Scotland',
                        'type' => 'public',
                        'ranking' => 'Top 30 QS World',
                        'website' => 'https://ed.ac.uk',
                        'application_portal' => 'https://ed.ac.uk/studying-here/applying',
                        'description' => 'One of the oldest and most prestigious universities in the world.',
                        'programs' => [
                            ['name' => 'MA International Business', 'degree_type' => 'master', 'field_of_study' => 'Business', 'duration_years' => 1, 'tuition_per_year' => 26500, 'currency' => 'GBP', 'application_deadline' => 'April 30', 'intake_periods' => ['Fall'], 'ielts_min' => 7.0, 'admission_requirements' => ['Strong undergraduate degree', 'Work experience preferred']],
                            ['name' => 'BSc Medicine', 'degree_type' => 'bachelor', 'field_of_study' => 'Medicine', 'duration_years' => 6, 'tuition_per_year' => 36700, 'currency' => 'GBP', 'application_deadline' => 'October 15 (UCAS)', 'intake_periods' => ['Fall'], 'min_gpa' => 3.8, 'ielts_min' => 7.5, 'admission_requirements' => ['Strong sciences A-levels', 'UKCAT/BMAT test', 'Interview']],
                        ],
                    ],
                    [
                        'name' => 'Manchester Metropolitan University',
                        'location' => 'Manchester, England',
                        'type' => 'public',
                        'ranking' => 'Top 800 QS',
                        'website' => 'https://mmu.ac.uk',
                        'application_portal' => 'https://mmu.ac.uk/study',
                        'description' => 'A leading modern university with strong industry links.',
                        'programs' => [
                            ['name' => 'BSc Nursing', 'degree_type' => 'bachelor', 'field_of_study' => 'Nursing', 'duration_years' => 3, 'tuition_per_year' => 15000, 'currency' => 'GBP', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 6.5, 'admission_requirements' => ['5 GCSEs', 'DBS check', 'Occupational health check']],
                        ],
                    ],
                ],
            ],
            'CA' => [
                'visa' => [
                    'visa_name' => 'Canadian Study Permit',
                    'visa_fee' => 150,
                    'visa_fee_currency' => 'CAD',
                    'processing_time' => '4–12 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 10000,
                    'min_funds_currency' => 'CAD',
                    'min_funds_description' => 'per year for living expenses',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => 'Up to 3 years (PGWP)',
                    'required_documents' => ['Passport', 'Letter of Acceptance', 'Proof of funds', 'IELTS/TOEFL results', 'Statement of Purpose', 'Biometrics'],
                    'notes' => 'Post-Graduation Work Permit (PGWP) allows graduates to work in Canada.',
                ],
                'schools' => [
                    [
                        'name' => 'University of Toronto',
                        'location' => 'Toronto, Ontario',
                        'type' => 'public',
                        'ranking' => 'Top 30 QS World',
                        'website' => 'https://utoronto.ca',
                        'application_portal' => 'https://future.utoronto.ca/apply/',
                        'description' => 'Canada\'s top-ranked university, known for research and innovation.',
                        'programs' => [
                            ['name' => 'MSc Artificial Intelligence', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 26000, 'currency' => 'CAD', 'application_deadline' => 'December 1', 'intake_periods' => ['Fall'], 'min_gpa' => 3.5, 'ielts_min' => 7.0, 'toefl_min' => 93, 'admission_requirements' => ['BSc in CS or Engineering', 'Research proposal', 'Two reference letters']],
                            ['name' => 'Bachelor of Commerce', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 4, 'tuition_per_year' => 47600, 'currency' => 'CAD', 'application_deadline' => 'February 1', 'intake_periods' => ['Fall'], 'min_gpa' => 3.3, 'ielts_min' => 6.5, 'admission_requirements' => ['High school diploma', 'English proficiency', 'Supplementary application']],
                        ],
                    ],
                    [
                        'name' => 'University of British Columbia',
                        'location' => 'Vancouver, British Columbia',
                        'type' => 'public',
                        'ranking' => 'Top 50 QS World',
                        'website' => 'https://ubc.ca',
                        'application_portal' => 'https://you.ubc.ca/applying-ubc/',
                        'description' => 'A globally recognized research university on Canada\'s Pacific coast.',
                        'programs' => [
                            ['name' => 'MEng Electrical Engineering', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 1.5, 'tuition_per_year' => 24000, 'currency' => 'CAD', 'application_deadline' => 'March 1', 'intake_periods' => ['Fall', 'Spring'], 'min_gpa' => 3.0, 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in Engineering', 'References', 'Statement of intent']],
                            ['name' => 'BSc Nursing', 'degree_type' => 'bachelor', 'field_of_study' => 'Nursing', 'duration_years' => 4, 'tuition_per_year' => 8000, 'currency' => 'CAD', 'application_deadline' => 'February 28', 'intake_periods' => ['Fall'], 'min_gpa' => 3.2, 'ielts_min' => 7.0, 'admission_requirements' => ['Biology and Chemistry prerequisites', 'Volunteer experience in healthcare']],
                        ],
                    ],
                    [
                        'name' => 'Seneca College',
                        'location' => 'Toronto, Ontario',
                        'type' => 'college',
                        'ranking' => 'Top Ontario College',
                        'website' => 'https://senecapolytechnic.ca',
                        'application_portal' => 'https://www.ontariocolleges.ca',
                        'description' => 'A leading polytechnic offering career-focused programs.',
                        'programs' => [
                            ['name' => 'Diploma in Business Administration', 'degree_type' => 'diploma', 'field_of_study' => 'Business', 'duration_years' => 2, 'tuition_per_year' => 15000, 'currency' => 'CAD', 'intake_periods' => ['Fall', 'Winter', 'Spring'], 'ielts_min' => 6.0, 'admission_requirements' => ['High school diploma', 'English proficiency test']],
                            ['name' => 'Diploma in Information Technology', 'degree_type' => 'diploma', 'field_of_study' => 'IT', 'duration_years' => 2, 'tuition_per_year' => 15500, 'currency' => 'CAD', 'intake_periods' => ['Fall', 'Winter'], 'ielts_min' => 6.0, 'admission_requirements' => ['High school diploma', 'Math prerequisite']],
                        ],
                    ],
                ],
            ],
            'DE' => [
                'visa' => [
                    'visa_name' => 'German Student Visa (National D Visa)',
                    'visa_fee' => 75,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '4–12 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 11208,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year (blocked account requirement)',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '18 months job-seeker visa',
                    'required_documents' => ['Passport', 'University admission letter', 'Blocked account proof (€934/month)', 'Health insurance proof', 'Academic certificates with German translation', 'Language certificate (B2/C1 German or IELTS for English programs)'],
                    'notes' => 'Many public universities have no tuition fees. Blocked account required.',
                ],
                'schools' => [
                    [
                        'name' => 'Technical University of Munich (TUM)',
                        'location' => 'Munich, Bavaria',
                        'type' => 'public',
                        'ranking' => 'Top 50 QS World',
                        'website' => 'https://tum.de',
                        'application_portal' => 'https://portal.mytum.de',
                        'description' => 'Germany\'s top technical university, ranked globally for engineering.',
                        'programs' => [
                            ['name' => 'MSc Robotics, Cognition, Intelligence', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 0, 'currency' => 'EUR', 'application_deadline' => 'May 31', 'intake_periods' => ['Fall'], 'min_gpa' => 3.0, 'ielts_min' => 7.0, 'admission_requirements' => ['BSc in Engineering or Computer Science', 'GRE (optional)', 'Two references']],
                            ['name' => 'MSc Data Engineering and Analytics', 'degree_type' => 'master', 'field_of_study' => 'Data Science', 'duration_years' => 2, 'tuition_per_year' => 0, 'currency' => 'EUR', 'application_deadline' => 'April 30', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['Strong math and programming background']],
                        ],
                    ],
                    [
                        'name' => 'Heidelberg University',
                        'location' => 'Heidelberg, Baden-Württemberg',
                        'type' => 'public',
                        'ranking' => 'Top 70 QS World',
                        'website' => 'https://uni-heidelberg.de',
                        'application_portal' => 'https://www.uni-heidelberg.de/courses/prospective/',
                        'description' => 'Germany\'s oldest university with strong humanities and sciences.',
                        'programs' => [
                            ['name' => 'MSc Molecular Biosciences', 'degree_type' => 'master', 'field_of_study' => 'Biology', 'duration_years' => 2, 'tuition_per_year' => 0, 'currency' => 'EUR', 'application_deadline' => 'March 15', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in Biology or related', 'Research experience preferred']],
                        ],
                    ],
                    [
                        'name' => 'Berlin International University of Applied Sciences',
                        'location' => 'Berlin',
                        'type' => 'private',
                        'ranking' => null,
                        'website' => 'https://berlin-international.de',
                        'application_portal' => 'https://berlin-international.de/apply',
                        'description' => 'Private university offering English-taught programs in Berlin.',
                        'programs' => [
                            ['name' => 'Bachelor of Business Administration (English)', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3.5, 'tuition_per_year' => 6700, 'currency' => 'EUR', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 6.0, 'admission_requirements' => ['High school certificate', 'English proficiency']],
                        ],
                    ],
                ],
            ],
            'PT' => [
                'visa' => [
                    'visa_name' => 'Portuguese Student Visa (National Visa Type D)',
                    'visa_fee' => 90,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '4–8 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 760,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per month (national minimum wage)',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1 year job-seeker visa',
                    'required_documents' => ['Passport', 'University enrolment letter', 'Proof of accommodation', 'Proof of funds', 'Health insurance', 'Academic certificates'],
                ],
                'schools' => [
                    ['name' => 'University of Lisbon', 'location' => 'Lisbon', 'type' => 'public', 'ranking' => 'Top 350 QS', 'website' => 'https://ulisboa.pt', 'application_portal' => 'https://www.ulisboa.pt/en/candidatos', 'description' => 'Portugal\'s largest university offering wide range of programmes.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-08-31', 'programs' => [
                            ['name' => 'MSc Management', 'degree_type' => 'master', 'field_of_study' => 'Business', 'duration_years' => 2, 'tuition_per_year' => 5000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.0, 'admission_requirements' => ['Bachelor degree in Management or Economics']],
                            ['name' => 'BSc Computer Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Computer Science', 'duration_years' => 3, 'tuition_per_year' => 1500, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'admission_requirements' => ['Secondary education certificate', 'National admission exam']],
                        ]],
                    ['name' => 'Nova University Lisbon', 'location' => 'Lisbon', 'type' => 'public', 'ranking' => 'Top 500 QS', 'website' => 'https://unl.pt', 'application_portal' => 'https://www.unl.pt/en', 'description' => 'Young and dynamic university known for Nova SBE business school.', 'admission_opening_date' => '2026-02-15', 'admission_deadline_date' => '2026-06-30', 'programs' => [
                            ['name' => 'MSc Finance', 'degree_type' => 'master', 'field_of_study' => 'Finance', 'duration_years' => 1.5, 'tuition_per_year' => 9500, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['Bachelor in Business, Economics or related']],
                        ]],
                    ['name' => 'University of Porto', 'location' => 'Porto', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://up.pt', 'application_portal' => 'https://sigarra.up.pt/up/en/', 'description' => 'One of Portugal\'s top research universities located in the north.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-05-31', 'programs' => [
                            ['name' => 'MSc Electrical Engineering', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 4500, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5],
                        ]],
                    ['name' => 'University of Coimbra', 'location' => 'Coimbra', 'type' => 'public', 'ranking' => 'Top 450 QS', 'website' => 'https://uc.pt', 'application_portal' => 'https://www.uc.pt/en/applications', 'description' => 'UNESCO World Heritage university and one of the oldest in the world.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-07-15'],
                    ['name' => 'University of Aveiro', 'location' => 'Aveiro', 'type' => 'public', 'ranking' => 'Top 600 QS', 'website' => 'https://ua.pt', 'application_portal' => 'https://www.ua.pt/en/applications', 'description' => 'Modern university known for its commitment to R&D and industry.', 'admission_opening_date' => '2026-03-15', 'admission_deadline_date' => '2026-08-15'],
                    ['name' => 'University of Minho', 'location' => 'Braga & Guimarães', 'type' => 'public', 'ranking' => 'Top 600 QS', 'website' => 'https://uminho.pt', 'application_portal' => 'https://www.uminho.pt/en/study', 'description' => 'Strong regional university with excellent engineering and social sciences.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-06-30'],
                    ['name' => 'ISCTE - University Institute of Lisbon', 'location' => 'Lisbon', 'type' => 'public', 'ranking' => 'Top 800 QS', 'website' => 'https://iscte-iul.pt', 'application_portal' => 'https://www.iscte-iul.pt/en/', 'description' => 'Specialized in business, social sciences, and technology.', 'admission_opening_date' => '2026-01-15', 'admission_deadline_date' => '2026-05-15'],
                ],
            ],
            'ES' => [
                'visa' => [
                    'visa_name' => 'Spanish Student Visa (Visado de estudios)',
                    'visa_fee' => 80,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '4–8 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 7200,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year (€600/month)',
                    'work_hours_per_week' => 30,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1 year job-seeker permit',
                    'required_documents' => ['Passport', 'University admission letter', 'Proof of funds', 'Health insurance', 'Academic records', 'Criminal record certificate'],
                ],
                'schools' => [
                    ['name' => 'IE University', 'location' => 'Madrid & Segovia', 'type' => 'private', 'ranking' => 'Top 300 QS', 'website' => 'https://ie.edu', 'application_portal' => 'https://www.ie.edu/university/apply/', 'description' => 'Spain\'s globally renowned private university for business and technology.', 'programs' => [
                            ['name' => 'Master in Business Administration (MBA)', 'degree_type' => 'master', 'field_of_study' => 'Business', 'duration_years' => 1, 'tuition_per_year' => 65000, 'currency' => 'EUR', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 7.0, 'admission_requirements' => ['Bachelor degree', '3+ years work experience', 'GMAT']],
                        ]],
                    ['name' => 'University of Barcelona', 'location' => 'Barcelona', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://ub.edu', 'application_portal' => 'https://web.ub.edu/en/web/estudis/masters', 'description' => 'One of Spain\'s most prestigious public universities.', 'programs' => [
                            ['name' => 'MSc Bioinformatics', 'degree_type' => 'master', 'field_of_study' => 'Biology', 'duration_years' => 1.5, 'tuition_per_year' => 3500, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.0, 'admission_requirements' => ['BSc in Biology, Chemistry or CS']],
                        ]],
                ],
            ],
            'IE' => [
                'visa' => [
                    'visa_name' => 'Irish Study Visa',
                    'visa_fee' => 60,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '4–8 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 7000,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year minimum',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1–2 years (Third Level Graduate Scheme)',
                    'required_documents' => ['Passport', 'Letter of Offer from Irish institution', 'Proof of funds', 'Health insurance', 'Academic transcripts'],
                ],
                'schools' => [
                    ['name' => 'University College Dublin', 'location' => 'Dublin', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://ucd.ie', 'application_portal' => 'https://www.ucd.ie/study/', 'description' => 'Ireland\'s largest and most internationally diverse university.', 'programs' => [
                            ['name' => 'MSc Data Analytics', 'degree_type' => 'master', 'field_of_study' => 'Data Science', 'duration_years' => 1, 'tuition_per_year' => 20000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['Strong quantitative background', 'Programming experience']],
                            ['name' => 'BSc Computer Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Computer Science', 'duration_years' => 4, 'tuition_per_year' => 16000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.0, 'admission_requirements' => ['High school certificate', 'Math proficiency']],
                        ]],
                    ['name' => 'Trinity College Dublin', 'location' => 'Dublin', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://tcd.ie', 'application_portal' => 'https://www.tcd.ie/courses/', 'description' => 'Ireland\'s oldest and most prestigious university founded in 1592.', 'programs' => [
                            ['name' => 'MSc Finance', 'degree_type' => 'master', 'field_of_study' => 'Finance', 'duration_years' => 1, 'tuition_per_year' => 22000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 7.0, 'admission_requirements' => ['Bachelor in Finance or Economics', 'GMAT optional']],
                        ]],
                ],
            ],
            'AU' => [
                'visa' => [
                    'visa_name' => 'Student Visa (Subclass 500)',
                    'visa_fee' => 650,
                    'visa_fee_currency' => 'AUD',
                    'processing_time' => '1–6 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 21041,
                    'min_funds_currency' => 'AUD',
                    'min_funds_description' => 'per year for living costs',
                    'work_hours_per_week' => 48,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '2–4 years (Temporary Graduate Visa Subclass 485)',
                    'required_documents' => ['Passport', 'Confirmation of Enrolment (CoE)', 'Genuine Temporary Entrant (GTE) statement', 'IELTS/PTE results', 'Financial evidence', 'Overseas Student Health Cover (OSHC)'],
                ],
                'schools' => [
                    ['name' => 'University of Sydney', 'location' => 'Sydney, NSW', 'type' => 'public', 'ranking' => 'Top 50 QS', 'website' => 'https://sydney.edu.au', 'application_portal' => 'https://sydney.edu.au/study/how-to-apply.html', 'description' => 'Australia\'s first university, globally ranked for research and teaching.', 'programs' => [
                            ['name' => 'Master of Information Technology', 'degree_type' => 'master', 'field_of_study' => 'IT', 'duration_years' => 1.5, 'tuition_per_year' => 40500, 'currency' => 'AUD', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 6.5, 'pte_min' => 58, 'admission_requirements' => ['Bachelor in IT or related', 'Research statement']],
                            ['name' => 'Bachelor of Nursing', 'degree_type' => 'bachelor', 'field_of_study' => 'Nursing', 'duration_years' => 3, 'tuition_per_year' => 38000, 'currency' => 'AUD', 'intake_periods' => ['Fall'], 'ielts_min' => 7.0, 'pte_min' => 65, 'admission_requirements' => ['High school certificate', 'English proficiency', 'Police check']],
                        ]],
                    ['name' => 'RMIT University', 'location' => 'Melbourne, VIC', 'type' => 'public', 'ranking' => 'Top 250 QS', 'website' => 'https://rmit.edu.au', 'application_portal' => 'https://www.rmit.edu.au/study-with-us/international-students/apply', 'description' => 'Practice-based university known for design, technology and business.', 'programs' => [
                            ['name' => 'Diploma of IT', 'degree_type' => 'diploma', 'field_of_study' => 'IT', 'duration_years' => 1, 'tuition_per_year' => 22500, 'currency' => 'AUD', 'intake_periods' => ['Fall', 'Spring', 'Summer'], 'ielts_min' => 6.0, 'admission_requirements' => ['High school completion']],
                        ]],
                ],
            ],
            'NZ' => [
                'visa' => [
                    'visa_name' => 'New Zealand Student Visa',
                    'visa_fee' => 375,
                    'visa_fee_currency' => 'NZD',
                    'processing_time' => '3–5 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 15000,
                    'min_funds_currency' => 'NZD',
                    'min_funds_description' => 'per year for living costs',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => 'Up to 3 years',
                    'required_documents' => ['Passport', 'Offer of Place letter', 'Proof of funds', 'Return ticket or funds for departure', 'Health and character requirements'],
                ],
                'schools' => [
                    ['name' => 'University of Auckland', 'location' => 'Auckland', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://auckland.ac.nz', 'application_portal' => 'https://www.auckland.ac.nz/en/study/applying-to-auckland.html', 'description' => 'New Zealand\'s leading research-intensive university.', 'admission_opening_date' => '2026-05-01', 'admission_deadline_date' => '2026-12-01', 'programs' => [
                            ['name' => 'Master of Engineering', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 36000, 'currency' => 'NZD', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 6.5, 'admission_requirements' => ['Relevant engineering degree']],
                            ['name' => 'Bachelor of Commerce', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3, 'tuition_per_year' => 31500, 'currency' => 'NZD', 'intake_periods' => ['Fall'], 'ielts_min' => 6.0, 'admission_requirements' => ['High school certificate']],
                        ]],
                    ['name' => 'University of Otago', 'location' => 'Dunedin', 'type' => 'public', 'ranking' => 'Top 250 QS', 'website' => 'https://otago.ac.nz', 'application_portal' => 'https://www.otago.ac.nz/study/apply/', 'description' => 'The first university in NZ, famous for its campus life and sciences.', 'admission_opening_date' => '2026-01-15', 'admission_deadline_date' => '2026-10-31', 'programs' => [
                            ['name' => 'MSc Environmental Science', 'degree_type' => 'master', 'field_of_study' => 'Science', 'duration_years' => 2, 'tuition_per_year' => 34000, 'currency' => 'NZD', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5],
                        ]],
                    ['name' => 'University of Canterbury', 'location' => 'Christchurch', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://canterbury.ac.nz', 'application_portal' => 'https://www.canterbury.ac.nz/en/study/apply/', 'description' => 'Renowned for engineering, science, and forestry.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-10-15'],
                    ['name' => 'Victoria University of Wellington', 'location' => 'Wellington', 'type' => 'public', 'ranking' => 'Top 250 QS', 'website' => 'https://wgtn.ac.nz', 'application_portal' => 'https://www.wgtn.ac.nz/international/applying', 'description' => 'Located in the capital city, strong in law and social sciences.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-11-01'],
                    ['name' => 'Massey University', 'location' => 'Palmerston North', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://massey.ac.nz', 'application_portal' => 'https://www.massey.ac.nz/study/apply-to-study/', 'description' => 'Famous for veterinary science, agriculture, and distance learning.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-11-15'],
                    ['name' => 'Lincoln University', 'location' => 'Lincoln, Canterbury', 'type' => 'public', 'ranking' => 'Top 400 QS', 'website' => 'https://lincoln.ac.nz', 'application_portal' => 'https://www.lincoln.ac.nz/apply', 'description' => 'Specialized in agriculture, commerce, and environmental science.', 'admission_opening_date' => '2026-02-15', 'admission_deadline_date' => '2026-10-30'],
                    ['name' => 'Waikato University', 'location' => 'Hamilton', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://waikato.ac.nz', 'application_portal' => 'https://www.waikato.ac.nz/study/apply/', 'description' => 'Strong in management and indigenous studies.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-12-01'],
                ],
            ],
            'NL' => [
                'visa' => [
                    'visa_name' => 'Dutch Student Visa / MVV + Residence Permit',
                    'visa_fee' => 192,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '4–8 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 9600,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year',
                    'work_hours_per_week' => 16,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1 year Orientation Year Permit',
                    'required_documents' => ['Passport', 'Admission letter', 'Proof of sufficient funds', 'Health insurance', 'Diploma with translation'],
                ],
                'schools' => [
                    ['name' => 'Delft University of Technology', 'location' => 'Delft', 'type' => 'public', 'ranking' => 'Top 60 QS', 'website' => 'https://tudelft.nl', 'application_portal' => 'https://www.tudelft.nl/en/education/admission-and-application/', 'description' => 'The Netherlands\' top technical university, world-class for engineering.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2026-01-15', 'programs' => [
                            ['name' => 'MSc Sustainable Energy Technology', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 20000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['Bachelor in Engineering or Science', 'Math background']],
                        ]],
                    ['name' => 'University of Amsterdam', 'location' => 'Amsterdam', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://uva.nl', 'application_portal' => 'https://www.uva.nl/en/education/master-s/masters.html', 'description' => 'A comprehensive research university in Europe\'s most international city.', 'admission_opening_date' => '2025-11-01', 'admission_deadline_date' => '2026-02-01', 'programs' => [
                            ['name' => 'MSc Artificial Intelligence', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 18000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 7.0, 'admission_requirements' => ['BSc in CS, Mathematics or AI related', 'Programming proficiency']],
                        ]],
                    ['name' => 'Leiden University', 'location' => 'Leiden & The Hague', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://universiteitleiden.nl', 'application_portal' => 'https://www.universiteitleiden.nl/en/education/admission-and-application', 'description' => 'The oldest university in the Netherlands, located near The Hague.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-04-01'],
                    ['name' => 'Utrecht University', 'location' => 'Utrecht', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://uu.nl', 'application_portal' => 'https://www.uu.nl/en/organisation/governance-and-organisation/application-portal', 'description' => 'Consistently high-ranking university in the heart of the country.', 'admission_opening_date' => '2025-11-01', 'admission_deadline_date' => '2026-04-01'],
                    ['name' => 'Radboud University', 'location' => 'Nijmegen', 'type' => 'public', 'ranking' => 'Top 250 QS', 'website' => 'https://ru.nl', 'application_portal' => 'https://www.ru.nl/en/education/master-programmes/admission-and-application', 'description' => 'Known for its high-quality research and green campus.', 'admission_opening_date' => '2025-12-01', 'admission_deadline_date' => '2026-05-01'],
                    ['name' => 'Wageningen University & Research', 'location' => 'Wageningen', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://wur.nl', 'application_portal' => 'https://www.wur.nl/en/education-programmes/master/admission-and-application.htm', 'description' => 'Leading institution for life sciences, agriculture, and environment.', 'admission_opening_date' => '2025-11-01', 'admission_deadline_date' => '2026-05-01'],
                    ['name' => 'Groningen University', 'location' => 'Groningen', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://rug.nl', 'application_portal' => 'https://www.rug.nl/education/application-and-admission/', 'description' => 'Top research university in the northern Netherlands.', 'admission_opening_date' => '2025-12-01', 'admission_deadline_date' => '2026-05-01'],
                    ['name' => 'Erasmus University Rotterdam', 'location' => 'Rotterdam', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://eur.nl', 'application_portal' => 'https://www.eur.nl/en/education/application-admission', 'description' => 'Excellence in social sciences, clinical medicine, and business.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2026-04-15'],
                ],
            ],
            'FR' => [
                'visa' => [
                    'visa_name' => 'French Long-Stay Student Visa (VLS-TS étudiant)',
                    'visa_fee' => 99,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '3–6 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 7320,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year (€615/month minimum)',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1 year APS (temporary residence)',
                    'required_documents' => ['Passport', 'Admission letter', 'Proof of funds', 'Health insurance', 'Housing proof', 'Campus France approval (for certain countries)'],
                ],
                'schools' => [
                    ['name' => 'Sorbonne University', 'location' => 'Paris', 'type' => 'public', 'ranking' => 'Top 80 QS', 'website' => 'https://sorbonne-universite.fr', 'application_portal' => 'https://www.sorbonne-universite.fr/en/education/applying-sorbonne-university', 'description' => 'One of the world\'s oldest and most renowned universities.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-05-31', 'programs' => [
                            ['name' => 'MSc Sciences de la Vie (Life Sciences)', 'degree_type' => 'master', 'field_of_study' => 'Biology', 'duration_years' => 2, 'tuition_per_year' => 3770, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in Life Sciences']],
                        ]],
                    ['name' => 'Sciences Po', 'location' => 'Paris', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://sciencespo.fr', 'application_portal' => 'https://www.sciencespo.fr/admissions/en/', 'description' => 'Elite French institution specializing in social sciences and international affairs.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2025-12-15', 'programs' => [
                            ['name' => 'Master in International Affairs', 'degree_type' => 'master', 'field_of_study' => 'Political Science', 'duration_years' => 2, 'tuition_per_year' => 13560, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 7.0, 'admission_requirements' => ['Bachelor in Social Sciences', 'Motivation letter', 'CV']],
                        ]],
                    ['name' => 'HEC Paris', 'location' => 'Jouy-en-Josas', 'type' => 'private', 'ranking' => '#1 Business School in Europe', 'website' => 'https://hec.edu', 'application_portal' => 'https://www.hec.edu/en/master-s-programs/how-apply', 'description' => 'One of the prestigious Grand Écoles, world-leader in business education.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2026-04-01'],
                    ['name' => 'École Polytechnique', 'location' => 'Palaiseau', 'type' => 'public', 'ranking' => 'Top 50 QS', 'website' => 'https://polytechnique.edu', 'application_portal' => 'https://www.polytechnique.edu/en/programs/apply', 'description' => 'The most prestigious engineering school in France.', 'admission_opening_date' => '2025-11-01', 'admission_deadline_date' => '2026-03-31'],
                    ['name' => 'PSL University', 'location' => 'Paris', 'type' => 'public', 'ranking' => 'Top 50 QS', 'website' => 'https://psl.eu', 'application_portal' => 'https://psl.eu/en/education/apply-psl', 'description' => 'A collegiate university in the heart of Paris, combining elite schools like ENS.', 'admission_opening_date' => '2026-01-15', 'admission_deadline_date' => '2026-05-15'],
                    ['name' => 'University of Strasbourg', 'location' => 'Strasbourg', 'type' => 'public', 'ranking' => 'Top 400 QS', 'website' => 'https://unistra.fr', 'application_portal' => 'https://www.unistra.fr/en/study/apply', 'description' => 'Deeply rooted in European history and a top research university.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-06-30'],
                    ['name' => 'Aix-Marseille University', 'location' => 'Marseille', 'type' => 'public', 'ranking' => 'Top 500 QS', 'website' => 'https://univ-amu.fr', 'application_portal' => 'https://www.univ-amu.fr/en/applications', 'description' => 'The largest university in the French-speaking world by enrollment.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-07-01'],
                    ['name' => 'University of Lyon', 'location' => 'Lyon', 'type' => 'public', 'ranking' => 'Top 500 QS', 'website' => 'https://universite-lyon.fr', 'application_portal' => 'https://www.universite-lyon.fr/en/studies/applications', 'description' => 'A key academic hub in France\'s second largest city.', 'admission_opening_date' => '2026-01-15', 'admission_deadline_date' => '2026-05-31'],
                ],
            ],
            'IT' => [
                'visa' => [
                    'visa_name' => 'Italian Student Visa (National Visa Type D)',
                    'visa_fee' => 116,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '4–8 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 6167,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year (as per Italian law)',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1 year job-seeker permit',
                    'required_documents' => ['Passport', 'University admission letter', 'Proof of accommodation', 'Financial means declaration', 'Health insurance', 'Degree certificates with Italian translation'],
                ],
                'schools' => [
                    ['name' => 'Politecnico di Milano', 'location' => 'Milan', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://polimi.it', 'application_portal' => 'https://www.polimi.it/en/prospective-students/', 'description' => 'Italy\'s leading technical university, globally recognized for design and engineering.', 'admission_opening_date' => '2026-01-15', 'admission_deadline_date' => '2026-05-15', 'programs' => [
                            ['name' => 'MSc Computer Science and Engineering', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 3748, 'currency' => 'EUR', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in CS or Electronics']],
                        ]],
                    ['name' => 'University of Bologna', 'location' => 'Bologna', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://unibo.it', 'application_portal' => 'https://www.unibo.it/en/teaching/degree-programmes/', 'description' => 'The world\'s oldest university, founded in 1088.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-08-31', 'programs' => [
                            ['name' => 'MSc Artificial Intelligence', 'degree_type' => 'master', 'field_of_study' => 'AI', 'duration_years' => 2, 'tuition_per_year' => 2800, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in Computer Science or Mathematics']],
                        ]],
                    ['name' => 'Sapienza University of Rome', 'location' => 'Rome', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://uniroma1.it', 'application_portal' => 'https://www.uniroma1.it/en/admissions', 'description' => 'One of the largest European universities, established in 1303.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-07-31'],
                    ['name' => 'University of Padua', 'location' => 'Padua', 'type' => 'public', 'ranking' => 'Top 250 QS', 'website' => 'https://unipd.it', 'application_portal' => 'https://www.unipd.it/en/admissions', 'description' => 'The second oldest university in Italy with a strong scientific tradition.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-06-30'],
                    ['name' => 'Bocconi University', 'location' => 'Milan', 'type' => 'private', 'ranking' => 'Top 10 Business in Europe', 'website' => 'https://unibocconi.it', 'application_portal' => 'https://www.unibocconi.it/en/admissions', 'description' => 'Premier private university for economics, management and law.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-01-31'],
                    ['name' => 'University of Pisa', 'location' => 'Pisa', 'type' => 'public', 'ranking' => 'Top 400 QS', 'website' => 'https://unipi.it', 'application_portal' => 'https://www.unipi.it/index.php/admissions', 'description' => 'Well-known institution with links to Galileo Galilei.', 'admission_opening_date' => '2026-04-01', 'admission_deadline_date' => '2026-08-15'],
                    ['name' => 'University of Turin', 'location' => 'Turin', 'type' => 'public', 'ranking' => 'Top 500 QS', 'website' => 'https://unito.it', 'application_portal' => 'https://www.unito.it/international/applying', 'description' => 'A traditional and high-ranking research university in northwest Italy.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-07-15'],
                    ['name' => 'Politecnico di Torino', 'location' => 'Turin', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://polito.it', 'application_portal' => 'https://www.polito.it/en/education/applying', 'description' => 'A leading school for engineering and architecture.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-05-31'],
                ],
            ],
            'SE' => [
                'visa' => [
                    'visa_name' => 'Swedish Residence Permit for Studies',
                    'visa_fee' => 1500,
                    'visa_fee_currency' => 'SEK',
                    'processing_time' => '3–6 months',
                    'financial_proof_required' => true,
                    'min_funds_required' => 8514,
                    'min_funds_currency' => 'SEK',
                    'min_funds_description' => 'per month for living costs',
                    'work_hours_per_week' => null,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1 year (ET-Short Stay Visa)',
                    'required_documents' => ['Passport', 'Admission letter', 'Proof of funds', 'Health insurance'],
                ],
                'schools' => [
                    ['name' => 'Karolinska Institute', 'location' => 'Stockholm', 'type' => 'public', 'ranking' => 'Top 50 QS', 'website' => 'https://ki.se', 'application_portal' => 'https://ki.se/en/education/study-here', 'description' => 'World-leading medical university and home of the Nobel Prize in Physiology or Medicine.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-01-15', 'programs' => [
                            ['name' => 'MSc Biomedicine', 'degree_type' => 'master', 'field_of_study' => 'Biology', 'duration_years' => 2, 'tuition_per_year' => 195000, 'currency' => 'SEK', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in Biomedicine or related']],
                        ]],
                    ['name' => 'Stockholm University', 'location' => 'Stockholm', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://su.se', 'application_portal' => 'https://www.su.se/english/education/', 'description' => 'A comprehensive research university in Scandinavia\'s dynamic capital.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-01-15', 'programs' => [
                            ['name' => 'MSc Sustainable Development', 'degree_type' => 'master', 'field_of_study' => 'Environmental Science', 'duration_years' => 2, 'tuition_per_year' => 150000, 'currency' => 'SEK', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['Bachelor in relevant field']],
                        ]],
                    ['name' => 'Lund University', 'location' => 'Lund', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://lunduniversity.lu.se', 'application_portal' => 'https://www.lunduniversity.lu.se/admissions/how-to-apply', 'description' => 'One of Scandinavia\'s oldest and most prestigious universities.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-01-15'],
                    ['name' => 'Uppsala University', 'location' => 'Uppsala', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://uu.se/en', 'application_portal' => 'https://www.uu.se/en/admissions/master/apply/', 'description' => 'The oldest university in the Nordic countries, founded in 1477.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-01-15'],
                    ['name' => 'KTH Royal Institute of Technology', 'location' => 'Stockholm', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://kth.se/en', 'application_portal' => 'https://www.kth.se/en/studies/how-to-apply', 'description' => 'Sweden\'s largest and most respected technical university.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-01-15'],
                    ['name' => 'Chalmers University of Technology', 'location' => 'Gothenburg', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://chalmers.se/en', 'application_portal' => 'https://www.chalmers.se/en/education/applying/', 'description' => 'A highly-ranked technical university in Gothernburg.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-01-15'],
                    ['name' => 'University of Gothenburg', 'location' => 'Gothenburg', 'type' => 'public', 'ranking' => 'Top 250 QS', 'website' => 'https://gu.se/en', 'application_portal' => 'https://www.gu.se/en/study-in-gothenburg/apply', 'description' => 'One of the largest universities in the Nordic countries.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-01-15'],
                    ['name' => 'Umeå University', 'location' => 'Umeå', 'type' => 'public', 'ranking' => 'Top 450 QS', 'website' => 'https://umu.se/en', 'application_portal' => 'https://www.umu.se/en/education/admissions/how-to-apply/', 'description' => 'A leading university in northern Sweden.', 'admission_opening_date' => '2025-10-15', 'admission_deadline_date' => '2026-01-15'],
                ],
            ],
            'FI' => [
                'visa' => [
                    'visa_name' => 'Finnish Residence Permit for Studies',
                    'visa_fee' => 350,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '4–8 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 6720,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year (€560/month)',
                    'work_hours_per_week' => 25,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1 year',
                    'required_documents' => ['Passport', 'Acceptance letter', 'Proof of funds', 'Health insurance'],
                ],
                'schools' => [
                    ['name' => 'University of Helsinki', 'location' => 'Helsinki', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://helsinki.fi', 'application_portal' => 'https://www.helsinki.fi/en/admissions', 'description' => 'Finland\'s oldest and largest university with strong research.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-01-20', 'programs' => [
                            ['name' => 'MSc Computer Science', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 15000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in CS or Mathematics']],
                        ]],
                    ['name' => 'Aalto University', 'location' => 'Espoo', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://aalto.fi', 'application_portal' => 'https://www.aalto.fi/en/study-at-aalto', 'description' => 'A multidisciplinary university combining business, tech and arts.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-01-20', 'programs' => [
                            ['name' => 'MSc Information Networks', 'degree_type' => 'master', 'field_of_study' => 'IT', 'duration_years' => 2, 'tuition_per_year' => 15000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 7.0, 'admission_requirements' => ['Bachelor in Engineering or Sciences']],
                        ]],
                    ['name' => 'University of Turku', 'location' => 'Turku', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://utu.fi/en', 'application_portal' => 'https://www.utu.fi/en/study-at-utu/how-to-apply', 'description' => 'Known for its high-quality research and teaching.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-01-20'],
                    ['name' => 'University of Jyväskylä', 'location' => 'Jyväskylä', 'type' => 'public', 'ranking' => 'Top 350 QS', 'website' => 'https://jyu.fi/en', 'application_portal' => 'https://www.jyu.fi/en/apply', 'description' => 'Expertise in education and sports sciences.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-01-20'],
                    ['name' => 'Tampere University', 'location' => 'Tampere', 'type' => 'public', 'ranking' => 'Top 400 QS', 'website' => 'https://tuni.fi/en', 'application_portal' => 'https://www.tuni.fi/en/apply', 'description' => 'Strong focus on society and technology.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-01-20'],
                    ['name' => 'University of Oulu', 'location' => 'Oulu', 'type' => 'public', 'ranking' => 'Top 450 QS', 'website' => 'https://oulu.fi/en', 'application_portal' => 'https://www.oulu.fi/en/apply', 'description' => 'Located in the tech hub of the north.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-01-20'],
                    ['name' => 'LUT University', 'location' => 'Lappeenranta & Lahti', 'type' => 'public', 'ranking' => 'Top 400 QS', 'website' => 'https://lut.fi/en', 'application_portal' => 'https://www.lut.fi/en/apply', 'description' => 'Specialized in technology and business sustainable solutions.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-01-20'],
                    ['name' => 'University of Eastern Finland', 'location' => 'Kuopio & Joensuu', 'type' => 'public', 'ranking' => 'Top 500 QS', 'website' => 'https://uef.fi/en', 'application_portal' => 'https://www.uef.fi/en/apply', 'description' => 'Multidisciplinary university with strong health sciences.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-01-20'],
                ],
            ],
            'NO' => [
                'visa' => [
                    'visa_name' => 'Norwegian Student Residence Permit',
                    'visa_fee' => 800,
                    'visa_fee_currency' => 'NOK',
                    'processing_time' => '2–6 months',
                    'financial_proof_required' => true,
                    'min_funds_required' => 128887,
                    'min_funds_currency' => 'NOK',
                    'min_funds_description' => 'per year',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1 year',
                    'required_documents' => ['Passport', 'Admission letter', 'Proof of financial sufficiency', 'Accommodation proof'],
                    'notes' => 'Most public universities have no tuition fees, even for international students.',
                ],
                'schools' => [
                    ['name' => 'University of Oslo', 'location' => 'Oslo', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://uio.no', 'application_portal' => 'https://www.uio.no/english/studies/application/', 'description' => 'Norway\'s oldest and most esteemed university.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2025-12-01', 'programs' => [
                            ['name' => 'MSc Informatics', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 0, 'currency' => 'NOK', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in CS or Mathematics']],
                        ]],
                    ['name' => 'University of Bergen', 'location' => 'Bergen', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://uib.no/en', 'application_portal' => 'https://www.uib.no/en/education/admission', 'description' => 'Globally recognized for marine research and climate studies.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2025-12-01'],
                    ['name' => 'Norwegian University of Science and Technology (NTNU)', 'location' => 'Trondheim', 'type' => 'public', 'ranking' => 'Top 350 QS', 'website' => 'https://ntnu.edu', 'application_portal' => 'https://www.ntnu.edu/studies/admission', 'description' => 'The premier technical university in Norway.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2025-12-01'],
                    ['name' => 'UiT The Arctic University of Norway', 'location' => 'Tromsø', 'type' => 'public', 'ranking' => 'Top 450 QS', 'website' => 'https://uit.no/front', 'application_portal' => 'https://en.uit.no/education/admissions', 'description' => 'The world\'s northernmost university.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2025-12-01'],
                    ['name' => 'University of Stavanger', 'location' => 'Stavanger', 'type' => 'public', 'ranking' => 'Top 500 QS', 'website' => 'https://uis.no/en', 'application_portal' => 'https://www.uis.no/en/studies/how-to-apply', 'description' => 'Strong links to the energy and petroleum industry.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2025-12-01'],
                    ['name' => 'NMBU - Norwegian University of Life Sciences', 'location' => 'Ås', 'type' => 'public', 'ranking' => 'Top 600 QS', 'website' => 'https://nmbu.no/en', 'application_portal' => 'https://www.nmbu.no/en/studies/admission', 'description' => 'Expertise in veterinary medicine, agriculture and sustainability.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2025-12-01'],
                    ['name' => 'BI Norwegian Business School', 'location' => 'Oslo', 'type' => 'private', 'ranking' => 'Top Business in Europe', 'website' => 'https://bi.edu', 'application_portal' => 'https://www.bi.edu/study-at-bi/application-and-admission/', 'description' => 'One of the leading business schools in Europe.', 'admission_opening_date' => '2025-11-01', 'admission_deadline_date' => '2026-03-01'],
                    ['name' => 'University of Agder', 'location' => 'Kristiansand', 'type' => 'public', 'ranking' => 'Top 800 QS', 'website' => 'https://uia.no/en', 'application_portal' => 'https://www.uia.no/en/studies/admission', 'description' => 'Dynamic university in southern Norway.', 'admission_opening_date' => '2025-10-01', 'admission_deadline_date' => '2025-12-01'],
                ],
            ],
            'AT' => [
                'visa' => [
                    'visa_name' => 'Austrian Student Visa (Aufenthaltserlaubnis Schüler)',
                    'visa_fee' => 120,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '4–10 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 8000,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '1 year job-seeker visa',
                    'required_documents' => ['Passport', 'University admission letter', 'Proof of funds', 'Health insurance', 'Accommodation proof', 'German or English proficiency certificate'],
                ],
                'schools' => [
                    ['name' => 'University of Vienna', 'location' => 'Vienna', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://univie.ac.at', 'application_portal' => 'https://www.univie.ac.at/en/studying/apply/', 'description' => 'Austria\'s largest and oldest university, founded in 1365.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-09-05', 'programs' => [
                            ['name' => 'MSc Computer Science', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 1500, 'currency' => 'EUR', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in CS or Mathematics']],
                        ]],
                    ['name' => 'TU Wien (Vienna University of Technology)', 'location' => 'Vienna', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://tuwien.at/en', 'application_portal' => 'https://www.tuwien.at/en/studies/admission', 'description' => 'A leading technical university in Central Europe.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-09-05'],
                    ['name' => 'University of Innsbruck', 'location' => 'Innsbruck', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://uibk.ac.at/en', 'application_portal' => 'https://www.uibk.ac.at/en/studies/admission/', 'description' => 'Famous for its research and scenic Alpine location.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-09-05'],
                    ['name' => 'University of Graz', 'location' => 'Graz', 'type' => 'public', 'ranking' => 'Top 600 QS', 'website' => 'https://uni-graz.at/en', 'application_portal' => 'https://studienabteilung.uni-graz.at/en/admission/', 'description' => 'The second largest university in Austria.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-09-05'],
                    ['name' => 'Graz University of Technology', 'location' => 'Graz', 'type' => 'public', 'ranking' => 'Top 450 QS', 'website' => 'https://tugraz.at/en', 'application_portal' => 'https://www.tugraz.at/en/studies/prospective-students/admission-and-registration/', 'description' => 'A major center for science and tech in Styria.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-09-05'],
                    ['name' => 'University of Salzburg', 'location' => 'Salzburg', 'type' => 'public', 'ranking' => 'Top 800 QS', 'website' => 'https://plus.ac.at', 'application_portal' => 'https://www.plus.ac.at/studium/bewerbung-und-zulassung/', 'description' => 'Highly ranked for law, theology and humanities.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-09-05'],
                    ['name' => 'JKU Linz', 'location' => 'Linz', 'type' => 'public', 'ranking' => 'Top 500 QS', 'website' => 'https://jku.at/en', 'application_portal' => 'https://www.jku.at/en/studying/admission/', 'description' => 'Strong focus on technology, social sciences and law.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-09-05'],
                    ['name' => 'Medical University of Vienna', 'location' => 'Vienna', 'type' => 'public', 'ranking' => 'Top 250 World', 'website' => 'https://meduniwien.ac.at/en', 'application_portal' => 'https://www.meduniwien.ac.at/hp/en/studies/admission/', 'description' => 'Successor to the faculty of medicine at the University of Vienna.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-09-05'],
                ],
            ],
            'PL' => [
                'visa' => [
                    'visa_name' => 'Polish National Visa (Type D)',
                    'visa_fee' => 80,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '15–30 days',
                    'financial_proof_required' => true,
                    'min_funds_required' => 3000,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year (exclusive of tuition)',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '9 months (job-seeker permit)',
                    'required_documents' => ['Passport', 'Acceptance letter', 'Proof of funds (Min PLN 776/mo)', 'Health insurance (€30k coverage)', 'Accommodation proof', 'Return flight ticket'],
                ],
                'schools' => [
                    [
                        'name' => 'University of Warsaw',
                        'location' => 'Warsaw',
                        'type' => 'public',
                        'ranking' => 'Top 300 QS World',
                        'website' => 'https://en.uw.edu.pl/',
                        'application_portal' => 'https://irk.uw.edu.pl/',
                        'admission_opening_date' => '2026-05-10',
                        'admission_deadline_date' => '2026-07-10',
                        'image_url' => 'https://images.unsplash.com/photo-1629813203204-c36b69671dca?q=80&w=1200',
                        'description' => 'The largest and most prestigious university in Poland, offering world-class research and a vibrant international community.',
                        'programs' => [
                            ['name' => 'MSc Data Science and Business Analytics', 'degree_type' => 'master', 'field_of_study' => 'Data Science', 'duration_years' => 2, 'tuition_per_year' => 4500, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 6.5],
                            ['name' => 'Applied Multilingual Studies', 'degree_type' => 'bachelor', 'field_of_study' => 'Languages', 'duration_years' => 3, 'tuition_per_year' => 5250, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 6.0],
                        ],
                    ],
                    [
                        'name' => 'Jagiellonian University',
                        'location' => 'Kraków',
                        'type' => 'public',
                        'ranking' => 'Top 400 QS World',
                        'website' => 'https://en.uj.edu.pl/',
                        'application_portal' => 'https://irk.uj.edu.pl/',
                        'admission_opening_date' => '2026-04-01',
                        'admission_deadline_date' => '2026-07-10',
                        'image_url' => 'https://images.unsplash.com/photo-1601625463687-25541fb72f67?q=80&w=1200',
                        'description' => 'Established in 1364, it is one of the oldest and most historically significant universities in Central Europe.',
                        'programs' => [
                            ['name' => 'MA International Relations', 'degree_type' => 'master', 'field_of_study' => 'Political Science', 'duration_years' => 2, 'tuition_per_year' => 4500, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 6.5],
                            ['name' => 'BA Global and Development Studies', 'degree_type' => 'bachelor', 'field_of_study' => 'Sociology', 'duration_years' => 3, 'tuition_per_year' => 4000, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 6.5],
                        ],
                    ],
                    [
                        'name' => 'Warsaw University of Technology',
                        'location' => 'Warsaw',
                        'type' => 'public',
                        'ranking' => '#1 Tech University in Poland',
                        'website' => 'https://www.pw.edu.pl/en',
                        'application_portal' => 'https://irk.pw.edu.pl/',
                        'admission_opening_date' => '2026-05-01',
                        'admission_deadline_date' => '2026-07-15',
                        'image_url' => 'https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1200',
                        'description' => 'A premier engineering hub focused on innovation, technology, and cutting-edge research.',
                        'programs' => [
                            ['name' => 'BSc Computer Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Engineering', 'duration_years' => 3.5, 'tuition_per_year' => 5700, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 6.0],
                            ['name' => 'MSc Aerospace Engineering', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 3500, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 6.5],
                        ],
                    ],
                    [
                        'name' => 'Adam Mickiewicz University',
                        'location' => 'Poznań',
                        'type' => 'public',
                        'ranking' => 'Top 3 Public Univ',
                        'website' => 'https://amu.edu.pl/en',
                        'application_portal' => 'https://rekrutacja.amu.edu.pl/',
                        'admission_opening_date' => '2026-05-01',
                        'admission_deadline_date' => '2026-07-30',
                        'image_url' => 'https://images.unsplash.com/photo-1590012314607-cda9d9b6a917?q=80&w=1200',
                        'description' => 'A vibrant academic hub in Western Poland known for excellence in humanities and law.',
                        'programs' => [
                            ['name' => 'BA International Relations', 'degree_type' => 'bachelor', 'field_of_study' => 'Political Science', 'duration_years' => 3, 'tuition_per_year' => 3000, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 6.0],
                        ],
                    ],
                    ['name' => 'AGH University of Science and Technology', 'location' => 'Kraków', 'type' => 'public', 'ranking' => 'Top 500 QS', 'website' => 'https://agh.edu.pl/en', 'application_portal' => 'https://rekrutacja.agh.edu.pl/en/', 'description' => 'One of the best technical universities in Poland.', 'admission_opening_date' => '2026-04-15', 'admission_deadline_date' => '2026-07-15'],
                    ['name' => 'Wroclaw University of Science and Technology', 'location' => 'Wroclaw', 'type' => 'public', 'ranking' => 'Top 800 QS', 'website' => 'https://pwr.edu.pl/en/', 'application_portal' => 'https://rekrutacja.pwr.edu.pl/en/', 'description' => 'A leading educational and research center in Lower Silesia.', 'admission_opening_date' => '2026-05-01', 'admission_deadline_date' => '2026-07-20'],
                    ['name' => 'Medical University of Warsaw', 'location' => 'Warsaw', 'type' => 'public', 'ranking' => '#1 Medical in Poland', 'website' => 'https://wum.edu.pl/en', 'application_portal' => 'https://rekrutacja.wum.edu.pl/en', 'description' => 'The largest and most prestigious medical school in Poland.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-06-30'],
                    ['name' => 'Gdansk University of Technology', 'location' => 'Gdańsk', 'type' => 'public', 'ranking' => 'Top 800 QS', 'website' => 'https://pg.edu.pl/en', 'application_portal' => 'https://rekrutacja.pg.edu.pl/en', 'description' => 'Ranked among the top technical universities in Europe.', 'admission_opening_date' => '2026-05-01', 'admission_deadline_date' => '2026-07-25'],
                    ['name' => 'University of Lodz', 'location' => 'Lodz', 'type' => 'public', 'ranking' => 'Top 1000 QS', 'website' => 'https://iso.uni.lodz.pl/', 'application_portal' => 'https://rekrutacja.uni.lodz.pl/', 'description' => 'A modern public university with strong international outreach.', 'admission_opening_date' => '2026-05-15', 'admission_deadline_date' => '2026-08-15'],
                    ['name' => 'Kozminski University', 'location' => 'Warsaw', 'type' => 'private', 'ranking' => 'Top Business in CEE', 'website' => 'https://kozminski.edu.pl/en', 'application_portal' => 'https://rekrutacja.kozminski.edu.pl/', 'description' => 'Triple-crown accredited business school.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-08-31'],
                ],
            ],
            'CH' => [
                'visa' => [
                    'visa_name' => 'Swiss National Visa (Type D)',
                    'visa_fee' => 80,
                    'visa_fee_currency' => 'CHF',
                    'processing_time' => '8–16 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 21000,
                    'min_funds_currency' => 'CHF',
                    'min_funds_description' => 'per year (approximate living costs)',
                    'work_hours_per_week' => 15,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '6 months job-seeker permit',
                    'required_documents' => ['Passport', 'Acceptance letter', 'Pre-paid tuition receipt', 'Proof of CHF 21k-30k available', 'Motivation letter & CV', 'Exit declaration'],
                ],
                'schools' => [
                    [
                        'name' => 'ETH Zurich',
                        'location' => 'Zurich',
                        'type' => 'public',
                        'ranking' => 'Top 10 QS World',
                        'website' => 'https://ethz.ch/en.html',
                        'application_portal' => 'https://ethz.ch/en/studies/registration-application.html',
                        'admission_opening_date' => '2026-04-01',
                        'admission_deadline_date' => '2026-04-30',
                        'image_url' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?q=80&w=1200',
                        'description' => 'A world-leading science and technology university, synonymous with innovation and research excellence.',
                        'programs' => [
                            ['name' => 'MSc Robotics, Systems and Control', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 1460, 'currency' => 'CHF', 'intake_periods' => ['September'], 'ielts_min' => 7.0],
                            ['name' => 'MSc Data Science', 'degree_type' => 'master', 'field_of_study' => 'Data Science', 'duration_years' => 2, 'tuition_per_year' => 1460, 'currency' => 'CHF', 'intake_periods' => ['September'], 'ielts_min' => 7.0],
                        ],
                    ],
                    [
                        'name' => 'EPFL',
                        'location' => 'Lausanne',
                        'type' => 'public',
                        'ranking' => 'Top 20 QS World',
                        'website' => 'https://www.epfl.ch/en/',
                        'application_portal' => 'https://www.epfl.ch/education/admission/',
                        'admission_opening_date' => '2026-01-15',
                        'admission_deadline_date' => '2026-04-15',
                        'image_url' => 'https://images.unsplash.com/photo-1541339907198-e08756eaa539?q=80&w=1200',
                        'description' => 'One of Europes most innovative technical universities, specializing in natural sciences and engineering.',
                        'programs' => [
                            ['name' => 'MSc Computer Science', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 1560, 'currency' => 'CHF', 'intake_periods' => ['September'], 'ielts_min' => 7.0],
                        ],
                    ],
                    [
                        'name' => 'University of Zurich (UZH)',
                        'location' => 'Zurich',
                        'type' => 'public',
                        'ranking' => 'Top 100 QS World',
                        'website' => 'https://www.uzh.ch/en.html',
                        'application_portal' => 'https://www.uzh.ch/en/studies/application.html',
                        'admission_opening_date' => '2026-01-01',
                        'admission_deadline_date' => '2026-04-30',
                        'image_url' => 'https://images.unsplash.com/photo-1545229838-892f392cecb1?q=80&w=1200',
                        'description' => 'The largest university in Switzerland, offering the widest range of programs across all disciplines.',
                        'programs' => [
                            ['name' => 'MSc Finance', 'degree_type' => 'master', 'field_of_study' => 'Finance', 'duration_years' => 2, 'tuition_per_year' => 1700, 'currency' => 'CHF', 'intake_periods' => ['September', 'February'], 'ielts_min' => 7.0],
                        ],
                    ],
                    [
                        'name' => 'University of St. Gallen (HSG)',
                        'location' => 'St. Gallen',
                        'type' => 'public',
                        'ranking' => '#1 Management in Europe',
                        'website' => 'https://www.unisg.ch/en',
                        'application_portal' => 'https://www.unisg.ch/en/studies/admissions/',
                        'admission_opening_date' => '2025-10-01',
                        'admission_deadline_date' => '2026-04-30',
                        'image_url' => 'https://images.unsplash.com/photo-1614850523296-62180ad1e786?q=80&w=1200',
                        'description' => 'A premier global business school focusing on management, law, and international affairs.',
                        'programs' => [
                            ['name' => 'MA Strategy and International Management', 'degree_type' => 'master', 'field_of_study' => 'Business', 'duration_years' => 1.5, 'tuition_per_year' => 6650, 'currency' => 'CHF', 'intake_periods' => ['September'], 'ielts_min' => 7.0],
                        ],
                    ],
                    ['name' => 'University of Basel', 'location' => 'Basel', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://unibas.ch/en', 'application_portal' => 'https://www.unibas.ch/en/Studies/Application-Admission.html', 'description' => 'The oldest university in Switzerland with a rich tradition in life sciences.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-04-30'],
                    ['name' => 'University of Bern', 'location' => 'Bern', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://unibe.ch/en/', 'application_portal' => 'https://www.unibe.ch/studies/admissions/', 'description' => 'A comprehensive university located in the capital city.', 'admission_opening_date' => '2026-01-15', 'admission_deadline_date' => '2026-04-30'],
                    ['name' => 'University of Lausanne', 'location' => 'Lausanne', 'type' => 'public', 'ranking' => 'Top 250 QS', 'website' => 'https://unil.ch/en', 'application_portal' => 'https://www.unil.ch/admissions/en/home.html', 'description' => 'Known for its beautiful campus on the shores of Lake Geneva.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-04-30'],
                    ['name' => 'University of Geneva', 'location' => 'Geneva', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://unige.ch/en', 'application_portal' => 'https://www.unige.ch/admissions/en', 'description' => 'A world-renowned university in a global city of diplomacy.', 'admission_opening_date' => '2026-01-15', 'admission_deadline_date' => '2026-04-30'],
                    ['name' => 'University of Lugano (USI)', 'location' => 'Lugano', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://usi.ch/en', 'application_portal' => 'https://www.usi.ch/en/education/admission', 'description' => 'The only Italian-speaking university in Switzerland.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-06-30'],
                    ['name' => 'University of Fribourg', 'location' => 'Fribourg', 'type' => 'public', 'ranking' => 'Top 600 QS', 'website' => 'https://unifr.ch/en/', 'application_portal' => 'https://www.unifr.ch/studies/en/admission/', 'description' => 'A unique bilingual university (French/German).', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-04-30'],
                ],
            ],
            'MT' => [
                'visa' => [
                    'visa_name' => 'Maltese Student Visa (Type D)',
                    'visa_fee' => 66,
                    'visa_fee_currency' => 'EUR',
                    'processing_time' => '4–8 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 6600,
                    'min_funds_currency' => 'EUR',
                    'min_funds_description' => 'per year (€18-26/day)',
                    'work_hours_per_week' => 20,
                    'post_study_work_permit' => true,
                    'post_study_work_duration' => '12 months job-seeker permit',
                    'required_documents' => ['Passport', 'Acceptance letter', 'Medical insurance certificate', 'Accommodation proof', 'Bank statements (6 months)'],
                ],
                'schools' => [
                    [
                        'name' => 'University of Malta',
                        'location' => 'Msida',
                        'type' => 'public',
                        'ranking' => 'Top 800 QS World',
                        'website' => 'https://www.um.edu.mt/',
                        'application_portal' => 'https://www.um.edu.mt/apply/',
                        'admission_opening_date' => '2026-03-01',
                        'admission_deadline_date' => '2026-07-24',
                        'image_url' => 'https://images.unsplash.com/photo-1525610553991-2bede1a236e2?q=80&w=1200',
                        'description' => 'The highest teaching institution in Malta, offering high-quality education in an English-speaking environment.',
                        'programs' => [
                            ['name' => 'MSc Artificial Intelligence', 'degree_type' => 'master', 'field_of_study' => 'IT', 'duration_years' => 1.5, 'tuition_per_year' => 8500, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 6.5],
                            ['name' => 'MBA (Executive)', 'degree_type' => 'master', 'field_of_study' => 'Business', 'duration_years' => 2, 'tuition_per_year' => 12000, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 6.5],
                        ],
                    ],
                    [
                        'name' => 'MCAST',
                        'location' => 'Paola',
                        'type' => 'public',
                        'ranking' => 'Leading Vocational Inst',
                        'website' => 'https://mcast.edu.mt/',
                        'application_portal' => 'https://mcast.edu.mt/apply/',
                        'admission_opening_date' => '2026-07-01',
                        'admission_deadline_date' => '2026-08-31',
                        'image_url' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200',
                        'description' => 'Malta College of Arts, Science and Technology, specializing in vocational and professional training.',
                        'programs' => [
                            ['name' => 'BEng Electronics Engineering', 'degree_type' => 'bachelor', 'field_of_study' => 'Engineering', 'duration_years' => 4, 'tuition_per_year' => 6500, 'currency' => 'EUR', 'intake_periods' => ['October'], 'ielts_min' => 5.5],
                        ],
                    ],
                    [
                        'name' => 'American University of Malta',
                        'location' => 'Cospicua',
                        'type' => 'private',
                        'ranking' => 'American Liberal Arts Focus',
                        'website' => 'https://aum.edu.mt/',
                        'application_portal' => 'https://aum.edu.mt/apply-now/',
                        'admission_opening_date' => '2026-01-01',
                        'admission_deadline_date' => '2026-08-30',
                        'image_url' => 'https://images.unsplash.com/photo-1523050335392-9ae574d643c1?q=80&w=1200',
                        'description' => 'An American-style university focusing on technology, management, and global perspectives.',
                        'programs' => [
                            ['name' => 'BSc Game Development', 'degree_type' => 'bachelor', 'field_of_study' => 'IT', 'duration_years' => 3, 'tuition_per_year' => 8000, 'currency' => 'EUR', 'intake_periods' => ['October', 'February'], 'ielts_min' => 6.0],
                        ],
                    ],
                    [
                        'name' => 'GBS Malta',
                        'location' => 'St. Julian\'s',
                        'type' => 'private',
                        'ranking' => 'Modern Global Business',
                        'website' => 'https://gbs.edu.mt/',
                        'application_portal' => 'https://gbs.edu.mt/apply-now/',
                        'admission_opening_date' => '2026-01-01',
                        'admission_deadline_date' => '2026-08-30',
                        'image_url' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1200',
                        'description' => 'A specialized business school offering modern industry-relevant degrees in partnership with top global universities.',
                        'programs' => [
                            ['name' => 'BA Business and Management', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3, 'tuition_per_year' => 6500, 'currency' => 'EUR', 'intake_periods' => ['September', 'Jan', 'May'], 'ielts_min' => 6.0],
                        ],
                    ],
                    ['name' => 'Saint Martin\'s Institute of Higher Education', 'location' => 'Hamrun', 'type' => 'private', 'ranking' => 'UOL Affiliate', 'website' => 'https://stmartins.edu', 'application_portal' => 'https://stmartins.edu/apply', 'description' => 'Offers University of London degrees in Malta.', 'admission_opening_date' => '2026-02-01', 'admission_deadline_date' => '2026-08-31'],
                    ['name' => 'STC Higher Education', 'location' => 'Pembroke', 'type' => 'private', 'ranking' => 'Leading IT Hub', 'website' => 'https://stcmalta.com', 'application_portal' => 'https://stcmalta.com/apply', 'description' => 'Specialized in computer science and business management.', 'admission_opening_date' => '2026-01-15', 'admission_deadline_date' => '2026-09-15'],
                    ['name' => 'Domain Academy', 'location' => 'Mosta', 'type' => 'private', 'ranking' => 'International Excellence', 'website' => 'https://domainacademy.edu.mt', 'application_portal' => 'https://domainacademy.edu.mt/apply', 'description' => 'A premier private institution offering diplomas and degrees.', 'admission_opening_date' => '2026-03-01', 'admission_deadline_date' => '2026-08-31'],
                    ['name' => 'Global College Malta', 'location' => 'Smart City', 'type' => 'private', 'ranking' => 'British Style Education', 'website' => 'https://gcmalta.com', 'application_portal' => 'https://gcmalta.com/apply', 'description' => 'Provides undergraduate and postgraduate business courses.', 'admission_opening_date' => '2026-02-15', 'admission_deadline_date' => '2026-08-15'],
                    ['name' => 'EEC-ITIS Malta Tourism and Languages Institute', 'location' => 'San Gwann', 'type' => 'private', 'ranking' => 'Tourism Specialist', 'website' => 'https://eec-itis.edu.mt', 'application_portal' => 'https://eec-itis.edu.mt/apply', 'description' => 'Focused on hospitality, tourism, and languages.', 'admission_opening_date' => '2026-04-01', 'admission_deadline_date' => '2026-09-01'],
                    ['name' => 'London School of Commerce Malta', 'location' => 'Floriana', 'type' => 'private', 'ranking' => 'Global MBA Focus', 'website' => 'https://lscmalta.edu.mt', 'application_portal' => 'https://lscmalta.edu.mt/apply', 'description' => 'Global business school with branches across the world.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-08-30'],
                ],
            ],
            'NG' => [
                'visa' => [
                    'visa_name' => 'Nigerian Student Visa',
                    'visa_fee' => 50,
                    'visa_fee_currency' => 'USD',
                    'processing_time' => '2–4 weeks',
                    'financial_proof_required' => true,
                    'min_funds_required' => 1000,
                    'min_funds_currency' => 'USD',
                    'min_funds_description' => 'required to show financial capability',
                    'work_hours_per_week' => null,
                    'post_study_work_permit' => false,
                    'required_documents' => ['Passport', 'Admission letter from Nigerian institution', 'School fees receipt', 'Parent/guardian letter', 'WAEC/NECO results'],
                    'notes' => 'JAMB UTME is required for Nigerian universities. International students follow different admission process.',
                ],
                'schools' => [
                    ['name' => 'University of Lagos (UNILAG)', 'location' => 'Lagos', 'type' => 'public', 'ranking' => 'Top 1000 QS', 'website' => 'https://unilag.edu.ng', 'application_portal' => 'https://portal.unilag.edu.ng', 'description' => 'Nigeria\'s premier university.', 'admission_opening_date' => '2026-06-01', 'admission_deadline_date' => '2026-08-31', 'programs' => [
                            ['name' => 'BSc Computer Science', 'degree_type' => 'bachelor', 'field_of_study' => 'IT', 'duration_years' => 4, 'tuition_per_year' => 55000, 'currency' => 'NGN', 'intake_periods' => ['Fall'], 'admission_requirements' => ['JAMB UTME', '5 O\'Level Credits']],
                        ]],
                    ['name' => 'Covenant University', 'location' => 'Ota', 'type' => 'private', 'ranking' => '#1 in Nigeria (THE)', 'website' => 'https://covenantuniversity.edu.ng', 'application_portal' => 'https://adm.covenantuniversity.edu.ng', 'description' => 'Leading private university focused on leadership and excellence.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-07-31'],
                    ['name' => 'University of Ibadan (UI)', 'location' => 'Ibadan', 'type' => 'public', 'ranking' => 'Oldest in Nigeria', 'website' => 'https://ui.edu.ng', 'application_portal' => 'https://admissions.ui.edu.ng', 'description' => 'Nigeria\'s first university, known for research and legacy.', 'admission_opening_date' => '2026-05-01', 'admission_deadline_date' => '2026-08-15'],
                    ['name' => 'Obafemi Awolowo University (OAU)', 'location' => 'Ile-Ife', 'type' => 'public', 'ranking' => 'Culture and Tech Hub', 'website' => 'https://oauife.edu.ng', 'application_portal' => 'https://admissions.oauife.edu.ng', 'description' => 'Regarded as one of the most beautiful campuses in Africa.', 'admission_opening_date' => '2026-06-15', 'admission_deadline_date' => '2026-09-15'],
                    ['name' => 'University of Nigeria Nsukka (UNN)', 'location' => 'Nsukka', 'type' => 'public', 'ranking' => 'Restoring the Dignity of Man', 'website' => 'https://unn.edu.ng', 'application_portal' => 'https://unnportal.unn.edu.ng', 'description' => 'The first indigenous university in Nigeria.', 'admission_opening_date' => '2026-07-01', 'admission_deadline_date' => '2026-09-30'],
                    ['name' => 'Ahmadu Bello University (ABU)', 'location' => 'Zaria', 'type' => 'public', 'ranking' => 'Largest in SSA', 'website' => 'https://abu.edu.ng', 'application_portal' => 'https://putme.abu.edu.ng', 'description' => 'A major center for academic excellence in northern Nigeria.', 'admission_opening_date' => '2026-08-01', 'admission_deadline_date' => '2026-10-31'],
                    ['name' => 'Babcock University', 'location' => 'Ilishan-Remo', 'type' => 'private', 'ranking' => 'Top Private Medic', 'website' => 'https://babcock.edu.ng', 'application_portal' => 'https://admissions.babcock.edu.ng', 'description' => 'A leading private Adventist university.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-08-15'],
                    ['name' => 'University of Benin (UNIBEN)', 'location' => 'Benin City', 'type' => 'public', 'ranking' => 'Greatest Uniben', 'website' => 'https://uniben.edu', 'application_portal' => 'https://uniben.waeup.org', 'description' => 'One of Nigeria\'s first-generation federal universities.', 'admission_opening_date' => '2026-06-01', 'admission_deadline_date' => '2026-09-01'],
                    ['name' => 'Landmark University', 'location' => 'Omu-Aran', 'type' => 'private', 'ranking' => 'Agrarian Revolution', 'website' => 'https://lmu.edu.ng', 'application_portal' => 'https://admission.lmu.edu.ng', 'description' => 'Dedicated to an agrarian revolution in Africa.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-08-31'],
                    ['name' => 'Lagos State University (LASU)', 'location' => 'Ojo', 'type' => 'public', 'ranking' => 'Top State University', 'website' => 'https://lasu.edu.ng', 'application_portal' => 'https://services.lidc.lasu.edu.ng/admissionscreening', 'description' => 'The pride of Lagos State.', 'admission_opening_date' => '2026-07-15', 'admission_deadline_date' => '2026-10-15'],
                    ['name' => 'Baze University', 'location' => 'Abuja', 'type' => 'private', 'ranking' => 'Premium Private Abuja', 'website' => 'https://bazeuniversity.edu.ng', 'application_portal' => 'https://bazeuniversity.edu.ng/admissions', 'description' => 'High-quality private education in the nation\'s capital.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-09-15'],
                    ['name' => 'Pan-Atlantic University', 'location' => 'Lekki', 'type' => 'private', 'ranking' => '#1 for Media/Biz', 'website' => 'https://pau.edu.ng', 'application_portal' => 'https://apply.pau.edu.ng', 'description' => 'Niche university focused on media, communication and business.', 'admission_opening_date' => '2026-01-15', 'admission_deadline_date' => '2026-08-15'],
                    ['name' => 'American University of Nigeria (AUN)', 'location' => 'Yola', 'type' => 'private', 'ranking' => 'Dev University', 'website' => 'https://aun.edu.ng', 'application_portal' => 'https://aun.edu.ng/index.php/admissions', 'description' => 'Africa\'s first development university.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-08-30'],
                    ['name' => 'Rivers State University (RSU)', 'location' => 'Port Harcourt', 'type' => 'public', 'ranking' => 'South South Leader', 'website' => 'https://rsu.edu.ng', 'application_portal' => 'https://rsu.edu.ng/apply', 'description' => 'Leading technical university in the South-South region.', 'admission_opening_date' => '2026-07-01', 'admission_deadline_date' => '2026-09-30'],
                    ['name' => 'Afe Babalola University (ABUAD)', 'location' => 'Ado-Ekiti', 'type' => 'private', 'ranking' => '#1 THE Nigeria 2024', 'website' => 'https://abuad.edu.ng', 'application_portal' => 'https://admissions.abuad.edu.ng', 'description' => 'A world-class private university in Ekiti State.', 'admission_opening_date' => '2026-01-01', 'admission_deadline_date' => '2026-08-31'],
                ],
            ],
        ];
    }
}