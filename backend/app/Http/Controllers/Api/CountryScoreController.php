<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryScoreController extends Controller
{
    /**
     * Get scores for all active countries.
     */
    public function index()
    {
        $countries = Country::where('is_active', true)
            ->with('score')
            ->get()
            ->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'code' => $country->code,
                    'image_url' => $country->image_url,
                    'scores' => $country->score ? [
                        'visa_difficulty' => $country->score->visa_difficulty,
                        'cost_index' => $country->score->cost_index,
                        'processing_speed' => $country->score->processing_speed,
                        'pr_ease' => $country->score->pr_ease,
                        'job_market' => $country->score->job_market,
                    ] : [
                        'visa_difficulty' => 50,
                        'cost_index' => 50,
                        'processing_speed' => 50,
                        'pr_ease' => 50,
                        'job_market' => 50,
                    ],
                ];
            });

        return response()->json(['data' => $countries]);
    }

    /**
     * Compare specific countries.
     */
    public function compare(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:countries,id',
        ]);

        $countries = Country::whereIn('id', $request->ids)
            ->with('score')
            ->get()
            ->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'code' => $country->code,
                    'image_url' => $country->image_url,
                    'scores' => $country->score ? [
                        'visa_difficulty' => $country->score->visa_difficulty,
                        'cost_index' => $country->score->cost_index,
                        'processing_speed' => $country->score->processing_speed,
                        'pr_ease' => $country->score->pr_ease,
                        'job_market' => $country->score->job_market,
                    ] : [
                        'visa_difficulty' => 50,
                        'cost_index' => 50,
                        'processing_speed' => 50,
                        'pr_ease' => 50,
                        'job_market' => 50,
                    ],
                ];
            });

        return response()->json(['data' => $countries]);
    }
}
