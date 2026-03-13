<?php

namespace Database\Seeders;

use App\Models\TimelineStepTemplate;
use App\Models\VisaType;
use Illuminate\Database\Seeder;

class TimelineTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $visaTypes = VisaType::all();

        foreach ($visaTypes as $visa) {
            $pathwayType = $visa->pathway_type ?? '';
            $steps = $this->getStepsForPathway($pathwayType, $visa->name);

            foreach ($steps as $index => $step) {
                TimelineStepTemplate::updateOrCreate(
                    [
                        'visa_type_id' => $visa->id,
                        'title' => $step['title'],
                    ],
                    [
                        'description' => $step['description'],
                        'order' => $index + 1,
                    ]
                );
            }
        }
    }

    private function getStepsForPathway(string $type, string $name): array
    {
        // Common steps for all pathways
        $commonStart = [
            ['title' => 'Profile Completion', 'description' => 'Complete your professional and financial profile for accurate assessment.'],
            ['title' => 'Document Gathering', 'description' => 'Collect your passport, academic records, and proof of funds.'],
        ];

        $commonEnd = [
            ['title' => 'Submit Application', 'description' => 'Final review and submission of your application to the official portal.'],
            ['title' => 'Await Decision', 'description' => 'Monitor your application status and prepare for your move.'],
        ];

        // Specific steps based on type
        switch ($type) {
            case 'Study':
                return array_merge($commonStart, [
                    ['title' => 'Secure Admission', 'description' => 'Apply to and receive an unconditional offer from a DLI/University.'],
                    ['title' => 'Financial Proof Setup', 'description' => 'Set up a blocked account (if required) or gather bank statements.'],
                    ['title' => 'Health Insurance', 'description' => 'Purchase mandatory student health insurance for your stay.'],
                ], $commonEnd);

            case 'Skilled Work':
                return array_merge($commonStart, [
                    ['title' => 'Job Sponsorship / Offer', 'description' => 'Secure a job offer from a licensed sponsor or employer.'],
                    ['title' => 'Skills Assessment', 'description' => 'Get your qualifications verified by the relevant authority.'],
                    ['title' => 'Language Proficiency', 'description' => 'Take your IELTS/PTE/TOEFL test and achieve the required band.'],
                ], $commonEnd);

            case 'Digital Nomad':
            case 'Digital Nomad Visa':
                return array_merge($commonStart, [
                    ['title' => 'Remote Work Verification', 'description' => 'Gather contracts and income proof showing you work for non-Spanish companies.'],
                    ['title' => 'Income Threshold Verification', 'description' => 'Ensure your monthly income exceeds the minimum required (~€2,334).'],
                    ['title' => 'Criminal Record Check', 'description' => 'Obtain an apostilled criminal record certificate from your home country.'],
                    ['title' => 'Private Health Insurance', 'description' => 'Get a comprehensive private health insurance policy valid in Spain.'],
                ], $commonEnd);

            case 'Startup / Entrepreneur':
                return array_merge($commonStart, [
                    ['title' => 'Business Plan Development', 'description' => 'Create a detailed business plan for your innovative venture.'],
                    ['title' => 'Seek Endorsement / Facilitator', 'description' => 'Get your business idea approved by an authorized body.'],
                    ['title' => 'Capital Verification', 'description' => 'Show evidence of the required investment capital.'],
                ], $commonEnd);

            default:
                // Fallback for types that might not match exactly but have "Nomad" or "Nomadic" in the name
                if (stripos($name, 'Nomad') !== false) {
                    return $this->getStepsForPathway('Digital Nomad', $name);
                }

                return array_merge($commonStart, [
                    ['title' => 'Requirement Review', 'description' => 'Deep dive into the specific requirements for this visa class.'],
                    ['title' => 'Language & Skills Prep', 'description' => 'Prepare for any necessary exams or assessments.'],
                ], $commonEnd);
        }
    }
}
