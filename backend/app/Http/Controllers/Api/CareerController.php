<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\JobPlatform;
use App\Models\CvTemplate;
use App\Models\UserCv;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareerController extends Controller
{
    /**
     * Get job platforms for a specific country.
     */
    public function getJobPlatforms(Country $country): JsonResponse
    {
        $platforms = JobPlatform::where('country_id', $country->id)->get();
        return response()->json(['data' => $platforms]);
    }

    /**
     * Get CV templates / rules for a specific country.
     */
    public function getCvTemplates(Country $country): JsonResponse
    {
        $templates = CvTemplate::where('country_id', $country->id)->get();
        return response()->json(['data' => $templates]);
    }

    /**
     * Get the user's saved CVs.
     */
    public function getMyCvs(): JsonResponse
    {
        $cvs = UserCv::where('user_id', Auth::id())->with(['country', 'template'])->get();
        return response()->json(['data' => $cvs]);
    }

    /**
     * Save or generate a CV.
     */
    public function saveCv(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'cv_template_id' => 'required|exists:cv_templates,id',
            'cv_data' => 'required|array',
        ]);

        $cv = UserCv::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'country_id' => $validated['country_id'],
            'cv_template_id' => $validated['cv_template_id'],
        ],
        [
            'cv_data' => $validated['cv_data'],
        ]
        );

        return response()->json([
            'message' => 'CV saved successfully.',
            'data' => $cv->load(['country', 'template'])
        ]);
    }

    /**
     * Delete a saved CV.
     */
    public function deleteCv(UserCv $cv): JsonResponse
    {
        if ($cv->user_id !== Auth::id()) {
            abort(403);
        }

        $cv->delete();

        return response()->json(['message' => 'CV deleted successfully.']);
    }

    /**
     * Simulate AI Cover Letter Generation.
     */
    public function generateCoverLetter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'job_title' => 'required|string',
            'company_name' => 'required|string',
            'industry' => 'nullable|string',
            'tone' => 'nullable|string|in:professional,enthusiastic,direct',
            'key_skills' => 'nullable|array',
        ]);

        // Simulated AI response for now
        $simulatedLetter = "Dear Hiring Manager at {$validated['company_name']},\n\n"
            . "I am writing to express my strong interest in the {$validated['job_title']} position. "
            . "With my background in " . ($validated['industry'] ?? 'your industry') . " and my specific skills in "
            . implode(', ', $validated['key_skills'] ?? ['relevant technologies']) . ", I believe I would be a great fit for the team.\n\n"
            . "Please find my CV attached for your review. I look forward to discussing how I can contribute to {$validated['company_name']}.\n\n"
            . "Sincerely,\n[Your Name]";

        return response()->json([
            'message' => 'Cover letter generated successfully.',
            'data' => [
                'cover_letter' => $simulatedLetter
            ]
        ]);
    }
}