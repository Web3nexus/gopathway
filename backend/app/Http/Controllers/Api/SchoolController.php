<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolProgram;
use App\Models\StudentVisaRequirement;
use App\Models\Country;
use App\Models\UserSchoolApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * List all schools for a given country.
     */
    public function byCountry(Country $country): JsonResponse
    {
        $schools = School::where('country_id', $country->id)
            ->where('is_active', true)
            ->with(['programs' => function ($q) {
            $q->where('is_active', true);
        }])
            ->get()
            ->map(function ($school) {
            return [
            'id' => $school->id,
            'name' => $school->name,
            'location' => $school->location,
            'type' => $school->type,
            'ranking' => $school->ranking,
            'website' => $school->website,
            'application_portal' => $school->application_portal,
            'description' => $school->description,
            'programs' => $school->programs,
            'program_count' => $school->programs->count(),
            'is_tracked' => Auth::user() ? Auth::user()->trackedSchools()->where('school_id', $school->id)->exists() : false,
            ];
        });

        return response()->json(['data' => $schools]);
    }

    /**
     * List all programs for a given school.
     */
    public function programs(School $school): JsonResponse
    {
        $programs = \App\Models\SchoolProgram::where('school_id', $school->id)
            ->where('is_active', true)
            ->get();
        return response()->json(['data' => $programs]);
    }

    /**
     * Get student visa requirements for a country.
     */
    public function studentVisa(Country $country): JsonResponse
    {
        $visa = StudentVisaRequirement::where('country_id', $country->id)->first();
        return response()->json(['data' => $visa]);
    }

    /**
     * Get the user's school applications.
     */
    public function myApplications(): JsonResponse
    {
        $applications = UserSchoolApplication::where('user_id', Auth::id())
            ->with(['school', 'program'])
            ->latest()
            ->get();

        return response()->json(['data' => $applications]);
    }

    /**
     * Save or update a school application.
     */
    public function saveApplication(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'school_program_id' => 'nullable|exists:school_programs,id',
            'status' => 'required|in:researching,preparing_documents,applied,offer_received,deposit_paid,visa_applied,accepted,rejected',
            'notes' => 'nullable|string',
            'applied_at' => 'nullable|date',
        ]);

        $application = UserSchoolApplication::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'school_id' => $validated['school_id'],
        ],
            $validated + ['user_id' => Auth::id()]
        );

        return response()->json(['data' => $application->load(['school', 'program'])]);
    }

    /**
     * Remove a school application.
     */
    public function destroyApplication(UserSchoolApplication $application): JsonResponse
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }
        $application->delete();
        return response()->json(['message' => 'Application removed.']);
    }

    /**
     * Get the user's tracked schools.
     */
    public function myTrackedSchools(): JsonResponse
    {
        $schools = Auth::user()->trackedSchools()->with('country')->get();
        return response()->json(['data' => $schools]);
    }

    /**
     * Track a school.
     */
    public function track(School $school): JsonResponse
    {
        $user = Auth::user();
        
        if ($user->trackedSchools()->where('school_id', $school->id)->exists()) {
            return response()->json(['message' => 'Already tracking this school.'], 400);
        }

        $user->trackedSchools()->attach($school->id);

        // Immediate Notification (In-App)
        $user->notifications()->create([
            'title' => 'School Tracked: ' . $school->name,
            'message' => 'You are now tracking ' . $school->name . '. You will receive updates about this school.',
            'is_read' => false,
        ]);

        // Email Notification
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\DynamicEmail('school_tracked', [
                'user_name' => $user->name,
                'school_name' => $school->name,
                'school_url' => config('app.frontend_url') . '/dashboard/schools/' . $school->id,
            ]));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send school tracking email: " . $e->getMessage());
        }

        return response()->json(['message' => 'School tracked successfully.']);
    }

    /**
     * Untrack a school.
     */
    public function untrack(School $school): JsonResponse
    {
        Auth::user()->trackedSchools()->detach($school->id);
        return response()->json(['message' => 'School untracked successfully.']);
    }
}