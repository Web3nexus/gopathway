<?php

namespace App\Services;

use App\Models\Pathway;
use App\Models\RiskReport;
use App\Models\User;

class RiskAnalysisService
{
    // Dimension weights (must add to 100)
    private const WEIGHTS = [
        'funds'          => 25,
        'language'       => 20,
        'age'            => 10,
        'experience'     => 15,
        'documents'      => 15,
        'travel_history' => 15,
    ];

    // Funds range midpoints (in USD)
    private const FUNDS_RANGES = [
        '0-5000'     => 5000,
        '5000-10000' => 7500,
        '10000-20000' => 15000,
        '20000-50000' => 35000,
        '50000+'     => 75000,
    ];

    // Travel history risk map (higher = riskier)
    private const TRAVEL_RISK = [
        'none'             => 90,
        'domestic_only'    => 70,
        '1_2_countries'    => 40,
        '3_5_countries'    => 15,
        '5_plus_countries' => 5,
    ];

    /**
     * Calculate a full risk analysis for a user's pathway.
     */
    public function analyze(User $user, Pathway $pathway): RiskReport
    {
        $user->loadMissing(['profile', 'documents']);
        $pathway->loadMissing('visaType');

        $profile  = $user->profile;
        $visaType = $pathway->visaType;

        // ----- Score each dimension (0–100, higher = riskier) -----
        $fundsResult          = $this->scoreFundsRisk($profile, $visaType);
        $languageResult       = $this->scoreLanguageRisk($profile, $visaType);
        $ageResult            = $this->scoreAgeRisk($profile, $visaType);
        $experienceResult     = $this->scoreExperienceRisk($profile, $visaType);
        $documentsResult      = $this->scoreDocumentsRisk($user->documents);
        $travelHistoryResult  = $this->scoreTravelHistoryRisk($profile);

        // ----- Weighted overall risk -----
        $overallRisk = (int) round(
            ($fundsResult['score']         * self::WEIGHTS['funds']) / 100 +
            ($languageResult['score']      * self::WEIGHTS['language']) / 100 +
            ($ageResult['score']           * self::WEIGHTS['age']) / 100 +
            ($experienceResult['score']    * self::WEIGHTS['experience']) / 100 +
            ($documentsResult['score']     * self::WEIGHTS['documents']) / 100 +
            ($travelHistoryResult['score'] * self::WEIGHTS['travel_history']) / 100
        );

        $riskLevel = match (true) {
            $overallRisk <= 30 => 'low',
            $overallRisk <= 60 => 'medium',
            default            => 'high',
        };

        // ----- Build weak areas (top 3 riskiest dimensions) -----
        $dimensions = [
            'funds'          => $fundsResult,
            'language'       => $languageResult,
            'age'            => $ageResult,
            'experience'     => $experienceResult,
            'documents'      => $documentsResult,
            'travel_history' => $travelHistoryResult,
        ];

        $weakAreas = $this->buildWeakAreas($dimensions);

        // ----- Full report -----
        $fullReport = [
            'dimensions' => $dimensions,
            'weights'    => self::WEIGHTS,
        ];

        return RiskReport::updateOrCreate(
            [
                'user_id'    => $user->id,
                'pathway_id' => $pathway->id,
            ],
            [
                'risk_level'          => $riskLevel,
                'risk_score'          => $overallRisk,
                'funds_risk'          => $fundsResult['score'],
                'language_risk'       => $languageResult['score'],
                'age_risk'            => $ageResult['score'],
                'experience_risk'     => $experienceResult['score'],
                'documents_risk'      => $documentsResult['score'],
                'travel_history_risk' => $travelHistoryResult['score'],
                'weak_areas'          => $weakAreas,
                'full_report'         => $fullReport,
                'calculated_at'       => now(),
            ]
        );
    }

    // ─────────────────────────────────────────
    //  Dimension Scorers (0 = no risk, 100 = max risk)
    // ─────────────────────────────────────────

    private function scoreFundsRisk(?object $profile, ?object $visaType): array
    {
        if (!$profile || empty($profile->funds_range)) {
            return [
                'score' => 85,
                'label' => 'No funds data provided',
                'tip'   => 'Add your current funds range to your profile so we can assess financial readiness.',
            ];
        }

        $userFunds = self::FUNDS_RANGES[$profile->funds_range] ?? 0;
        $required  = $visaType?->min_funds_required ?? 10000;

        if ($required == 0) {
            return ['score' => 10, 'label' => 'No minimum funds required', 'tip' => ''];
        }

        $ratio = $userFunds / $required;

        // Risk scoring based on funds-to-requirement ratio
        $score = match (true) {
            $ratio >= 1.5 => 5,    // Very comfortable buffer
            $ratio >= 1.2 => 15,   // Good buffer
            $ratio >= 1.0 => 30,   // Just meeting requirement
            $ratio >= 0.8 => 55,   // Slightly under
            $ratio >= 0.5 => 75,   // Significantly under
            default       => 90,   // Very under-funded
        };

        $tips = [];
        if ($score >= 55) {
            $tips[] = 'Your available funds are below the visa requirement of ' . number_format($required, 0) . ' USD.';
        }
        if ($score >= 30 && $score < 55) {
            $tips[] = 'Your funds just meet the minimum — embassies prefer a 20–50% buffer above the requirement.';
        }

        return [
            'score' => $score,
            'label' => number_format($userFunds, 0) . ' vs ' . number_format($required, 0) . ' required',
            'tip'   => implode(' ', $tips) ?: 'Your funds look healthy for this visa.',
        ];
    }

    private function scoreLanguageRisk(?object $profile, ?object $visaType): array
    {
        if (!$profile || empty($profile->ielts_status)) {
            return [
                'score' => 80,
                'label' => 'No language test registered',
                'tip'   => 'Register for IELTS or an equivalent test — most visas require language proof.',
            ];
        }

        // IELTS status → approximate band mapping
        $statusBand = [
            'not_taken'  => 0,
            'registered' => 0,
            'taken'      => 5.5,
            'band_6'     => 6.0,
            'band_6_5'   => 6.5,
            'band_7'     => 7.0,
            'band_7_5'   => 7.5,
            'band_8'     => 8.0,
        ];

        $userBand   = $statusBand[$profile->ielts_status] ?? 0;
        $minBand    = $visaType?->min_ielts_score ?? 6.0;

        if ($userBand == 0) {
            $score = $profile->ielts_status === 'registered' ? 60 : 85;
            $tip   = $profile->ielts_status === 'registered'
                ? 'You are registered — prepare well to hit at least Band ' . $minBand . '.'
                : 'You have not taken a language test. This is a major risk factor.';
            return ['score' => $score, 'label' => 'No test score yet', 'tip' => $tip];
        }

        $diff = $userBand - $minBand;

        $score = match (true) {
            $diff >= 1.0 => 5,
            $diff >= 0.5 => 15,
            $diff >= 0   => 25,
            $diff >= -0.5 => 50,
            $diff >= -1.0 => 70,
            default       => 90,
        };

        $tip = $score >= 50
            ? 'Your IELTS score is below the recommended Band ' . $minBand . '. Consider retaking the test.'
            : ($score >= 25 ? 'You meet the minimum, but a higher score strengthens your application.' : 'Your language score looks strong.');

        return [
            'score' => $score,
            'label' => 'Band ' . $userBand . ' vs Band ' . $minBand . ' required',
            'tip'   => $tip,
        ];
    }

    private function scoreAgeRisk(?object $profile, ?object $visaType): array
    {
        if (!$profile || empty($profile->age)) {
            return [
                'score' => 40,
                'label' => 'Age not provided',
                'tip'   => 'Add your age to your profile for a more accurate risk assessment.',
            ];
        }

        $age      = (int) $profile->age;
        $visaName = strtolower($visaType?->name ?? '');

        // Study visas: ideal 17–30, acceptable up to 40
        if (str_contains($visaName, 'study') || str_contains($visaName, 'student')) {
            $score = match (true) {
                $age >= 17 && $age <= 25 => 5,
                $age >= 26 && $age <= 30 => 15,
                $age >= 31 && $age <= 35 => 35,
                $age >= 36 && $age <= 40 => 55,
                $age > 40               => 75,
                $age < 17               => 90,
                default                 => 40,
            };
        }
        // Work / skilled worker visas: ideal 25–45
        elseif (str_contains($visaName, 'work') || str_contains($visaName, 'skilled')) {
            $score = match (true) {
                $age >= 25 && $age <= 35 => 5,
                $age >= 18 && $age <= 24 => 15,
                $age >= 36 && $age <= 45 => 15,
                $age >= 46 && $age <= 50 => 40,
                $age > 50               => 65,
                $age < 18               => 85,
                default                 => 30,
            };
        }
        // Default / PR / other
        else {
            $score = match (true) {
                $age >= 21 && $age <= 40 => 10,
                $age >= 18 && $age <= 20 => 20,
                $age >= 41 && $age <= 50 => 30,
                $age > 50               => 50,
                $age < 18               => 80,
                default                 => 30,
            };
        }

        $tip = $score >= 50
            ? 'Your age may be a concern for this visa type. Some categories favor younger applicants.'
            : ($score >= 30 ? 'Your age is acceptable but not in the ideal range.' : 'Your age is within the ideal range for this visa.');

        return ['score' => $score, 'label' => $age . ' years old', 'tip' => $tip];
    }

    private function scoreExperienceRisk(?object $profile, ?object $visaType): array
    {
        if (!$profile || $profile->work_experience_years === null) {
            return [
                'score' => 60,
                'label' => 'No work experience data',
                'tip'   => 'Add your work experience to your profile for a better assessment.',
            ];
        }

        $years    = (int) $profile->work_experience_years;
        $required = $visaType?->min_work_experience_years ?? 0;

        if ($required == 0) {
            // No experience required — minimal risk
            $score = $years > 0 ? 5 : 20;
            return [
                'score' => $score,
                'label' => $years . ' years experience',
                'tip'   => $years > 0 ? 'Experience is not required but strengthens your profile.' : 'This visa does not require work experience.',
            ];
        }

        $diff = $years - $required;

        $score = match (true) {
            $diff >= 3  => 5,
            $diff >= 1  => 10,
            $diff >= 0  => 25,
            $diff >= -1 => 50,
            $diff >= -2 => 70,
            default     => 85,
        };

        $tip = $score >= 50
            ? 'You need at least ' . $required . ' years of experience. You currently have ' . $years . '.'
            : ($score >= 25 ? 'You meet the minimum experience requirement.' : 'Your experience exceeds the requirement — excellent.');

        return [
            'score' => $score,
            'label' => $years . ' years vs ' . $required . ' required',
            'tip'   => $tip,
        ];
    }

    private function scoreDocumentsRisk($documents): array
    {
        $count = $documents->count();

        if ($count === 0) {
            return [
                'score' => 80,
                'label' => 'No documents uploaded',
                'tip'   => 'Upload key documents (passport, bank statement, academic certificates) to reduce rejection risk.',
            ];
        }

        $approved = $documents->where('status', 'approved')->count();
        $uploaded = $documents->whereIn('status', ['uploaded', 'approved'])->count();

        // Risk decreases as more documents are uploaded and approved
        $uploadScore = max(80 - ($uploaded * 15), 10);
        $approvalBonus = $approved > 0 ? min($approved * 10, 30) : 0;
        $score = max($uploadScore - $approvalBonus, 5);

        $tip = $score >= 50
            ? 'Upload and get more documents verified to lower your rejection risk.'
            : ($score >= 25 ? 'Good progress — make sure all key documents are reviewed by an expert.' : 'Your document readiness looks strong.');

        return [
            'score' => $score,
            'label' => $uploaded . ' uploaded, ' . $approved . ' verified',
            'tip'   => $tip,
        ];
    }

    private function scoreTravelHistoryRisk(?object $profile): array
    {
        $history = $profile->travel_history ?? 'none';
        $score   = self::TRAVEL_RISK[$history] ?? 90;

        $labels = [
            'none'             => 'No international travel',
            'domestic_only'    => 'Domestic travel only',
            '1_2_countries'    => '1–2 countries visited',
            '3_5_countries'    => '3–5 countries visited',
            '5_plus_countries' => '5+ countries visited',
        ];

        $tip = match (true) {
            $score >= 70 => 'No travel history is a common rejection flag. Even short trips to neighboring countries help.',
            $score >= 40 => 'Limited travel history may raise concerns. Documenting any trips strengthens your application.',
            default      => 'Your travel history demonstrates mobility and prior visa compliance.',
        };

        return [
            'score' => $score,
            'label' => $labels[$history] ?? 'Unknown',
            'tip'   => $tip,
        ];
    }

    // ─────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────

    /**
     * Return the top 3 riskiest dimensions as weak areas.
     */
    private function buildWeakAreas(array $dimensions): array
    {
        // Sort by score descending to get the worst ones
        uasort($dimensions, fn($a, $b) => $b['score'] <=> $a['score']);

        $weakAreas = [];
        $labels = [
            'funds'          => 'Financial Readiness',
            'language'       => 'Language Proficiency',
            'age'            => 'Age Factor',
            'experience'     => 'Work Experience',
            'documents'      => 'Document Readiness',
            'travel_history' => 'Travel History',
        ];

        $count = 0;
        foreach ($dimensions as $key => $dim) {
            if ($count >= 3) break;
            if ($dim['score'] <= 20) continue; // Skip very low-risk items

            $weakAreas[] = [
                'dimension' => $key,
                'name'      => $labels[$key] ?? ucwords(str_replace('_', ' ', $key)),
                'score'     => $dim['score'],
                'label'     => $dim['label'],
                'tip'       => $dim['tip'],
            ];
            $count++;
        }

        return $weakAreas;
    }
}
