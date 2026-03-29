<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class FinanceManagementController extends Controller
{
    public function index(): JsonResponse
    {
        $providers = FinanceProvider::latest()->paginate(20);
        return response()->json($providers);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'provider_type' => 'required|string|max:255',
            'supported_countries' => 'nullable|array',
            'supported_pathways' => 'nullable|array',
            'website' => 'required|url',
            'contact_email' => 'nullable|email',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'rating' => 'numeric|min:0|max:5',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $provider = FinanceProvider::create($request->all());

        return response()->json([
            'message' => 'Finance provider created successfully',
            'data' => $provider
        ], 210);
    }

    public function update(Request $request, FinanceProvider $financeProvider): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'provider_type' => 'string|max:255',
            'supported_countries' => 'nullable|array',
            'supported_pathways' => 'nullable|array',
            'website' => 'url',
            'contact_email' => 'nullable|email',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'rating' => 'numeric|min:0|max:5',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $financeProvider->update($request->all());

        return response()->json([
            'message' => 'Finance provider updated successfully',
            'data' => $financeProvider
        ]);
    }

    public function destroy(FinanceProvider $financeProvider): JsonResponse
    {
        $financeProvider->delete();
        return response()->json(['message' => 'Finance provider deleted successfully']);
    }

    public function toggleActive(FinanceProvider $financeProvider): JsonResponse
    {
        $financeProvider->is_active = !$financeProvider->is_active;
        $financeProvider->save();

        return response()->json([
            'message' => 'Finance provider status updated',
            'is_active' => $financeProvider->is_active
        ]);
    }
}
