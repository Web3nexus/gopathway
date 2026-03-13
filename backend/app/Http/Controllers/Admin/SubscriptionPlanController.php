<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => SubscriptionPlan::orderBy('tier')->orderBy('interval')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans,slug',
            'tier' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'interval' => 'required|in:month,year',
            'features' => 'nullable|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $plan = SubscriptionPlan::create($validated);

        return response()->json(['data' => $plan], 201);
    }

    public function update(Request $request, SubscriptionPlan $subscription_plan)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:subscription_plans,slug,' . $subscription_plan->id,
            'tier' => 'sometimes|string|max:50',
            'price' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|string|max:3',
            'interval' => 'sometimes|in:month,year',
            'features' => 'nullable|array',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $subscription_plan->update($validated);

        return response()->json(['data' => $subscription_plan]);
    }

    public function destroy(SubscriptionPlan $subscription_plan)
    {
        $subscription_plan->delete();
        return response()->json(null, 204);
    }
}
