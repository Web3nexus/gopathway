<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pathway;
use App\Services\RiskAnalysisService;

class ComparisonController extends Controller
{
    /**
     * Compare multiple pathways for the authenticated user.
     * Expects GET ?ids[]=1&ids[]=2
     */
    public function compare(Request $request, RiskAnalysisService $riskAnalysisService)
    {
        $request->validate([
            'ids' => 'required|array|min:2|max:3',
            'ids.*' => 'integer|exists:pathways,id',
        ]);

        $user = $request->user();

        $pathways = Pathway::with(['country', 'visaType', 'costItems', 'timelineSteps', 'documents'])
            ->where('user_id', $user->id)
            ->whereIn('id', $request->query('ids'))
            ->get();

        if ($pathways->count() < 2) {
            return response()->json(['message' => 'Please select at least two of your own pathways to compare.'], 400);
        }

        $comparisonData = $pathways->map(function ($pathway) use ($riskAnalysisService, $user) {
            // Risk Analysis
            $riskReport = $riskAnalysisService->analyze($user, $pathway);
            $visa = $pathway->visaType;

            return [
            'id' => $pathway->id,
            'country' => $pathway->country->name,
            'visa_type' => $visa->name,
            'pathway_type' => $visa->pathway_type,
            'status' => $pathway->status,
            'readiness_score' => $pathway->readiness_score,

            // Costs
            'total_cost' => $pathway->costItems->sum('amount'),
            'cost_items_count' => $pathway->costItems->count(),

            // Timeline
            'total_steps' => $pathway->timelineSteps->count(),
            'completed_steps' => $pathway->timelineSteps->where('status', 'completed')->count(),

            // Intelligence
            'pr_possibility' => (bool)$visa->pr_possibility,
            'benefits' => $visa->benefits,
            'restrictions' => $visa->restrictions,
            'official_source_link' => $visa->official_source_link,

            // Requirements
            'min_funds' => $visa->min_funds_required,
            'min_ielts' => $visa->min_ielts_score,
            'min_experience' => $visa->min_work_experience_years,

            // Risk & Success
            'risk_level' => $riskReport['overall_risk_level'] ?? 'Medium',
            'risk_score' => $riskReport['overall_risk_score'] ?? 50,
            'success_probability' => 100 - ($riskReport['overall_risk_score'] ?? 50),

            // Specific dimensions for frontend radar chart
            'dimensions' => [
            'costs' => min(100, max(0, 100 - ($pathway->costItems->sum('amount') / 200))), // Heuristic: lower cost = better score
            'timeline' => min(100, max(0, 100 - ($pathway->timelineSteps->count() * 5))), // Fewer steps = better score
            'risk' => 100 - ($riskReport['overall_risk_score'] ?? 50), // Lower risk = better score
            'readiness' => $pathway->readiness_score ?? 50,
            'pr_potential' => $visa->pr_possibility ? 90 : 30, // New dimension for PR
            ]
            ];
        });

        return response()->json([
            'data' => $comparisonData,
        ]);
    }
}