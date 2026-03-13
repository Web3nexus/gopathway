<?php

namespace App\Services;

use App\Models\User;
use App\Models\ReferralCommission;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    /**
     * Calculate and record commission for a referral.
     */
    public function recordPayment(User $referredUser, float $amount, string $paymentId)
    {
        $referrerId = $referredUser->referred_by_id;

        if (!$referrerId) {
            return null;
        }

        $referrer = User::find($referrerId);

        if (!$referrer) {
            return null;
        }

        $commissionAmount = ($amount * $referrer->commission_rate) / 100;

        $commission = ReferralCommission::create([
            'referrer_id' => $referrer->id,
            'referred_id' => $referredUser->id,
            'amount' => $commissionAmount,
            'status' => 'pending',
            'payment_id' => $paymentId,
        ]);

        Log::info('Referral commission recorded', [
            'referrer_id' => $referrer->id,
            'referred_id' => $referredUser->id,
            'amount' => $commissionAmount,
            'payment_id' => $paymentId
        ]);

        return $commission;
    }
}