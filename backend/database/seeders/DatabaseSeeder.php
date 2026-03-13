<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\DocumentType;
use App\Models\VisaType;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(CleanupSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(PathwayExpansionSeeder::class);
        $this->call(ComprehensiveRealisticCostSeeder::class);


        // ──────────────── DEMO USERS ────────────────

        // 1. Admin user (accesses /securegate after login)
        $admin = User::updateOrCreate(
            ['email' => 'admin@gopathway.net'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        // 2. Demo regular user — profile fully completed, pathway selected
        $demoUser = User::updateOrCreate(
            ['email' => 'demo@gopathway.net'],
            ['name' => 'Demo User', 'password' => bcrypt('password')]
        );
        $demoUser->assignRole('user');

        // 3. Blank user — no profile, no pathway (tests the empty state)
        $blankUser = User::updateOrCreate(
            ['email' => 'newuser@gopathway.net'],
            ['name' => 'New User', 'password' => bcrypt('password')]
        );
        $blankUser->assignRole('user');


        // ──────────────── COUNTRIES & VISA TYPES ────────────────
        // Real data is now handled by PathwayExpansionSeeder and ComprehensiveRealisticCostSeeder
        // Seeded in the order: MasterData -> Timeline -> Settlement -> PathwayExpansion -> RealisticCosts


        // ──────────────── DOCUMENT TYPES (Global) ────────────────
        DocumentType::create(['name' => 'International Passport', 'description' => 'Valid international travel document', 'visa_type_id' => null]);
        DocumentType::create(['name' => 'IELTS / TOEFL Certificate', 'description' => 'English language proficiency proof', 'visa_type_id' => null]);
        DocumentType::create(['name' => 'Bank Statement', 'description' => 'Proof of sufficient funds (last 3-6 months)', 'visa_type_id' => null]);
        DocumentType::create(['name' => 'Criminal Record / Police Clearance', 'description' => 'Background check certificate', 'visa_type_id' => null]);
        DocumentType::create(['name' => 'Educational Certificates', 'description' => 'Degree certificates and transcripts', 'visa_type_id' => null]);

        // ──────────────── DEMO USER — PROFILE + PATHWAY ────────────────
        $uk = Country::where('code', 'GB')->first();

        // Get the first UK Skilled Worker visa (dynamic from expansion seeder)
        $skilledWorkerVisa = \App\Models\VisaType::where('country_id', $uk?->id)
            ->where('name', 'LIKE', '%Skilled Worker%')
            ->first();

        // Create demo user's profile (fully completed)
        $demoUser->profile()->create([
            'age' => 29,
            'education_level' => 'bachelors',
            'work_experience_years' => 4,
            'funds_range' => '10k_20k',
            'ielts_status' => 'band_7',
            'preferred_country_id' => $uk?->id,
        ]);

        // Create demo user's active pathway
        $pathway = $demoUser->pathway()->create([
            'country_id' => $uk?->id,
            'visa_type_id' => $skilledWorkerVisa?->id,
            'status' => 'in_progress',
            'readiness_score' => 65,
        ]);


        // Timeline steps for the demo user
        $steps = [
            ['title' => 'Complete Profile', 'description' => 'Fill out your relocation profile to get a readiness score.', 'status' => 'completed', 'order' => 1],
            ['title' => 'Gather Required Documents', 'description' => 'Collect passport, bank statements, IELTS certificate, and references.', 'status' => 'completed', 'order' => 2],
            ['title' => 'Book IELTS / English Test', 'description' => 'Ensure you have a valid language test score (minimum Band 6.5).', 'status' => 'pending', 'order' => 3],
            ['title' => 'Submit Visa Application', 'description' => 'Apply online via the UKVI portal after receiving your Certificate of Sponsorship.', 'status' => 'pending', 'order' => 4],
        ];

        foreach ($steps as $step) {
            $demoUser->timelineSteps()->create(array_merge($step, [
                'completed_at' => $step['status'] === 'completed' ? now()->subDays(rand(2, 14)) : null,
            ]));
        }

        // Sample notifications for demo user
        $demoUser->notifications()->create([
            'title' => 'Welcome to GoPathway! 🎉',
            'message' => 'Your account has been set up. Start by completing your profile to get your readiness score.',
            'is_read' => true,
        ]);
        $demoUser->notifications()->create([
            'title' => 'Pathway Unlocked: UK Skilled Worker Visa',
            'message' => 'Your roadmap is ready. 4 steps to complete. Next: Book your IELTS test.',
            'is_read' => false,
        ]);
        $demoUser->notifications()->create([
            'title' => 'Readiness Score: 65%',
            'message' => 'You are 65% ready. Upload your documents to increase your score.',
            'is_read' => false,
        ]);

        // ──────────────── SUBSCRIPTION PLANS ────────────────
        SubscriptionPlan::updateOrCreate(
            ['slug' => 'premium-monthly'],
            [
                'name' => 'Premium Monthly',
                'price' => 29.00,
                'currency' => 'USD',
                'interval' => 'month',
                'features' => [
                    'Full Readiness Score Breakdown',
                    'Secure Document Vault',
                    'Interactive Cost Planner',
                    'Priority Expert Connections',
                ],
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['slug' => 'global-explorer-annual'],
            [
                'name' => 'Global Explorer (Annual)',
                'price' => 290.00,
                'currency' => 'USD',
                'interval' => 'year',
                'features' => [
                    'All Premium Features',
                    '2 Months Free',
                    'Dedicated Relocation Concierge',
                    'Unlimited Pathway Benchmarking',
                ],
            ]
        );
    }
}