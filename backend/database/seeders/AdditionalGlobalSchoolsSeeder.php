<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\School;
use App\Models\SchoolProgram;
use Illuminate\Database\Seeder;

class AdditionalGlobalSchoolsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'ES' => [
                [
                    'name' => 'Universidad de Valencia', 
                    'type' => 'public', 
                    'location' => 'Valencia, Spain',
                    'ranking' => 'Top 300 QS World',
                    'website' => 'https://www.uv.es/',
                    'application_portal' => 'https://www.uv.es/admission',
                    'description' => 'One of the oldest and largest universities in Spain, known for its affordable tuition, extensive scholarship programs, and high international attendance.',
                    'programs' => [
                        ['name' => 'Bachelor in International Business', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 4, 'tuition_per_year' => 1200, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                        ['name' => 'MSc in Data Science', 'degree_type' => 'master', 'field_of_study' => 'Data Science', 'duration_years' => 1.5, 'tuition_per_year' => 2000, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                    ]
                ],
                [
                    'name' => 'University of Alicante', 
                    'type' => 'public', 
                    'location' => 'Alicante, Spain',
                    'ranking' => 'Top 800 QS World',
                    'website' => 'https://www.ua.es/',
                    'application_portal' => 'https://si.ua.es/en/admission/',
                    'description' => 'A highly international, affordable public university located on the Mediterranean coast with vast scholarship opportunities for non-EU students.',
                    'programs' => [
                        ['name' => 'Bachelor of Computer Engineering', 'degree_type' => 'bachelor', 'field_of_study' => 'Computer Science', 'duration_years' => 4, 'tuition_per_year' => 900, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                        ['name' => 'Master in Economics', 'degree_type' => 'master', 'field_of_study' => 'Economics', 'duration_years' => 1, 'tuition_per_year' => 1800, 'currency' => 'EUR', 'intake_periods' => ['Sept']]
                    ]
                ],
                [
                    'name' => 'University of Granada', 
                    'type' => 'public', 
                    'location' => 'Granada, Spain',
                    'ranking' => 'Top 500 QS World',
                    'website' => 'https://www.ugr.es/',
                    'application_portal' => 'https://internacional.ugr.es/',
                    'description' => 'A popular Erasmus destination, the University of Granada offers very low tuition rates (~€800/yr for bachelor degrees) and a vibrant student life.',
                    'programs' => [
                        ['name' => 'Bachelor of Modern Languages', 'degree_type' => 'bachelor', 'field_of_study' => 'Language', 'duration_years' => 4, 'tuition_per_year' => 850, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                    ]
                ],
            ],
            'DE' => [
                [
                    'name' => 'Free University of Berlin', 
                    'type' => 'public', 
                    'location' => 'Berlin, Germany',
                    'ranking' => 'Top 100 QS World',
                    'website' => 'https://www.fu-berlin.de/',
                    'application_portal' => 'https://www.fu-berlin.de/en/studium/bewerbung/',
                    'description' => 'A leading research university offering tuition-free education for international students. Excellent DAAD scholarship availability.',
                    'programs' => [
                        ['name' => 'MSc Data Science', 'degree_type' => 'master', 'field_of_study' => 'Data Science', 'duration_years' => 2, 'tuition_per_year' => 0, 'currency' => 'EUR', 'intake_periods' => ['Oct']],
                        ['name' => 'Bachelor of Global History', 'degree_type' => 'bachelor', 'field_of_study' => 'Arts', 'duration_years' => 3, 'tuition_per_year' => 0, 'currency' => 'EUR', 'intake_periods' => ['Oct']]
                    ]
                ],
                [
                    'name' => 'RWTH Aachen University', 
                    'type' => 'public', 
                    'location' => 'Aachen, Germany',
                    'ranking' => 'Top 150 QS World',
                    'website' => 'https://www.rwth-aachen.de/',
                    'application_portal' => 'https://www.rwth-aachen.de/go/id/egq/',
                    'description' => 'The largest technical university in Germany. Exceptional engineering programs with zero tuition fees (only minor administrative contributions).',
                    'programs' => [
                        ['name' => 'MSc Automotive Engineering', 'degree_type' => 'master', 'field_of_study' => 'Engineering', 'duration_years' => 1.5, 'tuition_per_year' => 0, 'currency' => 'EUR', 'intake_periods' => ['Oct', 'April']],
                    ]
                ],
            ],
            'IT' => [
                [
                    'name' => 'University of Padua', 
                    'type' => 'public', 
                    'location' => 'Padua, Italy',
                    'ranking' => 'Top 250 QS World',
                    'website' => 'https://www.unipd.it/',
                    'application_portal' => 'https://www.unipd.it/en/how-apply',
                    'description' => 'Historic top-tier Italian university with Income-based DSU scholarships that cover tuition and provide living stipends, making it essentially free for many.',
                    'programs' => [
                        ['name' => 'Bachelor in Information Engineering', 'degree_type' => 'bachelor', 'field_of_study' => 'Engineering', 'duration_years' => 3, 'tuition_per_year' => 2600, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                    ]
                ],
                [
                    'name' => 'University of Pisa', 
                    'type' => 'public', 
                    'location' => 'Pisa, Italy',
                    'ranking' => 'Top 400 QS World',
                    'website' => 'https://www.unipi.it/',
                    'application_portal' => 'https://www.unipi.it/index.php/study',
                    'description' => 'Highly affordable public university in Tuscany with generous DSU Regional Scholarships and extremely low tuition scaled to family income.',
                    'programs' => [
                        ['name' => 'MSc Artificial Intelligence', 'degree_type' => 'master', 'field_of_study' => 'AI', 'duration_years' => 2, 'tuition_per_year' => 2400, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                    ]
                ],
            ],
            'PT' => [
                [
                    'name' => 'University of Porto', 
                    'type' => 'public', 
                    'location' => 'Porto, Portugal',
                    'ranking' => 'Top 300 QS World',
                    'website' => 'https://sigarra.up.pt/up/en/',
                    'application_portal' => 'https://sigarra.up.pt/up/en/WEB_BASE.GERA_PAGINA?P_pagina=1000720',
                    'description' => 'One of Portugal\'s largest and most reputed institutions. Combines excellent student life with among the lowest tuition fees in Western Europe.',
                    'programs' => [
                        ['name' => 'MSc Software Engineering', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 3500, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                    ]
                ],
                [
                    'name' => 'University of Coimbra', 
                    'type' => 'public', 
                    'location' => 'Coimbra, Portugal',
                    'ranking' => 'Top 400 QS World',
                    'website' => 'https://www.uc.pt/',
                    'application_portal' => 'https://www.uc.pt/en/applications/',
                    'description' => 'UNESCO World Heritage site and the oldest university in Portugal. Highly international and offers competitive tuition rates.',
                    'programs' => [
                        ['name' => 'Bachelor of Business and Economics', 'degree_type' => 'bachelor', 'field_of_study' => 'Business', 'duration_years' => 3, 'tuition_per_year' => 3000, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                    ]
                ],
            ],
            'FR' => [
                [
                    'name' => 'University of Strasbourg', 
                    'type' => 'public', 
                    'location' => 'Strasbourg, France',
                    'ranking' => 'Top 400 QS World',
                    'website' => 'https://www.unistra.fr/',
                    'application_portal' => 'https://www.unistra.fr/formation/admission-inscription',
                    'description' => 'Public French university with highly subsidized tuition fees from the government (~€277/yr for master\'s for EU, exceptionally low for non-EU too) and strong ties to international institutions.',
                    'programs' => [
                        ['name' => 'MSc Life Sciences', 'degree_type' => 'master', 'field_of_study' => 'Biology', 'duration_years' => 2, 'tuition_per_year' => 3770, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                    ]
                ],
                [
                    'name' => 'Aix-Marseille University', 
                    'type' => 'public', 
                    'location' => 'Marseille, France',
                    'ranking' => 'Top 500 QS World',
                    'website' => 'https://www.univ-amu.fr/',
                    'application_portal' => 'https://www.univ-amu.fr/en/public/admission-and-registration',
                    'description' => 'The largest university in the Francophone world by the number of its students and its budget, offering extremely cheap public education.',
                    'programs' => [
                        ['name' => 'Bachelor of Mathematics & CS', 'degree_type' => 'bachelor', 'field_of_study' => 'Computer Science', 'duration_years' => 3, 'tuition_per_year' => 2770, 'currency' => 'EUR', 'intake_periods' => ['Sept']],
                    ]
                ],
            ],
            'CA' => [
                [
                    'name' => 'Memorial University of Newfoundland', 
                    'type' => 'public', 
                    'location' => 'St. John\'s, NL, Canada',
                    'ranking' => 'Top 800 QS World',
                    'website' => 'https://www.mun.ca/',
                    'application_portal' => 'https://www.mun.ca/undergrad/apply/',
                    'description' => 'Known for having some of the lowest tuition rates for international students in Canada while providing a high-quality education and abundant research funding.',
                    'programs' => [
                        ['name' => 'BEng Ocean and Naval Architectural', 'degree_type' => 'bachelor', 'field_of_study' => 'Engineering', 'duration_years' => 5, 'tuition_per_year' => 11500, 'currency' => 'CAD', 'intake_periods' => ['Sept', 'Jan']],
                    ]
                ],
                [
                    'name' => 'University of Saskatchewan', 
                    'type' => 'public', 
                    'location' => 'Saskatoon, SK, Canada',
                    'ranking' => 'Top 500 QS World',
                    'website' => 'https://www.usask.ca/',
                    'application_portal' => 'https://admissions.usask.ca/',
                    'description' => 'A member of the U15 medical-doctoral research-intensive universities in Canada, and recognized for excellent scholarship opportunities and relatively low living costs.',
                    'programs' => [
                        ['name' => 'MSc Computer Science', 'degree_type' => 'master', 'field_of_study' => 'Computer Science', 'duration_years' => 2, 'tuition_per_year' => 9500, 'currency' => 'CAD', 'intake_periods' => ['Sept', 'Jan']],
                    ]
                ],
            ],
        ];

        foreach ($data as $countryCode => $schools) {
            $country = Country::where('code', $countryCode)->first();
            if (!$country) continue;

            foreach ($schools as $schoolData) {
                $programs = $schoolData['programs'] ?? [];
                unset($schoolData['programs']);

                // Create or update the school
                $school = School::updateOrCreate(
                    ['country_id' => $country->id, 'name' => $schoolData['name']],
                    array_merge($schoolData, ['country_id' => $country->id])
                );

                // Create or update programs
                foreach ($programs as $programData) {
                    SchoolProgram::updateOrCreate(
                        ['school_id' => $school->id, 'name' => $programData['name']],
                        array_merge($programData, ['school_id' => $school->id])
                    );
                }
            }

            $this->command->info("Seeded ADDITIONAL schools for: {$country->name}");
        }
    }
}
