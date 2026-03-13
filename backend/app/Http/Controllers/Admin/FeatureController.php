<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * List all features for admin.
     */
    public function index()
    {
        return response()->json([
            'data' => Feature::all()
        ]);
    }

    /**
     * Update feature premium status.
     */
    public function update(Request $request, Feature $feature)
    {
        $validated = $request->validate([
            'is_premium' => 'required|boolean',
        ]);

        $feature->update($validated);

        return response()->json([
            'message' => "Feature {$feature->name} updated successfully.",
            'data' => $feature
        ]);
    }

    /**
     * List all platform features (global release flags).
     */
    public function indexPlatformFeatures()
    {
        return response()->json([
            'data' => \App\Models\PlatformFeature::all()
        ]);
    }

    /**
     * Toggle a platform feature globally.
     */
    public function togglePlatformFeature(Request $request, $id)
    {
        $feature = \App\Models\PlatformFeature::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'sometimes|boolean',
            'is_premium' => 'sometimes|boolean',
        ]);

        if (isset($validated['is_active']) && $validated['is_active'] && !$feature->is_active) {
            \App\Jobs\NotifyUsersOfNewFeature::dispatch($feature);
        }

        $feature->update($validated);

        return response()->json([
            'message' => "Platform feature {$feature->feature_name} " . ($feature->is_active ? 'enabled' : 'disabled') . ".",
            'data' => $feature
        ]);
    }
}