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
                    'opening_date' => '2026-03-15',
                    'funding_type' => 'Full Funding',
                    'description' => 'A joint initiative of the Ministry of Foreign Affairs and NAWA for students in STEM, Technical, and Natural Sciences.',
                    'source_url' => 'https://nawa.gov.pl/en/students/foreign-students/the-lukasiewicz-scholarship-programme',
                ],
                [
                    'title' => 'Vistula University Merit Scholarship',
                    'provider' => 'Vistula University',
                    'eligibility' => 'International students with outstanding academic results',
                    'program_level' => 'Bachelor, Master',
                    'opening_date' => '2026-05-01',
                    'funding_type' => 'Tuition Discount',
                    'description' => 'Covers up to 100% of tuition fees for the first year of studies for top-performing international applicants.',
                    'source_url' => 'https://vistula.edu.pl/en/students/scholarships',
                ],
                [
                    'title' => 'Stefan Banach Scholarship Programme',
                    'provider' => 'NAWA',
                    'eligibility' => 'Students from Eastern Partnership, Central Asia, and Western Balkan countries',
                    'program_level' => 'Master',
                    'opening_date' => '2026-03-20',
                    'funding_type' => 'Full Funding',
                    'description' => 'Supports socio-economic development of developing countries by increasing the level of education and professional qualifications of their citizens.',
                    'source_url' => 'https://nawa.gov.pl/en/students/foreign-students/the-stefan-banach-scholarship-programme',
                ],
                [
                    'title' => 'Visegrad Scholarship Program',
                    'provider' => 'International Visegrad Fund',
                    'eligibility' => 'Students from V4 countries and neighboring regions',
                    'program_level' => 'Master, Post-Master',
                    'opening_date' => '2026-02-01',
                    'funding_type' => 'Partial Funding',
                    'description' => 'Supports regional cooperation and mobility between students and researchers in the Visegrad region.',
                    'source_url' => 'https://www.visegradfund.org/apply/mobilities/visegrad-scholarship/',
                ],
            ],
            'CH' => [
                [
                    'title' => 'Swiss Government Excellence Scholarships',
                    'provider' => 'Federal Commission for Scholarships (FCS)',
                    'eligibility' => 'Foreign researchers and artists',
                    'program_level' => 'PhD, Postdoc, Research',
                    'opening_date' => '2025-08-01',
                    'funding_type' => 'Full Funding',
                    'description' => 'Aimed at promoting international exchange and research cooperation between Switzerland and over 180 other countries.',
                    'source_url' => 'https://www.sbfi.admin.ch/sbfi/en/home/education/scholarships-and-grants/swiss-government-excellence-scholarships.html',
                ],
                [
                    'title' => 'ETH Excellence Scholarship & Opportunity Programme (ESOP)',
                    'provider' => 'ETH Zurich',
                    'eligibility' => 'Excellent Master\'s students',
                    'program_level' => 'Master',
                    'opening_date' => '2025-11-01',
                    'funding_type' => 'Full Funding',
                    'description' => 'Covers full study and living costs (CHF 12,000 per semester) and a tuition fee waiver.',
                    'source_url' => 'https://ethz.ch/students/en/studies/financial/scholarships/excellencescholarship.html',
                ],
                [
                    'title' => 'University of Geneva Excellence Master Fellowships',
                    'provider' => 'University of Geneva',
                    'eligibility' => 'Highly qualified Master\'s applicants',
                    'program_level' => 'Master',
                    'opening_date' => '2026-01-01',
                    'funding_type' => 'Grant',
                    'description' => 'The Faculty of Science of the University of Geneva offers several excellence fellowships to outstanding students.',
                    'source_url' => 'https://www.unige.ch/sciences/en/enseignements/formations/masters/excellencemasterfellowships/',
                ],
                [
                    'title' => 'EPFL Excellence Fellowships',
                    'provider' => 'EPFL',
                    'eligibility' => 'Outstanding Master\'s candidates',
                    'program_level' => 'Master',
                    'opening_date' => '2025-11-01',
                    'funding_type' => 'Full Funding',
                    'description' => 'EPFL offers a limited number of excellence fellowships to students with academic records at the master level.',
                    'source_url' => 'https://www.epfl.ch/education/master/master-excellence-fellowships/',
                ],
            ],
            'MT' => [
                [
                    'title' => 'ENDEAVOUR Scholarship Scheme',
                    'provider' => 'Ministry for Education and Employment (MEDE)',
                    'eligibility' => 'Maltese and EU/EEA citizens (some non-EU paths)',
                    'program_level' => 'Master, PhD',
                    'opening_date' => '2026-03-01',
                    'funding_type' => 'Partial Funding',
                    'description' => 'Aims to support good quality tertiary education and to ensure that the Maltese labor market is supplied with the right skills.',
                    'source_url' => 'https://education.gov.mt/en/education/myScholarship/Pages/Endeavour.aspx',
                ],
                [
                    'title' => 'Commonwealth Scholarships for Malta',
                    'provider' => 'Commonwealth Scholarship Commission',
                    'eligibility' => 'Citizens of Commonwealth countries',
                    'program_level' => 'Master, PhD',
                    'opening_date' => '2026-02-01',
                    'funding_type' => 'Full Funding',
                    'description' => 'Offers scholarships for students from developing Commonwealth countries to study in Malta.',
                    'source_url' => 'https://cscuk.fcdo.gov.uk/',
                ],
                [
                    'title' => 'TESS (Tertiary Education Scholarship Scheme)',
                    'provider' => 'Ministry for Education, Sport, Youth, Research and Innovation',
                    'eligibility' => 'Maltese residents',
                    'program_level' => 'Level 7 and 8',
                    'opening_date' => '2026-05-15',
                    'funding_type' => 'Grant',
                    'description' => 'Aims to support tertiary education in various sectors, excluding tourism-related courses covered by other schemes.',
                    'source_url' => 'https://education.gov.mt/en/education/myScholarship/Pages/TESS.aspx',
                ],
                [
                    'title' => 'Malta Arts Scholarships',
                    'provider' => 'Government of Malta',
                    'eligibility' => 'Artists and creative professionals',
                    'program_level' => 'Undergraduate, Postgraduate',
                    'opening_date' => '2026-05-15',
                    'funding_type' => 'Grant',
                    'description' => 'Supports students pursuing studies in the performing arts, visual arts, and other creative disciplines.',
                    'source_url' => 'https://education.gov.mt/en/education/myScholarship/Pages/Arts-Scholarship.aspx',
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
