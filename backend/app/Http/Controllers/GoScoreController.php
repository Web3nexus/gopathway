<?php

namespace App\Http\Controllers;

use App\Models\GoScore;
use App\Services\GoScoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoScoreController extends Controller
{
    public function __construct(private GoScoreService $service)
    {
    }

    /**
     * Get the current user's GoScore.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $goScore = GoScore::where('user_id', $user->id)->first();

        if (!$goScore || $goScore->calculated_at?->lt(now()->subHours(6))) {
            // Auto-calculate if stale (>6h) or missing
            $goScore = $this->service->calculate($user->load(['profile', 'documents', 'timelineSteps']));
        }

        $isPremium = $user->isPremium();

        return response()->json([
            'data' => [
                'total' => $goScore->total,
                'calculated_at' => $goScore->calculated_at,
                // Only return breakdown for premium users
                'dimensions' => $isPremium ? [
                    'profile' => ['score' => $goScore->profile_score, 'weight' => 20, 'details' => $goScore->breakdown['profile'] ?? null],
                    'funds' => ['score' => $goScore->funds_score, 'weight' => 25, 'details' => $goScore->breakdown['funds'] ?? null],
                    'language' => ['score' => $goScore->language_score, 'weight' => 20, 'details' => $goScore->breakdown['language'] ?? null],
                    'documents' => ['score' => $goScore->documents_score, 'weight' => 20, 'details' => $goScore->breakdown['documents'] ?? null],
                    'timeline' => ['score' => $goScore->timeline_score, 'weight' => 15, 'details' => $goScore->breakdown['timeline'] ?? null],
                ] : null,
                'is_premium' => $isPremium,
                'labels' => [
                    'readiness' => \App\Models\Setting::where('key', 'label_go_score_readiness')->value('value') ?? 'Your Readiness',
                    'title' => \App\Models\Setting::where('key', 'label_go_score_title')->value('value') ?? 'GoScore™',
                    'breakdown' => \App\Models\Setting::where('key', 'label_go_score_breakdown')->value('value') ?? 'Score Breakdown',
                ]
            ]
        ]);
    }

    /**
     * Force recalculate the GoScore.
     */
    public function calculate(Request $request): JsonResponse
    {
        $user = $request->user()->load(['profile', 'documents', 'timelineSteps']);
        $goScore = $this->service->calculate($user);
        $isPremium = $user->isPremium();

        return response()->json([
            'data' => [
                'total' => $goScore->total,
                'calculated_at' => $goScore->calculated_at,
                'dimensions' => $isPremium ? [
                    'profile' => ['score' => $goScore->profile_score, 'weight' => 20, 'details' => $goScore->breakdown['profile'] ?? null],
                    'funds' => ['score' => $goScore->funds_score, 'weight' => 25, 'details' => $goScore->breakdown['funds'] ?? null],
                    'language' => ['score' => $goScore->language_score, 'weight' => 20, 'details' => $goScore->breakdown['language'] ?? null],
                    'documents' => ['score' => $goScore->documents_score, 'weight' => 20, 'details' => $goScore->breakdown['documents'] ?? null],
                    'timeline' => ['score' => $goScore->timeline_score, 'weight' => 15, 'details' => $goScore->breakdown['timeline'] ?? null],
                ] : null,
                'is_premium' => $isPremium,
                'labels' => [
                    'readiness' => \App\Models\Setting::where('key', 'label_go_score_readiness')->value('value') ?? 'Your Readiness',
                    'title' => \App\Models\Setting::where('key', 'label_go_score_title')->value('value') ?? 'GoScore™',
                    'breakdown' => \App\Models\Setting::where('key', 'label_go_score_breakdown')->value('value') ?? 'Score Breakdown',
                ]
            ]
        ]);
    }
}
