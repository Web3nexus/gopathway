<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SettlementStep;
use App\Models\UserSettlementProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SettlementController extends Controller
{
    /**
     * Get settlement steps for the user's current destination.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Get user's active pathway for context
        $pathway = $user->pathway()
            ->with(['country'])
            ->where('status', 'active')
            ->latest()
            ->first();

        // Prioritise explicitly passed country_id, fall back to pathway country
        $countryId = $request->country_id;

        if (!$countryId && $pathway instanceof \App\Models\Pathway) {
            $countryId = $pathway->country_id;
        }

        if (!$countryId) {
            return response()->json([
                'data' => [],
                'summary' => ['total' => 0, 'completed' => 0],
                'debug' => 'No country context found for user: ' . $user->email,
            ]);
        }

        $steps = SettlementStep::where('country_id', $countryId)
            ->orderBy('phase')
            ->orderBy('order')
            ->get();

        Log::info("SettlementSteps API: User: {$user->email} | Country: {$countryId} | Steps: " . $steps->count());

        $progress = UserSettlementProgress::where('user_id', $user->id)
            ->pluck('completed_at', 'settlement_step_id');

        $formattedSteps = $steps->map(function ($step) use ($progress) {
            return [
            'id' => $step->id,
            'phase' => $step->phase,
            'title' => $step->title,
            'description' => $step->description,
            'required_documents' => $step->required_documents,
            'official_link' => $step->official_link,
            'estimated_time' => $step->estimated_time,
            'mandatory' => $step->mandatory,
            'completed_at' => $progress[$step->id] ?? null,
            ];
        });

        return response()->json([
            'data' => $formattedSteps,
            'summary' => [
                'total' => $steps->count(),
                'completed' => $progress->count(),
            ],
        ]);
    }

    /**
     * Toggle completion of a settlement step.
     */
    public function toggle(Request $request, SettlementStep $step): JsonResponse
    {
        $user = Auth::user();

        $progress = UserSettlementProgress::where('user_id', $user->id)
            ->where('settlement_step_id', $step->id)
            ->first();

        if ($progress) {
            $progress->delete();
            $status = 'uncompleted';
        }
        else {
            UserSettlementProgress::create([
                'user_id' => $user->id,
                'settlement_step_id' => $step->id,
                'completed_at' => now(),
            ]);
            $status = 'completed';
        }

        return response()->json([
            'message' => "Step marked as {$status}.",
            'status' => $status,
        ]);
    }
}