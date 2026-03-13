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
                    ['name' => 'University of Lisbon', 'location' => 'Lisbon', 'type' => 'public', 'ranking' => 'Top 350 QS', 'website' => 'https://ulisboa.pt', 'application_portal' => 'https://www.ulisboa.pt/en/candidatos', 'description' => 'Portugal\'s largest university offering wide range of programmes.', 'programs' => [
                            ['name' => 'MSc Management', 'degree_type' => 'master', 'field_of_study' => 'Business', 'duration_years' => 2, 'tuition_per_year' => 5000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.0, 'admission_requirements' => ['Bachelor degree in Management or Economics']],
                            ['name' => 'BSc Computer Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Computer Science', 'duration_years' => 3, 'tuition_per_year' => 1500, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'admission_requirements' => ['Secondary education certificate', 'National admission exam']],
                        ]],
                    ['name' => 'Nova University Lisbon', 'location' => 'Lisbon', 'type' => 'public', 'ranking' => 'Top 500 QS', 'website' => 'https://unl.pt', 'application_portal' => 'https://www.unl.pt/en', 'description' => 'Young and dynamic university known for Nova SBE business school.', 'programs' => [
                            ['name' => 'MSc Finance', 'degree_type' => 'master', 'field_of_study' => 'Finance', 'duration_years' => 1.5, 'tuition_per_year' => 9500, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['Bachelor in Business, Economics or related']],
                        ]],
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
                    ['name' => 'University of Auckland', 'location' => 'Auckland', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://auckland.ac.nz', 'application_portal' => 'https://www.auckland.ac.nz/en/study/applying-to-auckland.html', 'description' => 'New Zealand\'s leading research-intensive university.', 'programs' => [
                            ['name' => 'Master of Engineering', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 36000, 'currency' => 'NZD', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 6.5, 'admission_requirements' => ['Relevant engineering degree']],
                            ['name' => 'Bachelor of Commerce', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3, 'tuition_per_year' => 31500, 'currency' => 'NZD', 'intake_periods' => ['Fall'], 'ielts_min' => 6.0, 'admission_requirements' => ['High school certificate']],
                        ]],
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
                    ['name' => 'Delft University of Technology', 'location' => 'Delft', 'type' => 'public', 'ranking' => 'Top 60 QS', 'website' => 'https://tudelft.nl', 'application_portal' => 'https://www.tudelft.nl/en/education/admission-and-application/', 'description' => 'The Netherlands\' top technical university, world-class for engineering.', 'programs' => [
                            ['name' => 'MSc Sustainable Energy Technology', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 20000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['Bachelor in Engineering or Science', 'Math background']],
                        ]],
                    ['name' => 'University of Amsterdam', 'location' => 'Amsterdam', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://uva.nl', 'application_portal' => 'https://www.uva.nl/en/education/master-s/masters.html', 'description' => 'A comprehensive research university in Europe\'s most international city.', 'programs' => [
                            ['name' => 'MSc Artificial Intelligence', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 18000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 7.0, 'admission_requirements' => ['BSc in CS, Mathematics or AI related', 'Programming proficiency']],
                        ]],
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
                    ['name' => 'Sorbonne University', 'location' => 'Paris', 'type' => 'public', 'ranking' => 'Top 80 QS', 'website' => 'https://sorbonne-universite.fr', 'application_portal' => 'https://www.sorbonne-universite.fr/en/education/applying-sorbonne-university', 'description' => 'One of the world\'s oldest and most renowned universities.', 'programs' => [
                            ['name' => 'MSc Sciences de la Vie (Life Sciences)', 'degree_type' => 'master', 'field_of_study' => 'Biology', 'duration_years' => 2, 'tuition_per_year' => 3770, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in Life Sciences']],
                        ]],
                    ['name' => 'Sciences Po', 'location' => 'Paris', 'type' => 'public', 'ranking' => 'Top 300 QS', 'website' => 'https://sciencespo.fr', 'application_portal' => 'https://www.sciencespo.fr/admissions/en/', 'description' => 'Elite French institution specializing in social sciences and international affairs.', 'programs' => [
                            ['name' => 'Master in International Affairs', 'degree_type' => 'master', 'field_of_study' => 'Political Science', 'duration_years' => 2, 'tuition_per_year' => 13560, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 7.0, 'admission_requirements' => ['Bachelor in Social Sciences', 'Motivation letter', 'CV']],
                        ]],
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
                    ['name' => 'Politecnico di Milano', 'location' => 'Milan', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://polimi.it', 'application_portal' => 'https://www.polimi.it/en/prospective-students/', 'description' => 'Italy\'s leading technical university, globally recognized for design and engineering.', 'programs' => [
                            ['name' => 'MSc Computer Science and Engineering', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 3748, 'currency' => 'EUR', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in CS or Electronics']],
                        ]],
                    ['name' => 'University of Bologna', 'location' => 'Bologna', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://unibo.it', 'application_portal' => 'https://www.unibo.it/en/teaching/degree-programmes/', 'description' => 'The world\'s oldest university, founded in 1088.', 'programs' => [
                            ['name' => 'MSc Artificial Intelligence', 'degree_type' => 'master', 'field_of_study' => 'AI', 'duration_years' => 2, 'tuition_per_year' => 2800, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in Computer Science or Mathematics']],
                        ]],
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
                    ['name' => 'Karolinska Institute', 'location' => 'Stockholm', 'type' => 'public', 'ranking' => 'Top 50 QS', 'website' => 'https://ki.se', 'application_portal' => 'https://ki.se/en/education/study-here', 'description' => 'World-leading medical university and home of the Nobel Prize in Physiology or Medicine.', 'programs' => [
                            ['name' => 'MSc Biomedicine', 'degree_type' => 'master', 'field_of_study' => 'Biology', 'duration_years' => 2, 'tuition_per_year' => 195000, 'currency' => 'SEK', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in Biomedicine or related']],
                        ]],
                    ['name' => 'Stockholm University', 'location' => 'Stockholm', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://su.se', 'application_portal' => 'https://www.su.se/english/education/', 'description' => 'A comprehensive research university in Scandinavia\'s dynamic capital.', 'programs' => [
                            ['name' => 'MSc Sustainable Development', 'degree_type' => 'master', 'field_of_study' => 'Environmental Science', 'duration_years' => 2, 'tuition_per_year' => 150000, 'currency' => 'SEK', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['Bachelor in relevant field']],
                        ]],
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
                    ['name' => 'University of Helsinki', 'location' => 'Helsinki', 'type' => 'public', 'ranking' => 'Top 100 QS', 'website' => 'https://helsinki.fi', 'application_portal' => 'https://www.helsinki.fi/en/admissions', 'description' => 'Finland\'s oldest and largest university with strong research.', 'programs' => [
                            ['name' => 'MSc Computer Science', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 15000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in CS or Mathematics']],
                        ]],
                    ['name' => 'Aalto University', 'location' => 'Espoo', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://aalto.fi', 'application_portal' => 'https://www.aalto.fi/en/study-at-aalto', 'description' => 'A multidisciplinary university combining business, tech and arts.', 'programs' => [
                            ['name' => 'MSc Information Networks', 'degree_type' => 'master', 'field_of_study' => 'IT', 'duration_years' => 2, 'tuition_per_year' => 15000, 'currency' => 'EUR', 'intake_periods' => ['Fall'], 'ielts_min' => 7.0, 'admission_requirements' => ['Bachelor in Engineering or Sciences']],
                        ]],
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
                    ['name' => 'University of Oslo', 'location' => 'Oslo', 'type' => 'public', 'ranking' => 'Top 150 QS', 'website' => 'https://uio.no', 'application_portal' => 'https://www.uio.no/english/studies/application/', 'description' => 'Norway\'s oldest and most esteemed university.', 'programs' => [
                            ['name' => 'MSc Informatics', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 0, 'currency' => 'NOK', 'intake_periods' => ['Fall'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in CS or Mathematics']],
                        ]],
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
                    ['name' => 'University of Vienna', 'location' => 'Vienna', 'type' => 'public', 'ranking' => 'Top 200 QS', 'website' => 'https://univie.ac.at', 'application_portal' => 'https://www.univie.ac.at/en/studying/apply/', 'description' => 'Austria\'s largest and oldest university, founded in 1365.', 'programs' => [
                            ['name' => 'MSc Computer Science', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 1500, 'currency' => 'EUR', 'intake_periods' => ['Fall', 'Spring'], 'ielts_min' => 6.5, 'admission_requirements' => ['BSc in CS or Mathematics']],
                        ]],
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
                    ['name' => 'University of Lagos', 'location' => 'Lagos, Lagos State', 'type' => 'public', 'ranking' => 'Top African Universities', 'website' => 'https://unilag.edu.ng', 'application_portal' => 'https://portal.unilag.edu.ng', 'description' => 'Nigeria\'s premier university and one of Africa\'s best.', 'programs' => [
                            ['name' => 'BSc Computer Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Computer Science', 'duration_years' => 4, 'tuition_per_year' => 50000, 'currency' => 'NGN', 'intake_periods' => ['Fall'], 'admission_requirements' => ['JAMB UTME', 'O\'Level credits in 5 subjects including Math and English', 'Post-UTME screening']],
                            ['name' => 'BSc Medicine and Surgery (MBBS)', 'degree_type' => 'bachelor', 'field_of_study' => 'Medicine', 'duration_years' => 6, 'tuition_per_year' => 150000, 'currency' => 'NGN', 'intake_periods' => ['Fall'], 'admission_requirements' => ['JAMB UTME with Biology, Chemistry, Physics', 'High score in post-UTME', 'Minimum 5 O\'Levels']],
                        ]],
                    ['name' => 'Covenant University', 'location' => 'Ota, Ogun State', 'type' => 'private', 'ranking' => '1st Private University in Africa (various rankings)', 'website' => 'https://covenantuniversity.edu.ng', 'application_portal' => 'https://covenantuniversity.edu.ng/Admissions', 'description' => 'Nigeria\'s top-ranked private university with a unique model of excellence.', 'programs' => [
                            ['name' => 'BSc Petroleum Engineering', 'degree_type' => 'bachelor', 'field_of_study' => 'Engineering', 'duration_years' => 5, 'tuition_per_year' => 750000, 'currency' => 'NGN', 'intake_periods' => ['Fall'], 'admission_requirements' => ['JAMB UTME', 'Mathematics and Physics O\'Levels', 'Covenant University Post-UTME']],
                        ]],
                ],
            ],
        ];
    }
}