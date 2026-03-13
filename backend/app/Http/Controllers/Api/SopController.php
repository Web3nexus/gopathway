<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SopDraft;
use App\Models\Pathway;
use App\Services\SopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SopController extends Controller
{
    public function __construct(private SopService $service)
    {
    }

    /**
     * Start/Get current SOP draft for active pathway.
     */
    public function start(Request $request): JsonResponse
    {
        $user = $request->user();
        $pathway = $user->pathway()->where('status', 'active')->latest()->first();

        if (!$pathway) {
            return response()->json(['message' => 'No active pathway found.'], 404);
        }

        $draft = SopDraft::firstOrCreate([
            'user_id' => $user->id,
            'visa_type_id' => $pathway->visa_type_id,
        ]);

        return response()->json(['data' => $draft]);
    }

    /**
     * Save answers for a draft.
     */
    public function save(Request $request, SopDraft $sopDraft): JsonResponse
    {
        $this->authorizeDraft($request, $sopDraft);

        $validated = $request->validate([
            'answers' => ['required', 'array'],
        ]);

        $sopDraft->update([
            'answers' => array_merge($sopDraft->answers ?? [], $validated['answers']),
            'status' => 'drafting',
        ]);

        return response()->json(['data' => $sopDraft]);
    }

    /**
     * Generate the SOP text.
     */
    public function generate(Request $request, SopDraft $sopDraft): JsonResponse
    {
        $this->authorizeDraft($request, $sopDraft);

        $text = $this->service->generate($sopDraft);

        $sopDraft->update([
            'generated_text' => $text,
            'status' => 'generated',
        ]);

        return response()->json(['data' => $sopDraft]);
    }

    /**
     * Submit an SOP draft for AI review.
     */
    public function review(Request $request, \App\Services\SopAiReviewService $aiReviewService): JsonResponse
    {
        $validated = $request->validate([
            'draft' => ['required', 'string'],
            'country' => ['required', 'string'],
            'visa_type' => ['required', 'string'],
        ]);

        $result = $aiReviewService->reviewDraft(
            $validated['country'],
            $validated['visa_type'],
            $validated['draft']
        );

        if (!$result['success']) {
            return response()->json(['message' => $result['error']], 500);
        }

        return response()->json(['data' => $result['data']]);
    }

    private function authorizeDraft(Request $request, SopDraft $draft)
    {
        if ($draft->user_id !== $request->user()->id) {
            abort(403);
        }
    }
}
