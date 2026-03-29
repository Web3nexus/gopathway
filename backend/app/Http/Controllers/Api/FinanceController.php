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

        $country = strtolower($pathway->country->name);
        $visaType = strtolower($pathway->visaType->name);

        // Flexible filtering logic:
        $providers = FinanceProvider::where('is_active', true)
            ->get()
            ->filter(function ($provider) use ($country, $visaType) {
            $countries = array_map('strtolower', (array)$provider->supported_countries);
            $pathways = array_map('strtolower', (array)$provider->supported_pathways);

            // Match if:
            // 1. Countries list is empty/null OR contains the country OR contains 'global'
            $countryMatch = empty($countries) || 
                           in_array($country, $countries) || 
                           in_array('global', $countries);

            // 2. Pathways list is empty/null OR contains exact visa type OR partial match (e.g. "Student" in "Student Visa")
            $pathwayMatch = empty($pathways);
            if (!$pathwayMatch) {
                foreach ($pathways as $p) {
                    if (str_contains($visaType, $p) || str_contains($p, $visaType)) {
                        $pathwayMatch = true;
                        break;
                    }
                }
            }

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