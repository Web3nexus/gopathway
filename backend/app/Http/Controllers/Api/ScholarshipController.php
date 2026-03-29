<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScholarshipController extends Controller
{
    /**
     * Display a listing of approved scholarships (Public).
     */
    public function index(Request $request)
    {
        $query = Scholarship::approved()->with('country');

        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->has('program_level')) {
            $query->where('program_level', $request->program_level);
        }

        if ($request->has('funding_type')) {
            $query->where('funding_type', $request->funding_type);
        }

        // Limit for guests
        if (!auth('sanctum')->check()) {
            $allApproved = $query->latest()->get();
            
            // Try to get unique countries first
            $scholarships = $allApproved->unique('country_id')->take(6);
            
            // If we have fewer than 6 unique countries, fill with others
            if ($scholarships->count() < 6) {
                $scholarships = $allApproved->take(6);
            }

            return response()->json([
                'data' => $scholarships->values(),
                'is_limited' => true,
                'total_count' => Scholarship::approved()->count()
            ]);
        }

        $results = $query->latest()->paginate(20);
        
        return response()->json(array_merge($results->toArray(), [
            'is_limited' => false
        ]));
    }

    /**
     * Display a listing of all scholarships for Admin.
     */
    public function adminIndex(Request $request)
    {
        $query = Scholarship::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return $query->with(['country', 'source'])->latest()->paginate(50);
    }

    /**
     * Update scholarship status (Approve/Reject) or Edit.
     */
    public function update(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'provider' => 'string|max:255',
            'country_id' => 'nullable|exists:countries,id',
            'status' => ['string', Rule::in(['pending', 'approved', 'rejected'])],
            'deadline' => 'nullable|date',
            'funding_type' => 'nullable|string',
            'program_level' => 'nullable|string',
        ]);

        $scholarship->update($validated);

        return response()->json([
            'message' => 'Scholarship updated successfully.',
            'data' => $scholarship
        ]);
    }

    /**
     * Remove a scholarship.
     */
    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();

        return response()->json(['message' => 'Scholarship deleted successfully.']);
    }

    /**
     * Get statistics for admin dashboard.
     */
    public function stats()
    {
        return response()->json([
            'total' => Scholarship::count(),
            'pending' => Scholarship::pending()->count(),
            'approved' => Scholarship::approved()->count(),
            'rejected' => Scholarship::where('status', 'rejected')->count(),
        ]);
    }
}
