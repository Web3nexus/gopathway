<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationRequest;
use App\Models\ProfessionalProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    public function index()
    {
        return VerificationRequest::with(['user.professionalProfile'])
            ->latest()
            ->get()
            ->map(function ($request) {
                return [
                    'id' => $request->id,
                    'user' => $request->user,
                    'status' => $request->status,
                    'document_url' => Storage::url($request->document_path),
                    'admin_notes' => $request->admin_notes,
                    'created_at' => $request->created_at,
                ];
            });
    }

    public function review(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $verification = VerificationRequest::findOrFail($id);

        $verification->update([
            'status' => $request->status,
            'admin_notes' => $request->notes,
            'reviewed_at' => now(),
        ]);

        if ($request->status === 'approved') {
            $verification->user->professionalProfile->update(['is_verified' => true]);

            // Assign role based on profile type
            $type = $verification->user->professionalProfile->type; // 'lawyer' or 'translator'
            if (in_array($type, ['lawyer', 'translator'])) {
                $verification->user->syncRoles([$type]);
            }
        } else {
            $verification->user->professionalProfile->update(['is_verified' => false]);
            // Optional: Remove roles if rejected? 
            // Usually we just leave them as 'user' if they were never approved.
        }

        return response()->json([
            'message' => 'Verification ' . $request->status . ' successfully.',
            'verification' => $verification
        ]);
    }
}
