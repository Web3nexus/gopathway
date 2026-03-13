<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TimelineStepTemplateRequest;
use App\Models\TimelineStepTemplate;
use Illuminate\Http\JsonResponse;

class TimelineStepTemplateController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => TimelineStepTemplate::with('visaType.country')->orderBy('order')->get()
        ]);
    }

    public function store(TimelineStepTemplateRequest $request): JsonResponse
    {
        $template = TimelineStepTemplate::create($request->validated());
        return response()->json(['data' => $template], 201);
    }

    public function update(TimelineStepTemplateRequest $request, TimelineStepTemplate $timelineStepTemplate): JsonResponse
    {
        $timelineStepTemplate->update($request->validated());
        return response()->json(['data' => $timelineStepTemplate]);
    }

    public function destroy(TimelineStepTemplate $timelineStepTemplate): JsonResponse
    {
        $timelineStepTemplate->delete();
        return response()->json(null, 204);
    }
}
