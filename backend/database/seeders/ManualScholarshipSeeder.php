<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Scholarship;
use App\Models\ScholarshipSource;
use Illuminate\Database\Seeder;

class ManualScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $source = ScholarshipSource::where('name', 'like', '%Scholars4Dev%')->first();
        $sourceId = $source ? $source->id : null;

        $scholarships = [
            'PL' => [
                [
                    'title' => 'Polish Government Ignacy Łukasiewicz Scholarship',
                    'provider' => 'Polish National Agency for Academic Exchange (NAWA)',
                    'eligibility' => 'Citizens of developing countries',
                    'program_level' => 'Master, PhD',
                    'funding_type' => 'Full Funding',
                    'description' => 'A joint initiative of the Ministry of Foreign Affairs and NAWA for students in STEM, Technical, and Natural Sciences.',
                    'source_url' => 'https://nawa.gov.pl/en/students/foreign-students/the-lukasiewicz-scholarship-programme',
                ],
                [
                    'title' => 'Vistula University Merit Scholarship',
                    'provider' => 'Vistula University',
                    'eligibility' => 'International students with outstanding academic results',
                    'program_level' => 'Bachelor, Master',
                    'funding_type' => 'Tuition Discount',
                    'description' => 'Covers up to 100% of tuition fees for the first year of studies for top-performing international applicants.',
                    'source_url' => 'https://vistula.edu.pl/en/students/scholarships',
                ],
            ],
            'CH' => [
                [
                    'title' => 'Swiss Government Excellence Scholarships',
                    'provider' => 'Federal Commission for Scholarships (FCS)',
                    'eligibility' => 'Foreign researchers and artists',
                    'program_level' => 'PhD, Postdoc, Research',
                    'funding_type' => 'Full Funding',
                    'description' => 'Aimed at promoting international exchange and research cooperation between Switzerland and over 180 other countries.',
                    'source_url' => 'https://www.sbfi.admin.ch/sbfi/en/home/education/scholarships-and-grants/swiss-government-excellence-scholarships.html',
                ],
                [
                    'title' => 'ETH Excellence Scholarship & Opportunity Programme (ESOP)',
                    'provider' => 'ETH Zurich',
                    'eligibility' => 'Excellent Master\'s students',
                    'program_level' => 'Master',
                    'funding_type' => 'Full Funding',
                    'description' => 'Covers full study and living costs (CHF 12,000 per semester) and a tuition fee waiver.',
                    'source_url' => 'https://ethz.ch/students/en/studies/financial/scholarships/excellencescholarship.html',
                ],
            ],
            'MT' => [
                [
                    'title' => 'ENDEAVOUR Scholarship Scheme',
                    'provider' => 'Ministry for Education and Employment (MEDE)',
                    'eligibility' => 'Maltese and EU/EEA citizens (some non-EU paths)',
                    'program_level' => 'Master, PhD',
                    'funding_type' => 'Partial Funding',
                    'description' => 'Aims to support good quality tertiary education and to ensure that the Maltese labor market is supplied with the right skills.',
                    'source_url' => 'https://education.gov.mt/en/education/myScholarship/Pages/Endeavour.aspx',
                ],
                [
                    'title' => 'Commonwealth Scholarships for Malta',
                    'provider' => 'Commonwealth Scholarship Commission',
                    'eligibility' => 'Citizens of Commonwealth countries',
                    'program_level' => 'Master, PhD',
                    'funding_type' => 'Full Funding',
                    'description' => 'Offers scholarships for students from developing Commonwealth countries to study in Malta.',
                    'source_url' => 'https://cscuk.fcdo.gov.uk/',
                ],
            ],
        ];

        foreach ($scholarships as $countryCode => $items) {
            $country = Country::where('code', $countryCode)->first();
            if (!$country) continue;

            foreach ($items as $item) {
                Scholarship::updateOrCreate(
                    ['title' => $item['title'], 'country_id' => $country->id],
                    array_merge($item, [
                        'country_id' => $country->id,
                        'scholarship_source_id' => $sourceId,
                        'application_link' => $item['source_url'], // Added missing field
                        'status' => 'approved',
                    ])
                );
            }
        }
    }
}
