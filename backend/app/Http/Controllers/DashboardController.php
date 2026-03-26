<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $user = $request->user()->load(['profile.preferredCountry', 'notifications']);

        $profile = $user->profile;

        // Calculate profile completeness (%) - Optimized list
        $profileFields = ['age', 'education_level', 'work_experience_years', 'funds_range', 'ielts_status', 'preferred_country_id'];
        $filledCount = 0;
        if ($profile) {
            foreach ($profileFields as $field) {
                if (!is_null($profile->$field)) $filledCount++;
            }
        }
        $completeness = round(($filledCount / count($profileFields)) * 100);

        // Active pathway - Eager load with only needed fields
        $pathway = $user->activePathway; // using the relationship if defined, otherwise keep query
        if (!$pathway) {
            $pathway = $user->pathway()->with(['country', 'visaType'])->where('status', 'active')->latest()->first();
        }

        // Next uncompleted timeline step
        $nextStep = $pathway ? $pathway->timelineSteps()
            ->where('status', 'pending')
            ->orderBy('order')
            ->first() : null;

        // Unread notifications count
        $unreadCount = $user->notifications()->where('is_read', false)->count();

        // Recent notifications (5)
        $recentNotifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        // Cache platform features to avoid repeated DB hits
        $platformFeatures = \Illuminate\Support\Facades\Cache::remember('platform_features', 3600, function() {
            return \App\Models\PlatformFeature::all()->keyBy('feature_key')->map(fn($f) => [
                'is_active' => (bool)$f->is_active,
                'is_premium' => (bool)$f->is_premium
            ]);
        });

        return response()->json([
            'user' => $user,
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
            'platform_features' => $platformFeatures,
            'total_costs' => $pathway ? $pathway->costItems()->sum('amount') : 0,
            'is_premium' => $user->isPremium(),
            'labels' => [
                'risk_level' => \App\Helpers\SettingHelper::get('label_pathway_risk_level', 'Risk Level'),
                'progress' => \App\Helpers\SettingHelper::get('label_pathway_progress', 'Progress'),
                'roadmap' => \App\Helpers\SettingHelper::get('label_pathway_roadmap', 'Your Roadmap'),
            ],
        ]);
    }
}