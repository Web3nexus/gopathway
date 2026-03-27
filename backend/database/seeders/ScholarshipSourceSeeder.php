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
                    'item_selector' => 'article.post',
                    'fields' => [
                        'title'            => 'h2.entry-title a',
                        'description'      => 'div.entry-summary',
                        'application_link' => 'h2.entry-title a',
                        'source_url'       => 'h2.entry-title a',
                    ],
                ],
            ],

            // School/university source — targets the university listing
            [
                'name' => 'Free-Apply - International Universities',
                'base_url' => 'https://free-apply.com/en/university/',
                'crawl_type' => 'school',
                'is_active' => true,
                'scraping_rules' => [
                    'item_selector' => '.university-item',
                    'fields' => [
                        'school_name'            => '.university-name',
                        'location'               => '.university-location',
                        'official_website_link'  => '.university-link a',
                        'description'            => '.university-description',
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
