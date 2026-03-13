<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CostItemRequest;
use App\Models\CostItem;
use Illuminate\Http\JsonResponse;

class CostItemController extends Controller
{
    public function index(): JsonResponse
    {
        $query = CostItem::with(['pathway.country', 'country', 'visaType']);

        if (request()->filled('country_id')) {
            $query->where('country_id', request('country_id'));
        }

        if (request()->filled('visa_type_id')) {
            $query->where('visa_type_id', request('visa_type_id'));
        }

        if (request()->has('is_template')) {
            $query->whereNull('pathway_id');
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }

    public function store(CostItemRequest $request): JsonResponse
    {
        $costItem = CostItem::create($request->validated());
        return response()->json(['data' => $costItem], 201);
    }

    public function update(CostItemRequest $request, CostItem $costItem): JsonResponse
    {
        $costItem->update($request->validated());
        return response()->json(['data' => $costItem]);
    }

    public function destroy(CostItem $costItem): JsonResponse
    {
        $costItem->delete();
        return response()->json(null, 204);
    }
}
