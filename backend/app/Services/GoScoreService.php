<?php

namespace App\Services;

use App\Models\GoScore;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GoScoreService
{
    // Dimension weights (must add to 100)
    private const WEIGHTS = [
        'profile' => 20,
        'funds' => 25,
        'language' => 20,
        'documents' => 20,
        'timeline' => 15,
    ];

    // Funds range midpoints (in USD) for scoring
    private const FUNDS_RANGES = [
        '0-5000' => 5000,
        '5000-10000' => 7500,
        '10000-20000' => 15000,
        '20000-50000' => 35000,
        '50000+' => 75000,
    ];

    public function calculate(User $user): GoScore
    {
        $user->loadMissing(['profile', 'documents', 'timelineSteps', 'pathway']);

        $profile = $user->profile;
        $pathway = $user->pathway()->with('visaType')->latest()->first();
        $documents = $user->documents;
        $steps = $user->timelineSteps;

        // ----- 1. Profile Completeness -----
        $profileDetails = $this->scoreProfile($profile);
        $profileScore = $profileDetails['score'];

        // ----- 2. Funds Readiness -----
        $fundsDetails = $this->scoreFunds($profile, $pathway);
        $fundsScore = $fundsDetails['score'];

        // ----- 3. Language Readiness -----
        $languageDetails = $this->scoreLanguage($profile);
        $languageScore = $languageDetails['score'];

        // ----- 4. Document Readiness -----
        $documentsDetails = $this->scoreDocuments($documents);
        $documentsScore = $documentsDetails['score'];

        // ----- 5. Timeline Progress -----
        $timelineDetails = $this->scoreTimeline($steps);
        $timelineScore = $timelineDetails['score'];

        // ----- Weighted Total -----
        $total = (int) round(
            ($profileScore * self::WEIGHTS['profile']) / 100 +
            ($fundsScore * self::WEIGHTS['funds']) / 100 +
            ($languageScore * self::WEIGHTS['language']) / 100 +
            ($documentsScore * self::WEIGHTS['documents']) / 100 +
            ($timelineScore * self::WEIGHTS['timeline']) / 100
        );

        $breakdown = [
            'profile' => $profileDetails,
            'funds' => $fundsDetails,
            'language' => $languageDetails,
            'documents' => $documentsDetails,
            'timeline' => $timelineDetails,
            'weights' => self::WEIGHTS,
        ];

        return GoScore::updateOrCreate(
            ['user_id' => $user->id],
            [
                'profile_score' => $profileScore,
                'funds_score' => $fundsScore,
                'language_score' => $languageScore,
                'documents_score' => $documentsScore,
                'timeline_score' => $timelineScore,
                'total' => $total,
                'breakdown' => $breakdown,
                'calculated_at' => now(),
            ]
        );
    }

    // ─────────────────────────────────────────
    //  Dimension Scorers
    // ─────────────────────────────────────────

    private function scoreProfile(?object $profile): array
    {
        if (!$profile) {
            return ['score' => 0, 'label' => 'Profile incomplete', 'tips' => ['Complete your profile to improve your GoScore.']];
        }

        $fields = ['age', 'education_level', 'work_experience_years', 'funds_range', 'ielts_status', 'preferred_country_id'];
        $filled = collect($fields)->filter(fn($f) => !empty($profile->$f))->count();
        $score = (int) round(($filled / count($fields)) * 100);

        $tips = [];
        if (empty($profile->ielts_status))
            $tips[] = 'Add your language test status.';
        if (empty($profile->funds_range))
            $tips[] = 'Add your estimated funds range.';
        if (empty($profile->work_experience_years))
            $tips[] = 'Add your work experience.';

        return ['score' => $score, 'label' => "{$filled}/" . count($fields) . " fields complete", 'tips' => $tips];
    }

    private function scoreFunds(?object $profile, ?object $pathway): array
    {
        if (!$profile || empty($profile->funds_range)) {
            return ['score' => 0, 'label' => 'No funds data', 'tips' => ['Add your funds range to your profile.']];
        }

        $userFunds = self::FUNDS_RANGES[$profile->funds_range] ?? 0;
        $required = $pathway?->visaType?->min_funds_required ?? 10000; // Default $10k

        if ($required == 0) {
            return ['score' => 75, 'label' => 'No minimum specified', 'tips' => []];
        }

        $ratio = min($userFunds / $required, 1.5); // Cap at 150%
        $score = (int) min(round($ratio / 1.5 * 100), 100);

        $tips = [];
        if ($score < 70)
            $tips[] = 'Increase your savings to meet the minimum required funds.';
        if ($score < 50)
            $tips[] = 'Your current funds are significantly below the requirement.';

        return ['score' => $score, 'label' => number_format($userFunds, 0) . ' vs ' . number_format($required, 0) . ' required', 'tips' => $tips];
    }

    private function scoreLanguage(?object $profile): array
    {
        if (!$profile || empty($profile->ielts_status)) {
            return ['score' => 0, 'label' => 'No language test', 'tips' => ['Register for IELTS or add your test score.']];
        }

        $statusMap = [
            'not_taken' => 10,
            'registered' => 30,
            'taken' => 60,
            'band_6' => 70,
            'band_6_5' => 80,
            'band_7' => 90,
            'band_7_5' => 95,
            'band_8' => 100,
        ];

        $score = $statusMap[$profile->ielts_status] ?? 20;

        $tips = [];
        if ($score < 70)
            $tips[] = 'Aim for IELTS Band 6.5 or above for most visas.';
        if ($score < 50)
            $tips[] = 'Consider booking an IELTS preparation course.';

        return ['score' => $score, 'label' => ucwords(str_replace('_', ' ', $profile->ielts_status)), 'tips' => $tips];
    }

    private function scoreDocuments($documents): array
    {
        $count = $documents->count();

        if ($count === 0) {
            return ['score' => 0, 'label' => 'No documents uploaded', 'tips' => ['Start uploading your key documents to the Document Vault.']];
        }

        $approved = $documents->where('status', 'approved')->count();
        $uploaded = $documents->whereIn('status', ['uploaded', 'approved'])->count();

        // Base score on having documents, bonus for approved
        $baseScore = min($uploaded * 12, 70);         // Up to 70 for having docs
        $approvalBonus = min($approved * 10, 30);          // Up to 30 for approved
        $score = min($baseScore + $approvalBonus, 100);

        $tips = [];
        if ($uploaded < 3)
            $tips[] = 'Upload at least 3 key documents (passport, bank statement, academic certificate).';
        if ($approved < $uploaded)
            $tips[] = 'Get your documents reviewed and approved by an expert.';

        return ['score' => $score, 'label' => "{$uploaded} uploaded, {$approved} approved", 'tips' => $tips];
    }

    private function scoreTimeline($steps): array
    {
        $total = $steps->count();
        $completed = $steps->whereNotNull('completed_at')->count();

        if ($total === 0) {
            return ['score' => 0, 'label' => 'No pathway selected', 'tips' => ['Select a destination pathway to start your roadmap.']];
        }

        $score = (int) round(($completed / $total) * 100);

        $tips = [];
        if ($score < 50)
            $tips[] = 'Start completing your timeline steps to stay on track.';

        return ['score' => $score, 'label' => "{$completed}/{$total} steps completed", 'tips' => $tips];
    }
}
