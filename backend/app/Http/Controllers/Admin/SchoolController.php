<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolProgram;
use App\Models\StudentVisaRequirement;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    // ── Schools ──────────────────────────────────────────────────

    public function index(Request $request): JsonResponse
    {
        $schools = School::with('country', 'programs')
            ->when($request->country_id, fn($q) => $q->where('country_id', $request->country_id))
            ->latest()
            ->get();

        return response()->json(['data' => $schools]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:public,private,college,technical',
            'ranking' => 'nullable|string|max:100',
            'website' => 'nullable|url',
            'application_portal' => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        $school = School::create($data);
        return response()->json(['data' => $school], 201);
    }

    public function update(Request $request, School $school): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'sometimes|in:public,private,college,technical',
            'ranking' => 'nullable|string|max:100',
            'website' => 'nullable|url',
            'application_portal' => 'nullable|url',
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $school->update($data);
        return response()->json(['data' => $school]);
    }

    public function destroy(School $school): JsonResponse
    {
        $school->delete();
        return response()->json(['message' => 'School deleted.']);
    }

    // ── Programs ─────────────────────────────────────────────────

    public function storeProgram(Request $request, School $school): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'degree_type' => 'required|in:certificate,diploma,bachelor,master,phd',
            'field_of_study' => 'nullable|string|max:255',
            'duration_years' => 'nullable|numeric|min:0.5',
            'tuition_per_year' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'application_deadline' => 'nullable|string|max:100',
            'intake_periods' => 'nullable|array',
            'min_gpa' => 'nullable|numeric|between:0,4',
            'ielts_min' => 'nullable|numeric|between:0,9',
            'toefl_min' => 'nullable|numeric',
            'pte_min' => 'nullable|numeric',
            'admission_requirements' => 'nullable|array',
        ]);

        $program = $school->programs()->create($data);
        return response()->json(['data' => $program], 201);
    }

    public function updateProgram(Request $request, School $school, SchoolProgram $program): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'degree_type' => 'sometimes|in:certificate,diploma,bachelor,master,phd',
            'field_of_study' => 'nullable|string|max:255',
            'duration_years' => 'nullable|numeric|min:0.5',
            'tuition_per_year' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'application_deadline' => 'nullable|string|max:100',
            'intake_periods' => 'nullable|array',
            'min_gpa' => 'nullable|numeric|between:0,4',
            'ielts_min' => 'nullable|numeric|between:0,9',
            'toefl_min' => 'nullable|numeric',
            'pte_min' => 'nullable|numeric',
            'admission_requirements' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $program->update($data);
        return response()->json(['data' => $program]);
    }

    public function destroyProgram(School $school, SchoolProgram $program): JsonResponse
    {
        $program->delete();
        return response()->json(['message' => 'Program deleted.']);
    }

    // ── Student Visa Requirements ─────────────────────────────────

    public function storeVisaRequirement(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'visa_name' => 'required|string|max:255',
            'visa_fee' => 'nullable|numeric|min:0',
            'visa_fee_currency' => 'nullable|string|max:3',
            'processing_time' => 'nullable|string|max:100',
            'financial_proof_required' => 'boolean',
            'min_funds_required' => 'nullable|numeric|min:0',
            'min_funds_currency' => 'nullable|string|max:3',
            'min_funds_description' => 'nullable|string|max:255',
            'work_hours_per_week' => 'nullable|integer|min:0|max:168',
            'post_study_work_permit' => 'boolean',
            'post_study_work_duration' => 'nullable|string|max:100',
            'required_documents' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $visa = StudentVisaRequirement::updateOrCreate(
        ['country_id' => $data['country_id']],
            $data
        );

        return response()->json(['data' => $visa], 201);
    }
}