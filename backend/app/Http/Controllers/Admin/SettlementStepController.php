<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettlementStep;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettlementStepController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = SettlementStep::with(['country', 'visaType']);

        if ($request->filled('country_id') && $request->country_id !== 'null') {
            $query->where(function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        $steps = $query->orderBy('country_id')
            ->orderBy('phase')
            ->orderBy('order')
            ->get();

        return response()->json(['data' => $steps]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'visa_type_id' => 'nullable|exists:visa_types,id',
            'phase' => 'required|in:week1,month1,long_term',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'required_documents' => 'nullable|array',
            'official_link' => 'nullable|url',
            'estimated_time' => 'nullable|string',
            'mandatory' => 'boolean',
            'order' => 'integer',
        ]);

        $step = SettlementStep::create($validated);

        return response()->json(['data' => $step], 201);
    }

    public function show(SettlementStep $settlementStep): JsonResponse
    {
        return response()->json(['data' => $settlementStep]);
    }

    public function update(Request $request, SettlementStep $settlementStep): JsonResponse
    {
        $validated = $request->validate([
            'country_id' => 'exists:countries,id',
            'visa_type_id' => 'nullable|exists:visa_types,id',
            'phase' => 'in:week1,month1,long_term',
            'title' => 'string|max:255',
            'description' => 'string',
            'required_documents' => 'nullable|array',
            'official_link' => 'nullable|url',
            'estimated_time' => 'nullable|string',
            'mandatory' => 'boolean',
            'order' => 'integer',
        ]);

        $settlementStep->update($validated);

        return response()->json(['data' => $settlementStep]);
    }

    public function destroy(SettlementStep $settlementStep): JsonResponse
    {
        $settlementStep->delete();
        return response()->json(null, 204);
    }
}