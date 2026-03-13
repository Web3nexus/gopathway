<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $user = $request->user()->load(['profile.preferredCountry', 'pathway', 'notifications']);

        $profile = $user->profile;

        // Calculate profile completeness (%)
        $profileFields = ['age', 'education_level', 'work_experience_years', 'funds_range', 'ielts_status', 'preferred_country_id'];
        $filled = collect($profileFields)->filter(fn($f) => !is_null($profile?->$f))->count();
        $completeness = round(($filled / count($profileFields)) * 100);

        // Active pathway
        $pathway = $user->pathway()->with(['country', 'visaType'])->where('status', 'active')->latest()->first();

        // Next uncompleted timeline step
        $nextStep = $pathway ? $pathway->timelineSteps()
            ->where('status', 'pending')
            ->orderBy('order')
            ->first() : null;

        // Unread notifications
        $unreadCount = $user->notifications()->where('is_read', false)->count();

        // Recent notifications (5)
        $recentNotifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'profile_completeness' => $completeness,
            'pathway' => $pathway ? [
                'id' => $pathway->id,
                'status' => $pathway->status,
                'readiness_score' => $pathway->readiness_score,
                'country' => $pathway->country?->only(['id', 'name', 'code', 'image_url']),
                'visa_type' => $pathway->visaType?->only(['id', 'name']),
            ] : null,
            'next_step' => $nextStep,
            'unread_notifications' => $unreadCount,
            'recent_notifications' => $recentNotifications,
            'documents_uploaded' => $user->documents()->count(),
            'platform_features' => \App\Models\PlatformFeature::all()->keyBy('feature_key')->map(fn($f) => [
                'is_active' => (bool)$f->is_active,
                'is_premium' => (bool)$f->is_premium
            ]),
            'total_costs' => $pathway ? $pathway->costItems()->sum('amount') : 0,
            'is_premium' => $user->isPremium(),
            'labels' => [
                'risk_level' => \App\Models\Setting::where('key', 'label_pathway_risk_level')->value('value') ?? 'Risk Level',
                'progress' => \App\Models\Setting::where('key', 'label_pathway_progress')->value('value') ?? 'Progress',
                'roadmap' => \App\Models\Setting::where('key', 'label_pathway_roadmap')->value('value') ?? 'Your Roadmap',
            ],
        ]);
    }
}