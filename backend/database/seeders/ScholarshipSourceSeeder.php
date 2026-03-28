<?php

namespace Database\Seeders;

use App\Models\ScholarshipSource;
use Illuminate\Database\Seeder;

class ScholarshipSourceSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            // Scholarship source
            [
                'name' => 'Scholars4Dev - International Scholarships',
                'base_url' => 'https://www.scholars4dev.com/',
                'crawl_type' => 'scholarship',
                'is_active' => true,
                'scraping_rules' => [
                    'item_selector' => '.post',
                    'fields' => [
                        'title'            => 'h2 a',
                        'description'      => 'div.entry > p',
                        'application_link' => 'h2 a',
                        'source_url'       => 'h2 a',
                    ],
                ],
            ],

            // School/university source — targets the university listing
            [
                'name' => 'Free-Apply - International Universities',
                'base_url' => 'https://free-apply.com/en/search/uk/universities',
                'crawl_type' => 'school',
                'is_active' => false,
                'scraping_rules' => [
                    'item_selector' => '.university-item, .card, .bg-white.rounded-lg.shadow', // We'll need to figure this out if it works
                    'fields' => [
                        'school_name'            => '.university-name, h3',
                        'location'               => '.university-location, .location',
                        'official_website_link'  => '.university-link a, a',
                        'description'            => '.university-description, p',
                    ],
                ],
            ],
        ];

        foreach ($sources as $source) {
            ScholarshipSource::updateOrCreate(
                ['base_url' => $source['base_url']],
                $source
            );
        }

        $this->command->info('Scholarship & School sources seeded!');
    }
}
