<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\JobPlatform;
use App\Models\ResidencyRule;
use App\Models\CvTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    // ── Job Platforms ──────────────────────────────────────────

    public function storeJobPlatform(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'website_url' => 'required|url',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'tips' => 'nullable|array',
        ]);

        $platform = JobPlatform::create($data);
        return response()->json(['data' => $platform], 201);
    }

    public function updateJobPlatform(Request $request, JobPlatform $platform): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'website_url' => 'sometimes|url',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'tips' => 'nullable|array',
        ]);

        $platform->update($data);
        return response()->json(['data' => $platform]);
    }

    public function destroyJobPlatform(JobPlatform $platform): JsonResponse
    {
        $platform->delete();
        return response()->json(['message' => 'Job platform deleted.']);
    }

    // ── Residency Rules ─────────────────────────────────────────

    public function updateResidencyRules(Request $request, Country $country): JsonResponse
    {
        $data = $request->validate([
            'temporary_reqs' => 'nullable|array',
            'permanent_reqs' => 'nullable|array',
            'citizenship_reqs' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $rules = ResidencyRule::updateOrCreate(
        ['country_id' => $country->id],
            $data
        );

        return response()->json(['data' => $rules]);
    }

    // ── CV Templates ────────────────────────────────────────────

    public function storeCvTemplate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'cv_format_rules' => 'nullable|array',
            'structure_json' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $template = CvTemplate::create($data);
        return response()->json(['data' => $template], 201);
    }

    public function updateCvTemplate(Request $request, CvTemplate $template): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'cv_format_rules' => 'nullable|array',
            'structure_json' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $template->update($data);
        return response()->json(['data' => $template]);
    }

    public function destroyCvTemplate(CvTemplate $template): JsonResponse
    {
        $template->delete();
        return response()->json(['message' => 'CV template deleted.']);
    }
}