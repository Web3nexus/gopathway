<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ResidencyRule;
use App\Models\UserResidencyTracking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidencyController extends Controller
{
    /**
     * Get residency rules for a specific country.
     */
    public function getRules(Country $country): JsonResponse
    {
        $rules = ResidencyRule::where('country_id', $country->id)->first();
        return response()->json(['data' => $rules]);
    }

    /**
     * Get the authenticated user's residency tracking info.
     */
    public function getTracking(Request $request): JsonResponse
    {
        $countryId = $request->query('country_id');

        $query = UserResidencyTracking::where('user_id', Auth::id())->with('country');

        if ($countryId) {
            $query->where('country_id', $countryId);
        }

        $tracking = $query->first();

        return response()->json(['data' => $tracking]);
    }

    /**
     * Save or update the user's residency tracking info.
     */
    public function saveTracking(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'arrival_date' => 'nullable|date',
            'permit_expiry' => 'nullable|date',
            'language_progress' => 'nullable|array',
            'tax_filing' => 'nullable|array',
        ]);

        $tracking = UserResidencyTracking::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'country_id' => $validated['country_id'],
        ],
            $validated + ['user_id' => Auth::id()]
        );

        return response()->json([
            'message' => 'Residency tracking updated.',
            'data' => $tracking->load('country')
        ]);
    }
}