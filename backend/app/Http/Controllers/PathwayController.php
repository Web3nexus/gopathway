<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Notification;
use App\Models\Pathway;
use App\Models\TimelineStepTemplate;
use App\Models\VisaType;
use App\Services\RecommendationService;
use App\Services\SavingsProjectionService;
use Illuminate\Http\Request;

class PathwayController extends Controller
{
    public function __construct(
        protected RecommendationService $recommendationService,
        protected SavingsProjectionService $savingsService
    ) {
    }

    /**
     * Get all pathways for the user.
     */
    public function index(Request $request)
    {
        $pathways = $request->user()
            ->pathway()
            ->with(['country', 'visaType'])
            ->latest()
            ->get();

        return response()->json(['data' => $pathways]);
    }

    /**
     * Get the user's active pathway (most recent).
     */
    public function show(Request $request)
    {
        $pathway = $request->user()
            ->pathway()
            ->with(['country', 'visaType'])
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$pathway) {
            return response()->json(['data' => null]);
        }

        return response()->json([
            'data' => [
                'id' => $pathway->id,
                'status' => $pathway->status,
                'readiness_score' => $pathway->readiness_score,
                'current_savings' => $pathway->current_savings,
                'monthly_target' => $pathway->monthly_target,
                'target_date' => $pathway->target_date ? $pathway->target_date->toDateString() : null,
                'country' => $pathway->country ? [
                    'id' => $pathway->country->id,
                    'name' => $pathway->country->name,
                    'code' => $pathway->country->code,
                    'image_url' => $pathway->country->image_url,
                ] : null,
                'visa_type' => $pathway->visaType ? [
                    'id' => $pathway->visaType->id,
                    'name' => $pathway->visaType->name,
                    'type' => $pathway->visaType->pathway_type,
                    'cost_templates' => $pathway->visaType->costTemplates,
                ] : null,
                'labels' => [
                    'risk_level' => \App\Models\Setting::where('key', 'label_pathway_risk_level')->value('value') ?? 'Risk Level',
                    'progress' => \App\Models\Setting::where('key', 'label_pathway_progress')->value('value') ?? 'Progress',
                    'roadmap' => \App\Models\Setting::where('key', 'label_pathway_roadmap')->value('value') ?? 'Your Roadmap',
                ],
            ],
            'projection' => $this->savingsService->getProjection($pathway),
        ]);
    }

    /**
     * Select (or switch) a pathway — country + visa type.
     * Auto-populates timeline steps from admin templates and creates a welcome notification.
     */
    public function select(Request $request)
    {
        $validated = $request->validate([
            'country_id' => ['required', 'exists:countries,id'],
            'visa_type_id' => ['required', 'exists:visa_types,id'],
        ]);

        $user = $request->user();

        // Deactivate old active pathways
        $user->pathway()->update(['status' => 'inactive']);

        // Clear old timeline steps (from previous pathway)
        $user->timelineSteps()->delete();

        $pathway = $user->pathway()->create([
            'country_id' => $validated['country_id'],
            'visa_type_id' => $validated['visa_type_id'],
            'status' => 'active',
            'readiness_score' => 0,
        ]);

        // Calculate initial readiness based on profile match
        $visa = VisaType::find($validated['visa_type_id']);
        $score = $this->recommendationService->calculateMatchScore($user->profile, $visa);
        $pathway->update(['readiness_score' => $score]);

        // Auto-populate timeline steps from admin-defined templates
        $templates = TimelineStepTemplate::where('visa_type_id', $validated['visa_type_id'])
            ->orderBy('order')
            ->get();

        foreach ($templates as $template) {
            $pathway->timelineSteps()->create([
                'user_id' => $user->id,
                'title' => $template->title,
                'description' => $template->description,
                'order' => $template->order,
                'status' => 'pending',
            ]);
        }

        // Create a welcome notification
        $countryName = Country::find($validated['country_id'])?->name ?? 'your destination';
        Notification::create([
            'user_id' => $user->id,
            'title' => "Your roadmap for {$countryName} is ready!",
            'message' => "We've generated " . $templates->count() . " steps to guide you through the " . ($visa->name ?? 'visa') . " application process.",
        ]);

        $pathway->load(['country', 'visaType']);

        return response()->json([
            'data' => [
                'id' => $pathway->id,
                'status' => $pathway->status,
                'readiness_score' => $pathway->readiness_score,
                'country' => $pathway->country?->only(['id', 'name', 'code', 'image_url']),
                'visa_type' => $pathway->visaType ? [
                    'id' => $pathway->visaType->id,
                    'name' => $pathway->visaType->name,
                    'type' => $pathway->visaType->pathway_type,
                    'cost_templates' => $pathway->visaType->costTemplates,
                ] : null,
            ]
        ], 201);
    }

    /**
     * Update savings goals for the active pathway.
     */
    public function updateSavings(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'current_savings' => ['required', 'numeric', 'min:0'],
            'monthly_target' => ['required', 'numeric', 'min:0'],
            'target_date' => ['nullable', 'date', 'after:today'],
        ]);

        $pathway = $request->user()->pathway()->where('status', 'active')->first();
        
        if (!$pathway) {
            return response()->json(['message' => 'No active pathway found.'], 404);
        }
        
        $pathway->update($validated);

        return response()->json([
            'data' => $pathway,
            'projection' => $this->savingsService->getProjection($pathway),
        ]);
    }

    /**
     * Deactivate the current pathway and clear timeline.
     */
    public function deactivate(Request $request)
    {
        $user = $request->user();
        $user->pathway()->update(['status' => 'inactive']);
        $user->timelineSteps()->delete();

        return response()->json(['message' => 'Pathway deactivated successfully.']);
    }
}