<?php

namespace App\Http\Controllers;

use App\Models\Pathway;
use App\Models\RiskReport;
use App\Services\RiskAnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiskAnalysisController extends Controller
{
    public function __construct(private RiskAnalysisService $service)
    {
    }

    /**
     * Get the risk analysis for a user's pathway.
     */
    public function show(Request $request, Pathway $pathway): JsonResponse
    {
        $user = $request->user();

        // Ensure the pathway belongs to this user
        if ($pathway->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $report = RiskReport::where('user_id', $user->id)
            ->where('pathway_id', $pathway->id)
            ->first();

        if (!$report || $report->calculated_at?->lt(now()->subHours(6))) {
            // Auto-calculate if stale (>6h) or missing
            $report = $this->service->analyze(
                $user->load(['profile', 'documents']),
                $pathway
            );
        }

        return response()->json([
            'data' => $this->formatResponse($report, $user->isPremium()),
        ]);
    }

    /**
     * Force recalculate the risk analysis.
     */
    public function calculate(Request $request, Pathway $pathway): JsonResponse
    {
        $user = $request->user();

        // Ensure the pathway belongs to this user
        if ($pathway->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $report = $this->service->analyze(
            $user->load(['profile', 'documents']),
            $pathway
        );

        return response()->json([
            'data' => $this->formatResponse($report, $user->isPremium()),
        ]);
    }

    /**
     * Format the response based on premium status.
     * Free: risk level, overall score, top 3 weak areas.
     * Premium: + full dimension breakdown with scores and tips.
     */
    private function formatResponse(RiskReport $report, bool $isPremium): array
    {
        $data = [
            'risk_level'    => $report->risk_level,
            'risk_score'    => $report->risk_score,
            'calculated_at' => $report->calculated_at,
            'weak_areas'    => $report->weak_areas,
            'is_premium'    => $isPremium,
        ];

        if ($isPremium) {
            $data['dimensions'] = [
                'funds'          => ['score' => $report->funds_risk, 'weight' => 25, 'details' => $report->full_report['dimensions']['funds'] ?? null],
                'language'       => ['score' => $report->language_risk, 'weight' => 20, 'details' => $report->full_report['dimensions']['language'] ?? null],
                'age'            => ['score' => $report->age_risk, 'weight' => 10, 'details' => $report->full_report['dimensions']['age'] ?? null],
                'experience'     => ['score' => $report->experience_risk, 'weight' => 15, 'details' => $report->full_report['dimensions']['experience'] ?? null],
                'documents'      => ['score' => $report->documents_risk, 'weight' => 15, 'details' => $report->full_report['dimensions']['documents'] ?? null],
                'travel_history' => ['score' => $report->travel_history_risk, 'weight' => 15, 'details' => $report->full_report['dimensions']['travel_history'] ?? null],
            ];
        }

        return $data;
    }
}
