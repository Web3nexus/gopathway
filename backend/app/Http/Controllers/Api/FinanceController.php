<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinanceProvider;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Get recommended finance providers based on user's active pathway.
     */
    public function recommendations(Request $request)
    {
        $user = $request->user();
        $pathway = $user->activePathway;

        if (!$pathway) {
            return response()->json(['data' => []]);
        }

        $country = $pathway->country->name;
        $visaType = $pathway->visaType->name;

        // Simple filtering logic:
        // Match if supported_countries contains the country OR is null/empty
        // AND match if supported_pathways contains the visa type OR is null/empty
        $providers = FinanceProvider::where('is_active', true)
            ->get()
            ->filter(function ($provider) use ($country, $visaType) {
            $countries = $provider->supported_countries;
            $pathways = $provider->supported_pathways;

            $countryMatch = empty($countries) || in_array($country, (array)$countries) || in_array('Global', (array)$countries);
            $pathwayMatch = empty($pathways) || in_array($visaType, (array)$pathways);

            return $countryMatch && $pathwayMatch;
        })
            ->values();

        return response()->json([
            'data' => $providers,
            'meta' => [
                'disclaimer' => 'GoPathway does not provide loans or financial services. We only recommend verified providers. Users must perform their own due diligence.'
            ]
        ]);
    }
}