<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RelocationKit;
use App\Models\RelocationKitItem;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RelocationKitController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = RelocationKit::with(['country', 'items']);

        if ($request->filled('country_id') && $request->country_id !== 'null') {
            $query->where(function ($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        $kits = $query->orderBy('country_id')
            ->orderBy('order')
            ->get();

        return response()->json(['data' => $kits]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'is_premium' => 'boolean',
            'order' => 'integer',
        ]);

        $kit = RelocationKit::create($validated);

        return response()->json(['data' => $kit], 201);
    }

    public function show(RelocationKit $relocationKit): JsonResponse
    {
        return response()->json(['data' => $relocationKit->load('items')]);
    }

    public function update(Request $request, RelocationKit $relocationKit): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'icon' => 'nullable|string',
            'is_premium' => 'boolean',
            'order' => 'integer',
        ]);

        $relocationKit->update($validated);

        return response()->json(['data' => $relocationKit]);
    }

    public function destroy(RelocationKit $relocationKit): JsonResponse
    {
        $relocationKit->delete();
        return response()->json(null, 204);
    }

    // Items management
    public function storeItem(Request $request, RelocationKit $relocationKit): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_premium' => 'boolean',
            'order' => 'integer',
        ]);

        $item = $relocationKit->items()->create($validated);

        return response()->json(['data' => $item], 201);
    }

    public function updateItem(Request $request, RelocationKit $relocationKit, RelocationKitItem $item): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'is_premium' => 'boolean',
            'order' => 'integer',
        ]);

        $item->update($validated);

        return response()->json(['data' => $item]);
    }

    public function destroyItem(RelocationKit $relocationKit, RelocationKitItem $item): JsonResponse
    {
        $item->delete();
        return response()->json(null, 204);
    }
}