<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ReferralController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'referral_code' => $user->referral_code,
            'referral_link' => $user->referral_link,
            'stats' => [
                'total_clicks' => (int)$user->referral_clicks,
                'total_referrals' => $user->referrals()->count(),
                'total_commissions' => $user->commissions()->sum('amount'),
                'pending_commissions' => $user->commissions()->where('status', 'pending')->sum('amount'),
                'paid_commissions' => $user->commissions()->where('status', 'paid')->sum('amount'),
            ],
            'commission_rate' => $user->commission_rate,
            'payout_method' => $user->payout_method,
            'payout_details' => $user->payout_details,
        ]);
    }

    public function updatePayout(Request $request)
    {
        $request->validate([
            'payout_method' => 'required|string|in:paypal,bank',
            'payout_details' => 'required|array',
            // PayPal
            'payout_details.email' => 'required_if:payout_method,paypal|nullable|email',
            // Bank – common fields
            'payout_details.account_name' => 'required_if:payout_method,bank|nullable|string|max:255',
            'payout_details.bank_name' => 'required_if:payout_method,bank|nullable|string|max:255',
            'payout_details.account_number' => 'required_if:payout_method,bank|nullable|string|max:50',
            'payout_details.country' => 'required_if:payout_method,bank|nullable|string|max:100',
            // International extras
            'payout_details.swift_bic' => 'nullable|string|max:20',
            'payout_details.iban' => 'nullable|string|max:50',
            'payout_details.routing_number' => 'nullable|string|max:20',
            'payout_details.sort_code' => 'nullable|string|max:20',
            'payout_details.bank_address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->update([
            'payout_method' => $request->payout_method,
            'payout_details' => $request->payout_details,
        ]);

        return response()->json(['message' => 'Payout details updated successfully']);
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $history = $user->commissions()
            ->with('referredUser:id,name')
            ->latest()
            ->paginate(15);

        return response()->json($history);
    }

    public function trackClick(Request $request, $code)
    {
        $user = User::where('referral_code', $code)->first();
        if ($user) {
            $user->increment('referral_clicks');
            return response()->json(['message' => 'Click tracked']);
        }
        return response()->json(['message' => 'Referral code not found'], 404);
    }
}