<?php

namespace App\Http\Controllers;

use App\Models\CostItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CostController extends Controller
{
    /**
     * Get base cost templates for the cost planner.
     */
    public function index(Request $request): JsonResponse
    {
        $countryId = $request->query('country_id');
        $visaTypeId = $request->query('visa_type_id');

        // Return cost items that are not assigned to a specific pathway (templates)
        $query = CostItem::whereNull('pathway_id');

        if ($countryId) {
            $query->where(function ($q) use ($countryId) {
                $q->whereNull('country_id') // Global
                  ->orWhere('country_id', $countryId); // Country specific
            });
        }

        if ($visaTypeId) {
            $query->where(function ($q) use ($visaTypeId) {
                $q->whereNull('visa_type_id') // Not visa specific
                  ->orWhere('visa_type_id', $visaTypeId); // Visa specific
            });
        }

        $templates = $query->get();
        return response()->json($templates);
    }
}
