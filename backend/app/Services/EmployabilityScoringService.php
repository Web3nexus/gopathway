<?php

namespace App\Services;

use App\Models\Profile;

class EmployabilityScoringService
{
    /**
     * Calculate employability score based on profile attributes and target country.
     */
    public function calculateScore(Profile $profile): array
    {
        $score = 0;
        $maxScore = 100;
        $breakdown = [];

        // 1. Occupation Base Demand & Country Multiplier (Max 50 points)
        $occupationScore = 15; // default fallback
        $occupation = $profile->occupation;
        $country = $profile->preferredCountry;

        if ($occupation) {
            $baseDemand = $occupation->base_demand_score; // 1-100
            
            // Check if country has a specific multiplier for this occupation
            $multiplier = 1.0;
            if ($country) {
                $demandRecord = $occupation->countries()->where('country_id', $country->id)->first();
                if ($demandRecord) {
                    $multiplier = (float) $demandRecord->pivot->demand_multiplier;
                }
            }

            // Calculate scaled score up to 50
            // Base demand of 100 * multiplier 1.0 = 50 points
            $occupationScore = min(50, ($baseDemand / 2) * $multiplier);
        }
        $score += $occupationScore;
        $breakdown['occupation'] = round($occupationScore);

        // 2. Work Experience (Max 25 points)
        $experience = (int) $profile->work_experience_years;
        $expScore = 0;
        if ($experience >= 10) {
            $expScore = 25;
        } elseif ($experience >= 5) {
            $expScore = 20;
        } elseif ($experience >= 3) {
            $expScore = 15;
        } elseif ($experience >= 1) {
            $expScore = 5;
        }
        $score += $expScore;
        $breakdown['experience'] = $expScore;

        // 3. Education (Max 15 points)
        $eduScore = 0;
        $educationMap = [
            'phd' => 15,
            'masters' => 12,
            'bachelors' => 10,
            'high_school' => 0,
        ];
        if ($profile->education_level && isset($educationMap[$profile->education_level])) {
            $eduScore = $educationMap[$profile->education_level];
        }
        $score += $eduScore;
        $breakdown['education'] = $eduScore;

        // 4. Language/IELTS Status (Max 10 points)
        $langScore = 0;
        if (in_array($profile->ielts_status, ['taken', 'not_required'])) {
            $langScore = 10;
        } elseif ($profile->ielts_status === 'preparing') {
            $langScore = 5;
        }
        $score += $langScore;
        $breakdown['language'] = $langScore;

        // Final score rounding
        $finalScore = min(100, round($score));

        return [
            'score' => $finalScore,
            'rating' => $this->getRating($finalScore),
            'breakdown' => $breakdown,
        ];
    }

    private function getRating(int $score): string
    {
        if ($score >= 85) return 'Outstanding';
        if ($score >= 70) return 'High Demand';
        if ($score >= 50) return 'Moderate Prospects';
        return 'Low Demand';
    }
}
