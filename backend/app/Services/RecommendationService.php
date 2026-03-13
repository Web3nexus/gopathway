<?php

namespace App\Services;

use App\Models\User;
use App\Models\VisaType;
use Illuminate\Support\Collection;

class RecommendationService
{
    protected array $educationRanks = [
        'high_school' => 1,
        'other' => 1,
        'bachelors' => 2,
        'masters' => 3,
        'phd' => 4,
    ];

    protected array $fundsMap = [
        'under_5k' => 5000,
        '5k_10k' => 10000,
        '10k_20k' => 20000,
        '20k_50k' => 50000,
        'over_50k' => 100000,
    ];

    protected array $ieltsMap = [
        'not_taken' => 0.0,
        'scheduled' => 0.0,
        'band_5' => 5.5,
        'band_6' => 6.5,
        'band_7' => 7.5,
        'band_8_plus' => 8.5,
    ];

    /**
     * Get recommendations for a specific user.
     */
    public function getRecommendations(User $user): Collection
    {
        $profile = $user->profile;
        if (!$profile) {
            return collect();
        }

        $visaTypes = VisaType::with('country')->where('is_active', true)->get();

        return $visaTypes->map(function ($visa) use ($profile) {
            $score = $this->calculateMatchScore($profile, $visa);
            $improvements = $this->getImprovementChecklist($profile, $visa);

            return [
                'visa_type' => $visa,
                'match_percentage' => $score,
                'improvements' => $improvements,
                'is_eligible' => $score >= 100,
            ];
        })->sortByDesc('match_percentage')->values();
    }

    /**
     * Calculate 0-100 match score.
     */
    public function calculateMatchScore($profile, VisaType $visa): int
    {
        $totalWeight = 0;
        $matchedWeight = 0;

        // Weights: Funds 30%, Education 30%, Experience 20%, IELTS 20%

        // 1. Education (30%)
        if ($visa->min_education_level) {
            $totalWeight += 30;
            $userRank = $this->educationRanks[$profile->education_level] ?? 0;
            $requiredRank = $this->educationRanks[$visa->min_education_level] ?? 1;

            if ($userRank >= $requiredRank) {
                $matchedWeight += 30;
            } else {
                $matchedWeight += 30 * ($userRank / $requiredRank);
            }
        }

        // 2. Funds (30%)
        if ($visa->min_funds_required) {
            $totalWeight += 30;
            $userFunds = $this->fundsMap[$profile->funds_range] ?? 0;
            if ($userFunds >= $visa->min_funds_required) {
                $matchedWeight += 30;
            } else {
                $matchedWeight += 30 * ($userFunds / $visa->min_funds_required);
            }
        }

        // 3. Experience (20%)
        if ($visa->min_work_experience_years > 0) {
            $totalWeight += 20;
            if ($profile->work_experience_years >= $visa->min_work_experience_years) {
                $matchedWeight += 20;
            } else {
                $matchedWeight += 20 * ($profile->work_experience_years / $visa->min_work_experience_years);
            }
        }

        // 4. IELTS (20%)
        if ($visa->min_ielts_score > 0) {
            $totalWeight += 20;
            $userScore = $this->ieltsMap[$profile->ielts_status] ?? 0;
            if ($userScore >= $visa->min_ielts_score) {
                $matchedWeight += 20;
            } else {
                // If they haven't taken it, partial credit for "scheduled" could be added but currently 0
                $matchedWeight += 20 * ($userScore / $visa->min_ielts_score);
            }
        }

        if ($totalWeight === 0)
            return 0; // Better to show 0% if we have no data, rather than a fake 100%

        return (int) round(($matchedWeight / $totalWeight) * 100);
    }

    /**
     * Generate "To Improve" checklist.
     */
    protected function getImprovementChecklist($profile, VisaType $visa): array
    {
        $checklist = [];

        // Education
        $userRank = $this->educationRanks[$profile->education_level] ?? 0;
        $requiredRank = $this->educationRanks[$visa->min_education_level] ?? 0;
        if ($requiredRank > $userRank) {
            $checklist[] = "Obtain a " . str_replace('_', ' ', $visa->min_education_level) . " degree or higher.";
        }

        // Funds
        $userFunds = $this->fundsMap[$profile->funds_range] ?? 0;
        if ($visa->min_funds_required > $userFunds) {
            $gap = $visa->min_funds_required - $userFunds;
            $checklist[] = "Save an additional £" . number_format($gap) . " to meet the proof of funds requirement.";
        }

        // Experience
        if ($visa->min_work_experience_years > $profile->work_experience_years) {
            $gap = $visa->min_work_experience_years - $profile->work_experience_years;
            $checklist[] = "Gain " . $gap . " more years of relevant work experience.";
        }

        // IELTS
        $userScore = $this->ieltsMap[$profile->ielts_status] ?? 0;
        if ($visa->min_ielts_score > $userScore) {
            $checklist[] = "Achieve at least a Band " . $visa->min_ielts_score . " in your English proficiency test.";
        }

        return $checklist;
    }
}
