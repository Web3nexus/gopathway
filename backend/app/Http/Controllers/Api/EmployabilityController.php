<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Occupation;
use App\Services\EmployabilityScoringService;

class EmployabilityController extends Controller
{
    public function getScore(Request $request, EmployabilityScoringService $service)
    {
        $user = $request->user();
        if (!$user->profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $result = $service->calculateScore($user->profile);

        return response()->json([
            'data' => $result
        ]);
    }

    public function getOccupations()
    {
        $occupations = Occupation::orderBy('category')->orderBy('name')->get();

        // Group by category for frontend convenience
        $grouped = $occupations->groupBy('category');

        return response()->json([
            'data' => $grouped
        ]);
    }
}
