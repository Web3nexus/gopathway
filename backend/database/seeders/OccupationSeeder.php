<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $occupations = [
            // Tech
            ['name' => 'Software Engineer', 'category' => 'Technology', 'base_demand_score' => 95],
            ['name' => 'Data Scientist', 'category' => 'Technology', 'base_demand_score' => 90],
            ['name' => 'Cybersecurity Analyst', 'category' => 'Technology', 'base_demand_score' => 92],
            ['name' => 'IT Support Specialist', 'category' => 'Technology', 'base_demand_score' => 70],
            
            // Healthcare
            ['name' => 'Registered Nurse', 'category' => 'Healthcare', 'base_demand_score' => 100],
            ['name' => 'Physician / Doctor', 'category' => 'Healthcare', 'base_demand_score' => 98],
            ['name' => 'Pharmacist', 'category' => 'Healthcare', 'base_demand_score' => 85],
            ['name' => 'Healthcare Administrator', 'category' => 'Healthcare', 'base_demand_score' => 75],

            // Engineering & Science
            ['name' => 'Civil Engineer', 'category' => 'Engineering', 'base_demand_score' => 88],
            ['name' => 'Mechanical Engineer', 'category' => 'Engineering', 'base_demand_score' => 85],
            ['name' => 'Electrical Engineer', 'category' => 'Engineering', 'base_demand_score' => 86],

            // Business & Finance
            ['name' => 'Accountant', 'category' => 'Business', 'base_demand_score' => 80],
            ['name' => 'Financial Analyst', 'category' => 'Business', 'base_demand_score' => 78],
            ['name' => 'Marketing Manager', 'category' => 'Business', 'base_demand_score' => 65],
            ['name' => 'Project Manager', 'category' => 'Business', 'base_demand_score' => 82],
            ['name' => 'Human Resources Manager', 'category' => 'Business', 'base_demand_score' => 60],

            // Trades & Services
            ['name' => 'Electrician', 'category' => 'Trades', 'base_demand_score' => 85],
            ['name' => 'Plumber', 'category' => 'Trades', 'base_demand_score' => 82],
            ['name' => 'Carpenter', 'category' => 'Trades', 'base_demand_score' => 75],
            ['name' => 'Chef / Cook', 'category' => 'Services', 'base_demand_score' => 70],
            
            // Education
            ['name' => 'Teacher (Secondary)', 'category' => 'Education', 'base_demand_score' => 85],
            ['name' => 'University Professor', 'category' => 'Education', 'base_demand_score' => 75],
            
            // Other
            ['name' => 'Graphic Designer', 'category' => 'Creative', 'base_demand_score' => 50],
            ['name' => 'Sales Representative', 'category' => 'Sales', 'base_demand_score' => 55],
            ['name' => 'Administrative Assistant', 'category' => 'Administration', 'base_demand_score' => 40],
            ['name' => 'Other / Not Listed', 'category' => 'Other', 'base_demand_score' => 30],
        ];

        foreach ($occupations as $occ) {
            \App\Models\Occupation::updateOrCreate(
                ['name' => $occ['name']],
                ['category' => $occ['category'], 'base_demand_score' => $occ['base_demand_score']]
            );
        }
    }
}
