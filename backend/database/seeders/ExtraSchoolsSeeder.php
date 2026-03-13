<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\School;
use App\Models\SchoolProgram;
use Illuminate\Database\Seeder;

class ExtraSchoolsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'GB' => [
                ['name' => 'University of Manchester', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Computer Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Computer Science', 'duration_years' => 3, 'tuition_per_year' => 25000, 'currency' => 'GBP', 'intake_periods' => ['Sept']],
                        ['name' => 'MSc Engineering', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 1, 'tuition_per_year' => 28000, 'currency' => 'GBP', 'intake_periods' => ['Sept']],
                    ]],
                ['name' => 'University College London', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Artificial Intelligence', 'degree_type' => 'bachelor', 'field_of_study' => 'AI', 'duration_years' => 3, 'tuition_per_year' => 31000, 'currency' => 'GBP', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'University of Bristol', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Finance', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3, 'tuition_per_year' => 24000, 'currency' => 'GBP', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Coventry University', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Business', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3, 'tuition_per_year' => 18000, 'currency' => 'GBP', 'intake_periods' => ['Jan', 'Sept']]
                    ]],
                ['name' => 'Kaplan International London', 'type' => 'language', 'programs' => [
                        ['name' => 'IELTS Prep', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1200, 'currency' => 'GBP', 'intake_periods' => ['Rolling']] // Based on 300/week for 4 weeks
                    ]],
                ['name' => 'EC English Manchester', 'type' => 'language', 'programs' => [
                        ['name' => 'Academic English', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1080, 'currency' => 'GBP', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'NL' => [
                ['name' => 'University of Amsterdam', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Business Analytics', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3, 'tuition_per_year' => 12000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Delft University of Technology', 'type' => 'public', 'programs' => [
                        ['name' => 'MSc Engineering', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 17000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Erasmus University', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Economics', 'degree_type' => 'bachelor', 'field_of_study' => 'Economics', 'duration_years' => 3, 'tuition_per_year' => 14000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Utrecht University', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Data Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Data Science', 'duration_years' => 3, 'tuition_per_year' => 13000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'UvA Language Centre', 'type' => 'language', 'programs' => [
                        ['name' => 'Dutch Prep', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1200, 'currency' => 'EUR', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'NO' => [
                ['name' => 'University of Oslo', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Data Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Data Science', 'duration_years' => 3, 'tuition_per_year' => 0, 'currency' => 'NOK', 'intake_periods' => ['Aug']]
                    ]],
                ['name' => 'NTNU', 'type' => 'public', 'programs' => [
                        ['name' => 'MSc Engineering', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 2, 'tuition_per_year' => 0, 'currency' => 'NOK', 'intake_periods' => ['Aug']]
                    ]],
                ['name' => 'BI Norwegian Business School', 'type' => 'private', 'programs' => [
                        ['name' => 'BSc Business', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3, 'tuition_per_year' => 140000, 'currency' => 'NOK', 'intake_periods' => ['Aug']]
                    ]],
                ['name' => 'Alfaskolen Oslo', 'type' => 'language', 'programs' => [
                        ['name' => 'Norwegian Language', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 10000, 'currency' => 'NOK', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'IE' => [
                ['name' => 'Trinity College Dublin', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Computer Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Computer Science', 'duration_years' => 4, 'tuition_per_year' => 21000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'University College Dublin', 'type' => 'public', 'programs' => [
                        ['name' => 'MSc Business Analytics', 'degree_type' => 'master', 'field_of_study' => 'Business', 'duration_years' => 1, 'tuition_per_year' => 24000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'University of Galway', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Engineering', 'degree_type' => 'bachelor', 'field_of_study' => 'Engineering', 'duration_years' => 4, 'tuition_per_year' => 20000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Atlantic Language School', 'type' => 'language', 'programs' => [
                        ['name' => 'IELTS Prep', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1000, 'currency' => 'EUR', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'AU' => [
                ['name' => 'University of Melbourne', 'type' => 'public', 'programs' => [
                        ['name' => 'MD Medicine', 'degree_type' => 'master', 'field_of_study' => 'Medicine', 'duration_years' => 4, 'tuition_per_year' => 40000, 'currency' => 'AUD', 'intake_periods' => ['Feb', 'July']]
                    ]],
                ['name' => 'University of Sydney', 'type' => 'public', 'programs' => [
                        ['name' => 'LLB Law', 'degree_type' => 'bachelor', 'field_of_study' => 'Law', 'duration_years' => 4, 'tuition_per_year' => 45000, 'currency' => 'AUD', 'intake_periods' => ['Feb', 'July']]
                    ]],
                ['name' => 'Monash University', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc IT', 'degree_type' => 'bachelor', 'field_of_study' => 'IT', 'duration_years' => 3, 'tuition_per_year' => 38000, 'currency' => 'AUD', 'intake_periods' => ['Feb', 'July']]
                    ]],
                ['name' => 'Kaplan Australia', 'type' => 'language', 'programs' => [
                        ['name' => 'IELTS Prep', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1600, 'currency' => 'AUD', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'NZ' => [
                ['name' => 'University of Auckland', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc IT', 'degree_type' => 'bachelor', 'field_of_study' => 'IT', 'duration_years' => 3, 'tuition_per_year' => 38000, 'currency' => 'NZD', 'intake_periods' => ['Feb', 'July']]
                    ]],
                ['name' => 'Victoria University Wellington', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Data Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Data Science', 'duration_years' => 3, 'tuition_per_year' => 34000, 'currency' => 'NZD', 'intake_periods' => ['Feb', 'July']]
                    ]],
                ['name' => 'Auckland English Academy', 'type' => 'language', 'programs' => [
                        ['name' => 'IELTS Prep', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1400, 'currency' => 'NZD', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'CA' => [
                ['name' => 'University of Waterloo', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Computer Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Computer Science', 'duration_years' => 4, 'tuition_per_year' => 45000, 'currency' => 'CAD', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'York University', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Business', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 4, 'tuition_per_year' => 33000, 'currency' => 'CAD', 'intake_periods' => ['Jan', 'Sept']]
                    ]],
                ['name' => 'ILAC Toronto', 'type' => 'language', 'programs' => [
                        ['name' => 'Academic English', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1200, 'currency' => 'CAD', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'SE' => [
                ['name' => 'Lund University', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Engineering', 'degree_type' => 'bachelor', 'field_of_study' => 'Engineering', 'duration_years' => 3, 'tuition_per_year' => 14000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'KTH Royal Institute', 'type' => 'public', 'programs' => [
                        ['name' => 'MSc AI', 'degree_type' => 'master', 'field_of_study' => 'AI', 'duration_years' => 2, 'tuition_per_year' => 18000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Uppsala University', 'type' => 'public', 'programs' => [
                        ['name' => 'MD Medicine', 'degree_type' => 'master', 'field_of_study' => 'Medicine', 'duration_years' => 5, 'tuition_per_year' => 16000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Swedish Institute Language', 'type' => 'language', 'programs' => [
                        ['name' => 'Swedish Language', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1000, 'currency' => 'EUR', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'FI' => [
                ['name' => 'University of Helsinki', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Data Science', 'degree_type' => 'bachelor', 'field_of_study' => 'Data Science', 'duration_years' => 3, 'tuition_per_year' => 15000, 'currency' => 'EUR', 'intake_periods' => ['Aug']]
                    ]],
                ['name' => 'Aalto University', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Engineering', 'degree_type' => 'bachelor', 'field_of_study' => 'Engineering', 'duration_years' => 3, 'tuition_per_year' => 18000, 'currency' => 'EUR', 'intake_periods' => ['Aug']]
                    ]],
                ['name' => 'Tampere University', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc IT', 'degree_type' => 'bachelor', 'field_of_study' => 'IT', 'duration_years' => 3, 'tuition_per_year' => 12000, 'currency' => 'EUR', 'intake_periods' => ['Aug']]
                    ]],
                ['name' => 'Aalto Language Centre', 'type' => 'language', 'programs' => [
                        ['name' => 'Finnish Language', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 800, 'currency' => 'EUR', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'AT' => [
                ['name' => 'University of Vienna', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Law', 'degree_type' => 'bachelor', 'field_of_study' => 'Law', 'duration_years' => 3, 'tuition_per_year' => 1500, 'currency' => 'EUR', 'intake_periods' => ['Oct']]
                    ]],
                ['name' => 'TU Vienna', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Engineering', 'degree_type' => 'bachelor', 'field_of_study' => 'Engineering', 'duration_years' => 3, 'tuition_per_year' => 1500, 'currency' => 'EUR', 'intake_periods' => ['Oct']]
                    ]],
                ['name' => 'ActiLingua Vienna', 'type' => 'language', 'programs' => [
                        ['name' => 'German Language', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1000, 'currency' => 'EUR', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'FR' => [
                ['name' => 'HEC Paris', 'type' => 'private', 'programs' => [
                        ['name' => 'MBA', 'degree_type' => 'master', 'field_of_study' => 'Business', 'duration_years' => 1, 'tuition_per_year' => 60000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Alliance Française', 'type' => 'language', 'programs' => [
                        ['name' => 'French Prep', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 1000, 'currency' => 'EUR', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'ES' => [
                ['name' => 'Autonomous University Madrid', 'type' => 'public', 'programs' => [
                        ['name' => 'BSc Economics', 'degree_type' => 'bachelor', 'field_of_study' => 'Economics', 'duration_years' => 4, 'tuition_per_year' => 6000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Don Quijote School', 'type' => 'language', 'programs' => [
                        ['name' => 'Spanish Language', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 800, 'currency' => 'EUR', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'IT' => [
                ['name' => 'Sapienza University Rome', 'type' => 'public', 'programs' => [
                        ['name' => 'MD Medicine', 'degree_type' => 'master', 'field_of_study' => 'Medicine', 'duration_years' => 6, 'tuition_per_year' => 3000, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]],
                ['name' => 'Scuola Leonardo da Vinci', 'type' => 'language', 'programs' => [
                        ['name' => 'Italian Language', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 880, 'currency' => 'EUR', 'intake_periods' => ['Rolling']]
                    ]],
            ],
            'NG' => [
                ['name' => 'University of Ibadan', 'type' => 'public', 'programs' => [
                        ['name' => 'MBBS Medicine', 'degree_type' => 'bachelor', 'field_of_study' => 'Medicine', 'duration_years' => 6, 'tuition_per_year' => 350000, 'currency' => 'NGN', 'intake_periods' => ['Fall']]
                    ]],
                ['name' => 'MOD IELTS', 'type' => 'language', 'programs' => [
                        ['name' => 'IELTS Prep', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 200000, 'currency' => 'NGN', 'intake_periods' => ['Rolling']]
                    ]],
                ['name' => 'British Council Nigeria', 'type' => 'language', 'programs' => [
                        ['name' => 'IELTS Testing & Prep', 'degree_type' => 'certificate', 'field_of_study' => 'Language', 'duration_years' => 0.1, 'tuition_per_year' => 250000, 'currency' => 'NGN', 'intake_periods' => ['Rolling']]
                    ]],
            ]
        ];

        foreach ($data as $countryCode => $schools) {
            $country = Country::where('code', $countryCode)->first();
            if (!$country)
                continue;

            foreach ($schools as $schoolData) {
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

            $this->command->info("Seeded EXTRA schools and language schools for: {$country->name}");
        }
    }
}