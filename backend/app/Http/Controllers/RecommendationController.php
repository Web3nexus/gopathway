<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Get tailored recommendations for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $recommendations = $this->recommendationService->getRecommendations($request->user());

        return response()->json([
            'data' => $recommendations
        ]);
    }
}
