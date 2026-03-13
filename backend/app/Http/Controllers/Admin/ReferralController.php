<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ReferralCommission;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $referrers = User::whereHas('referrals')
            ->orWhereHas('commissions')
            ->withCount('referrals')
            ->withSum('commissions', 'amount')
            ->latest()
            ->paginate(20);

        return response()->json($referrers);
    }

    public function updateRate(Request $request, User $user)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $user->update([
            'commission_rate' => $request->commission_rate,
        ]);

        return response()->json([
            'message' => 'Commission rate updated successfully.',
            'user' => $user
        ]);
    }

    public function commissions(Request $request)
    {
        $commissions = ReferralCommission::with(['referrer:id,name,email,payout_method,payout_details', 'referredUser:id,name'])
            ->latest()
            ->paginate(30);

        return response()->json($commissions);
    }

    public function markAsPaid(Request $request, ReferralCommission $commission)
    {
        $commission->update(['status' => 'paid']);

        return response()->json(['message' => 'Commission marked as paid.']);
    }
}