<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    /**
     * Return all timeline steps for the user's active pathway.
     */
    public function index(Request $request)
    {
        $activePathway = $request->user()->pathway()
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$activePathway) {
            return response()->json(['data' => []]);
        }

        $steps = $activePathway->timelineSteps()
            ->orderBy('order')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'title' => $s->title,
                'description' => $s->description,
                'status' => $s->status,
                'order' => $s->order,
                'completed_at' => $s->completed_at,
            ]);

        return response()->json(['data' => $steps]);
    }

    /**
     * Mark a timeline step as complete.
     */
    public function complete(Request $request, $stepId)
    {
        $step = $request->user()
            ->timelineSteps()
            ->findOrFail($stepId);

        $step->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Create a notification for the completed step
        Notification::create([
            'user_id' => $request->user()->id,
            'title' => "Step completed: {$step->title}",
            'message' => 'Great progress! Keep going to complete your relocation roadmap.',
        ]);

        return response()->json([
            'data' => [
                'id' => $step->id,
                'status' => $step->status,
                'completed_at' => $step->completed_at,
            ]
        ]);
    }
}
