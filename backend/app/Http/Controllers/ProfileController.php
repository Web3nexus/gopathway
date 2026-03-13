<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $profile = $request->user()->profile;

        if (!$profile) {
            $profile = $request->user()->profile()->create([]);
        }

        return new ProfileResource($profile->load('preferredCountry'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $profile = $user->profile ?? $user->profile()->create([]);

        $profile->update($request->validated());

        // Handle user-level fields if they are in the request (e.g. from a common update form)
        if ($request->hasAny(['current_savings', 'monthly_savings_target', 'email_notifications'])) {
            $user->update($request->only(['current_savings', 'monthly_savings_target', 'email_notifications']));
        }

        return new ProfileResource($profile->fresh()->load('preferredCountry'));
    }

    /**
     * Specifically update budget/savings fields.
     */
    public function updateBudget(Request $request)
    {
        $validated = $request->validate([
            'current_savings' => 'nullable|numeric|min:0',
            'monthly_savings_target' => 'nullable|numeric|min:0',
        ]);

        $request->user()->update($validated);

        return response()->json([
            'message' => 'Budget updated successfully',
            'user' => $request->user()
        ]);
    }
}
