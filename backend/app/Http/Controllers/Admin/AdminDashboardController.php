<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\Pathway;
use App\Models\UserDocument;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'total_users' => User::count(),
            'active_countries' => Country::count(),
            'total_pathways' => Pathway::count(),
            'pending_documents' => UserDocument::where('status', 'uploaded')->count(),
        ]);
    }
}
