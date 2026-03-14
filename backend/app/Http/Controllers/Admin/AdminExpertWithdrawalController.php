<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpertWithdrawal;
use Illuminate\Http\Request;

class AdminExpertWithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = ExpertWithdrawal::with('expert:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $withdrawals]);
    }

    public function review(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,processed',
            'admin_notes' => 'nullable|string',
        ]);

        $withdrawal = ExpertWithdrawal::findOrFail($id);
        
        // If it's already processed or rejected, we shouldn't change it back unless we have a specific flow,
        // but for now we'll allow admin override if needed, or we can restrict it.
        if (in_array($withdrawal->status, ['rejected', 'processed']) && $request->status !== $withdrawal->status) {
             // Optional logic: Refund expert balance if rejected after being pending?
             // Since we already deducted from available balance when they requested, if admin rejects:
             // We should add it back to their balance?
             // Actually, the available balance is calculated dynamically based on total earnings minus ALL withdrawals (except rejected).
             // If status changes to rejected, it automatically frees up the balance in the expertStats calculation.
        }

        $withdrawal->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return response()->json(['message' => 'Withdrawal status updated successfully', 'data' => $withdrawal]);
    }
}
