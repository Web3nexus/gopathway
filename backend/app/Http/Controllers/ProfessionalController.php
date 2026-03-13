<?php

namespace App\Http\Controllers;

use App\Models\ProfessionalProfile;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfessionalController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'type' => 'required|in:translator,lawyer',
            'bio' => 'required|string|max:1000',
            'specialization' => 'nullable|array',
            'languages' => 'nullable|array',
            'years_of_experience' => 'required|integer|min:0',
            'document' => 'required|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $user = $request->user();

        return DB::transaction(function () use ($request, $user) {
            // Update or create professional profile
            $profile = ProfessionalProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'type' => $request->input('type'),
                    'bio' => $request->input('bio'),
                    'specialization' => $request->input('specialization'),
                    'languages' => $request->input('languages'),
                    'years_of_experience' => $request->input('years_of_experience'),
                    'is_verified' => false,
                ]
            );

            // Store verification document
            $path = $request->file('document')->store('verifications/' . $user->id);

            // Create verification request
            VerificationRequest::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'document_path' => $path,
            ]);

            // Assign role if not already assigned
            if (!$user->hasRole($request->input('type'))) {
                $user->assignRole($request->input('type'));
            }

            return response()->json([
                'message' => 'Application submitted successfully.',
                'profile' => $profile,
            ]);
        });
    }

    public function status(Request $request)
    {
        $user = $request->user()->load([
            'professionalProfile',
            'verificationRequests' => function ($query) {
                $query->latest();
            }
        ]);

        return response()->json([
            'profile' => $user->professionalProfile,
            'latest_verification' => $user->verificationRequests->first(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $profile = $user->professionalProfile;

        if (!$profile) {
            return response()->json(['message' => 'Professional profile not found'], 404);
        }

        $validated = $request->validate([
            'bio' => 'nullable|string',
            'specialization' => 'nullable|array',
            'languages' => 'nullable|array',
            'years_of_experience' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'is_available' => 'nullable|boolean',
        ]);

        $profile->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile->fresh()
        ]);
    }
}
