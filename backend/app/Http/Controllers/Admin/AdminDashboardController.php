<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\VisaType;
use App\Models\UserDocument;
use App\Models\School;
use App\Models\Scholarship;
use App\Models\Subscription;
use App\Models\PaymentLog;
use App\Models\ProfessionalProfile;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        // Unattended support: Support conversations with unread messages from non-admins
        $unattendedSupport = Conversation::where('is_support', true)
            ->whereHas('messages', function($query) {
                $query->where('is_read', false)
                    ->whereHas('sender', function($s) {
                        $s->whereDoesntHave('roles', function($r) {
                            $r->where('name', 'admin');
                        });
                    });
            })->count();

        return response()->json([
            'total_users' => User::count(),
            'active_countries' => Country::count(),
            'total_pathways' => VisaType::count(),
            'pending_documents' => UserDocument::where('status', 'uploaded')->count(),
            'schools_count' => School::count(),
            'scholarships_count' => Scholarship::approved()->count(),
            'total_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_revenue' => PaymentLog::where('status', 'success')->sum('amount'),
            'experts_count' => ProfessionalProfile::where('is_verified', true)->count(),
            'unattended_support' => $unattendedSupport,
        ]);
    }
}
