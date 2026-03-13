<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\ResidencyRule;
use App\Models\CvTemplate;
use Illuminate\Database\Seeder;

class ResidencyRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            'GB' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Skilled Worker Visa, Student Visa, or Graduate Visa',
                        'validity' => 'Typically 2-5 years, renewable',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'B1 English (Life in the UK Test)',
                        'income' => 'Continuous lawful employment or self-support',
                    ],
                    'citizenship_reqs' => [
                        'years' => '6',
                        'language' => 'B1 English (CEFR)',
                        'tests' => 'Life in the UK Test + English Language proof',
                    ],
                ],
                'cv' => [
                    'name' => 'UK Standard CV',
                    'format_rules' => [
                        'photo_required' => false,
                        'age_required' => false,
                        'max_pages' => 2,
                        'preferred_order' => 'Personal Summary → Work Experience → Education → Skills',
                        'notes' => 'No photo. No date of birth. Typically 2 pages max. Include a strong personal statement at the top.',
                    ],
                    'sections' => ['Personal Info', 'Personal Statement', 'Work Experience', 'Education', 'Skills', 'References'],
                ],
            ],
            'CA' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Study Permit, Work Permit, IEC, LMIA-based Work Permit',
                        'validity' => 'Usually 1-3 years',
                    ],
                    'permanent_reqs' => [
                        'years' => '3',
                        'language' => 'CLB 4 minimum (English or French)',
                        'income' => 'Tax filing history in Canada required',
                    ],
                    'citizenship_reqs' => [
                        'years' => '5',
                        'language' => 'CLB 4 (IELTS / CELPIP)',
                        'tests' => 'Citizenship Test (history, values, rights) + interview',
                    ],
                ],
                'cv' => [
                    'name' => 'Canadian Resume Standard',
                    'format_rules' => [
                        'photo_required' => false,
                        'age_required' => false,
                        'max_pages' => 2,
                        'preferred_order' => 'Contact Info → Summary → Skills → Experience → Education',
                        'notes' => 'No photo, no DOB, no marital status. Called a "Resume" not a CV. 1-2 pages standard. Use action verbs and quantify achievements.',
                    ],
                    'sections' => ['Personal Info', 'Professional Summary', 'Core Skills', 'Work Experience', 'Education', 'Certifications'],
                ],
            ],
            'DE' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Aufenthaltserlaubnis (Residence Permit) for work or study',
                        'validity' => '1-4 years depending on permit type',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'B1 German (TestDaF or Goethe Institut)',
                        'income' => 'Sufficient pension/pension contribution record + financial self-support',
                    ],
                    'citizenship_reqs' => [
                        'years' => '5',
                        'language' => 'B2 German required',
                        'tests' => 'Einbürgerungstest (civic integration) + renounce previous citizenship in most cases',
                    ],
                ],
                'cv' => [
                    'name' => 'German Lebenslauf Format',
                    'format_rules' => [
                        'photo_required' => true,
                        'age_required' => true,
                        'max_pages' => 2,
                        'preferred_order' => 'Photo + Personal Info → Work Experience → Education → Skills → Hobbies',
                        'notes' => 'A professional photo in the top-right corner is EXPECTED. Include full date of birth, marital status, and nationality. Reverse chronological order for experience. Titled "Lebenslauf".',
                    ],
                    'sections' => ['Personal Info (with photo)', 'Work Experience', 'Education', 'Skills & Languages', 'Certifications', 'Hobbies'],
                ],
            ],
            'NL' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Highly Skilled Migrant Permit, EU Blue Card, Student Residence Permit',
                        'validity' => '1-3 years with renewal',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'A2 Dutch minimum (Inburgering exam)',
                        'income' => 'Consistent income above social welfare threshold',
                    ],
                    'citizenship_reqs' => [
                        'years' => '5',
                        'language' => 'B1 Dutch (Inburgeringsexamen)',
                        'tests' => 'Civic Integration Exam (KNM test) + Language portfolio',
                    ],
                ],
                'cv' => [
                    'name' => 'Dutch CV Standard',
                    'format_rules' => [
                        'photo_required' => false,
                        'age_required' => false,
                        'max_pages' => 2,
                        'preferred_order' => 'Personal Info → Work Experience → Education → Skills → Languages',
                        'notes' => 'No photo required (though accepted). 2 pages max. Emphasize results achieved. LinkedIn profile is expected. English CV is fine for international companies.',
                    ],
                    'sections' => ['Personal Info', 'Work Experience', 'Education', 'Skills', 'Languages', 'Interests'],
                ],
            ],
            'AU' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Skilled Temporary (482), Student Visa (500), Temporary Graduate (485)',
                        'validity' => '1-4 years',
                    ],
                    'permanent_reqs' => [
                        'years' => '4',
                        'language' => 'IELTS 6+ or equivalent',
                        'income' => 'Genuine employment or proven financial self-sufficiency',
                    ],
                    'citizenship_reqs' => [
                        'years' => '4',
                        'language' => 'Competent English required',
                        'tests' => 'Australian Citizenship Test (laws, values, history) + Good character declaration',
                    ],
                ],
                'cv' => [
                    'name' => 'Australian Resume Format',
                    'format_rules' => [
                        'photo_required' => false,
                        'age_required' => false,
                        'max_pages' => 3,
                        'preferred_order' => 'Personal Info → Career Objective → Work Experience → Education → Skills → Referees',
                        'notes' => 'No photo. No DOB. 2-3 pages acceptable. Include 2 referees at the end. Casual tone compared to European CVs. Highlight specific achievements and soft skills.',
                    ],
                    'sections' => ['Personal Info', 'Career Objective', 'Work Experience', 'Education', 'Skills', 'Referees'],
                ],
            ],
            'NZ' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Skilled Migrant, AEWV, Student Visa',
                        'validity' => '1-3 years',
                    ],
                    'permanent_reqs' => [
                        'years' => '2',
                        'language' => 'IELTS 4.5+ for citizenship but English competency required',
                        'income' => 'Good character + meeting visa conditions',
                    ],
                    'citizenship_reqs' => [
                        'years' => '5',
                        'language' => 'English capability',
                        'tests' => 'Knowledge of NZ test + Good character + Intent to reside',
                    ],
                ],
                'cv' => [
                    'name' => 'New Zealand CV Format',
                    'format_rules' => [
                        'photo_required' => false,
                        'age_required' => false,
                        'max_pages' => 3,
                        'preferred_order' => 'Personal Info → Career Summary → Employment History → Education → Skills → Referees',
                        'notes' => 'No photo, no DOB. Friendly and approachable tone. 2-3 pages. Include 2 referees. Highlight collaboration, innovation, and cultural fit.',
                    ],
                    'sections' => ['Personal Info', 'Career Summary', 'Employment History', 'Education', 'Skills', 'Referees'],
                ],
            ],
            'IE' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Critical Skills EP, General EP, Student Stamp, ICT permit',
                        'validity' => '1-2 years renewable',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'No formal test required (English country)',
                        'income' => 'Lawful continuous employment or strong financial footing',
                    ],
                    'citizenship_reqs' => [
                        'years' => '5',
                        'language' => 'English proficiency (as used in application)',
                        'tests' => 'Declaration of Fidelity to Ireland + Good character proof',
                    ],
                ],
                'cv' => [
                    'name' => 'Irish CV Standard',
                    'format_rules' => [
                        'photo_required' => false,
                        'age_required' => false,
                        'max_pages' => 2,
                        'preferred_order' => 'Personal Info → Profile Summary → Work Experience → Education → Skills',
                        'notes' => 'No photo or DOB. Crisp 1-2 page format. Strong personal profile statement is expected at top. Direct, achievement-focused bullet points.',
                    ],
                    'sections' => ['Personal Info', 'Profile Summary', 'Work Experience', 'Education', 'Skills'],
                ],
            ],
            'ES' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Tarjeta de Identidad de Extranjero (TIE) – Non-lucrative, Work, Student Visa',
                        'validity' => '1-2 years, renewable for up to 5 years',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'A2 Spanish (DELE) required for Long Term Residency',
                        'income' => 'Financial self-sufficiency or employment contract',
                    ],
                    'citizenship_reqs' => [
                        'years' => '10',
                        'language' => 'A2–B1 Spanish (DELE exam) + CCSE civic knowledge test',
                        'tests' => 'CCSE (Cultural and Social Knowledge of Spain) + DELE Language Exam',
                    ],
                ],
                'cv' => [
                    'name' => 'Spanish Curriculum Vitae (CV)',
                    'format_rules' => [
                        'photo_required' => true,
                        'age_required' => true,
                        'max_pages' => 2,
                        'preferred_order' => 'Photo + Personal Info → Career Objective → Work Experience → Education → Skills',
                        'notes' => 'A professional photo is standard in Spain. Include date of birth and national ID (DNI/NIE) if available. Title the document "Curriculum Vitae". Spanish companies expect a photo at the top-right. 1-2 pages standard.',
                    ],
                    'sections' => ['Personal Info (with photo)', 'Career Objective', 'Work Experience', 'Education', 'Skills & Languages', 'Other Merits'],
                ],
            ],
            'PT' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'D2, D7, D8 Student Visa → AIMA Residence Permit',
                        'validity' => '1-2 years initially',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'A2 Portuguese (CAPLE test)',
                        'income' => 'Regular income above minimum wage or passive income',
                    ],
                    'citizenship_reqs' => [
                        'years' => '5',
                        'language' => 'A2 Portuguese minimum (CAPLE/DIPLE)',
                        'tests' => 'No formal civic test currently; character + legal residence proof',
                    ],
                ],
                'cv' => [
                    'name' => 'Portuguese CV Format',
                    'format_rules' => [
                        'photo_required' => true,
                        'age_required' => true,
                        'max_pages' => 2,
                        'preferred_order' => 'Photo + Personal Info → Objective → Education → Work Experience → Skills',
                        'notes' => 'Photo is standard in Portugal. Include DOB. Education often listed before work experience. 1-2 pages. Portuguese companies expect a formal Europass-style format.',
                    ],
                    'sections' => ['Personal Info (with photo)', 'Professional Objective', 'Education', 'Work Experience', 'Skills & Languages'],
                ],
            ],
            'FR' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Titre de Séjour – Student, Work, Talent Passport',
                        'validity' => '1 year (first VLS-TS), 2-4 years (renewals)',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'B1 French (TCF/DELF)',
                        'income' => 'Stable employment or financial resources above poverty threshold',
                    ],
                    'citizenship_reqs' => [
                        'years' => '5',
                        'language' => 'B2 French required (TCF/DELF)',
                        'tests' => 'French republican values interview + language proof + tax compliance',
                    ],
                ],
                'cv' => [
                    'name' => 'French CV (Curriculum Vitae)',
                    'format_rules' => [
                        'photo_required' => true,
                        'age_required' => false,
                        'max_pages' => 1,
                        'preferred_order' => 'Photo + Personal Info → Work Experience → Education → Skills → Interests',
                        'notes' => 'Photo is strongly expected in France. Maximum 1 page (A4 format). No mention of salary expectations. Include hobbies/interests at the bottom. Elegant, concise formatting.',
                    ],
                    'sections' => ['Personal Info (with photo)', 'Work Experience', 'Education', 'Skills & Languages', 'Interests & Hobbies'],
                ],
            ],
            'IT' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Permesso di Soggiorno – Work, Study, Elective Residency',
                        'validity' => '1-2 years renewable',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'A2 Italian + test on Italian society/culture',
                        'income' => 'Annual income above minimum social benefits (€8,500+)',
                    ],
                    'citizenship_reqs' => [
                        'years' => '10',
                        'language' => 'B1 Italian required',
                        'tests' => 'Knowledge of Italian Constitution and culture + B1 Italian certificate',
                    ],
                ],
                'cv' => [
                    'name' => 'Italian Curriculum Vitae',
                    'format_rules' => [
                        'photo_required' => true,
                        'age_required' => true,
                        'max_pages' => 2,
                        'preferred_order' => 'Personal Info (with photo) → Career Summary → Work Experience → Education → Skills',
                        'notes' => 'Photo is widely expected in Italy. Include DOB and place of birth. Europass format is very common. 1-2 pages. Include your tax code (Codice Fiscale) if applying within Italy.',
                    ],
                    'sections' => ['Personal Info (with photo)', 'Career Summary', 'Work Experience', 'Education', 'Skills', 'Languages'],
                ],
            ],
            'SE' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Uppehållstillstånd (UT) – Work Permit, Student Permit',
                        'validity' => 'Up to 2 years renewable',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'No Swedish test required for permanent residency',
                        'income' => 'Financial self-sufficiency throughout residence period',
                    ],
                    'citizenship_reqs' => [
                        'years' => '5',
                        'language' => 'Swedish language ability (assessed informally)',
                        'tests' => 'Good reputation + stable income + clear criminal record',
                    ],
                ],
                'cv' => [
                    'name' => 'Swedish CV Standard',
                    'format_rules' => [
                        'photo_required' => false,
                        'age_required' => false,
                        'max_pages' => 2,
                        'preferred_order' => 'Contact Info → Career Summary → Key Skills → Work Experience → Education',
                        'notes' => 'No photo required. Concise and skills-focused. Swedish companies value evidence of teamwork, initiative, and inclusiveness. LinkedIn is widely used alongside CV. Clean, minimalist design.',
                    ],
                    'sections' => ['Contact Info', 'Career Summary', 'Key Skills', 'Work Experience', 'Education', 'Languages'],
                ],
            ],
            'FI' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Oleskelulupa – Specialist Permit, Student Permit, Job Seeker',
                        'validity' => '1-4 years based on permit',
                    ],
                    'permanent_reqs' => [
                        'years' => '4',
                        'language' => 'A2 Finnish or Swedish (often tested)',
                        'income' => 'Steady income and good conduct throughout stay',
                    ],
                    'citizenship_reqs' => [
                        'years' => '6',
                        'language' => 'B1 Finnish or Swedish required',
                        'tests' => 'Language test + clean criminal record + integration plan completion',
                    ],
                ],
                'cv' => [
                    'name' => 'Finnish CV Format',
                    'format_rules' => [
                        'photo_required' => false,
                        'age_required' => false,
                        'max_pages' => 2,
                        'preferred_order' => 'Contact Info → Summary → Work Experience → Education → Skills',
                        'notes' => 'No photo or DOB required. Finns value brevity and honesty. 1-2 pages. Avoid exaggeration. A clean, professional LinkedIn profile is expected alongside the CV.',
                    ],
                    'sections' => ['Contact Info', 'Professional Summary', 'Work Experience', 'Education', 'Technical Skills', 'Languages'],
                ],
            ],
            'NO' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Oppholdstillatelse – Skilled Worker, Student Permit',
                        'validity' => '1-3 years renewable',
                    ],
                    'permanent_reqs' => [
                        'years' => '3',
                        'language' => 'A2 Norwegian (Norskprøver) + Social Studies course',
                        'income' => 'Continuous employment or self-sufficiency',
                    ],
                    'citizenship_reqs' => [
                        'years' => '7',
                        'language' => 'B1 Norwegian required',
                        'tests' => 'Norsk statsborgerprøven (civic knowledge) + Language proof',
                    ],
                ],
                'cv' => [
                    'name' => 'Norwegian CV Standard',
                    'format_rules' => [
                        'photo_required' => false,
                        'age_required' => false,
                        'max_pages' => 2,
                        'preferred_order' => 'Contact Details → Professional Profile → Work Experience → Education → Skills',
                        'notes' => 'No photo or DOB expected. Egalitarian culture — avoid over-marketing yourself. 1-2 pages. Achievements should be clearly quantified. A cover letter (søknadsbrev) is usually required separately.',
                    ],
                    'sections' => ['Contact Details', 'Professional Profile', 'Work Experience', 'Education', 'Skills', 'Languages'],
                ],
            ],
            'AT' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Red-White-Red Card, Student Residence Permit, Job Seeker Visa',
                        'validity' => '1-2 years renewable',
                    ],
                    'permanent_reqs' => [
                        'years' => '5',
                        'language' => 'A2 German (Deutschkenntnisse) required',
                        'income' => 'Adequate income and valid insurance throughout',
                    ],
                    'citizenship_reqs' => [
                        'years' => '10',
                        'language' => 'B1 German and civic knowledge test',
                        'tests' => 'Österreich-Wissen (civics test) + B1 German certificate',
                    ],
                ],
                'cv' => [
                    'name' => 'Austrian Lebenslauf Format',
                    'format_rules' => [
                        'photo_required' => true,
                        'age_required' => true,
                        'max_pages' => 2,
                        'preferred_order' => 'Photo + Personal Info → Work Experience → Education → Skills → Interests',
                        'notes' => 'Similar to German format. Professional photo in top-right. Include DOB and nationality. Document titled "Lebenslauf". Concise bullet points per role. 1-2 pages.',
                    ],
                    'sections' => ['Personal Info (with photo)', 'Work Experience', 'Education', 'Training & Certifications', 'Skills & Languages', 'Interests'],
                ],
            ],
            'NG' => [
                'residency' => [
                    'temporary_reqs' => [
                        'permits' => 'Temporary Residence Permit (TRP) or CERPAC for expatriates',
                        'validity' => '1-2 years renewable',
                    ],
                    'permanent_reqs' => [
                        'years' => '15',
                        'language' => 'English (national language, no test required)',
                        'income' => 'Employment or business ownership + tax compliance',
                    ],
                    'citizenship_reqs' => [
                        'years' => '15',
                        'language' => 'English proficiency',
                        'tests' => 'Federal Executive Council approval required for naturalisation — rarely granted',
                    ],
                ],
                'cv' => [
                    'name' => 'Nigerian CV Format',
                    'format_rules' => [
                        'photo_required' => true,
                        'age_required' => true,
                        'max_pages' => 3,
                        'preferred_order' => 'Photo + Personal Info → Career Objective → Work Experience → Education → Skills → Interests',
                        'notes' => 'Photo is almost always expected. Include DOB and state of origin. Include your NYSC details if applicable (for recent graduates). A 2-3 page academic-style CV is common. Referees (2) are expected at the bottom.',
                    ],
                    'sections' => ['Personal Info (with photo)', 'Career Objective', 'Work Experience', 'Education', 'Skills', 'NYSC Details (if applicable)', 'Referees'],
                ],
            ],
        ];

        foreach ($rules as $code => $data) {
            $country = Country::where('code', $code)->first();
            if (!$country)
                continue;

            // Seed Residency Rule
            ResidencyRule::updateOrCreate(
            ['country_id' => $country->id],
            [
                'temporary_reqs' => $data['residency']['temporary_reqs'],
                'permanent_reqs' => $data['residency']['permanent_reqs'],
                'citizenship_reqs' => $data['residency']['citizenship_reqs'],
            ]
            );

            // Seed CV Template
            CvTemplate::updateOrCreate(
            ['country_id' => $country->id, 'name' => $data['cv']['name']],
            [
                'country_id' => $country->id,
                'format_rules' => $data['cv']['format_rules'],
                'structure_json' => $data['cv']['sections'],
            ]
            );

            $this->command->info("Seeded residency rules and CV template for: {$country->name}");
        }
    }
}