<?php

namespace App\Services;

use App\Models\User;
use App\Models\ReferralCommission;
use App\Services\FlutterwaveService;
use Illuminate\Support\Facades\Log;
use Exception;

class PayoutService
{
    protected FlutterwaveService $flutterwave;

    public function __construct(FlutterwaveService $flutterwave)
    {
        $this->flutterwave = $flutterwave;
    }

    /**
     * Process automated payout for a commission.
     */
    public function processCommissionPayout(ReferralCommission $commission)
    {
        if ($commission->status !== 'pending') {
            throw new Exception("Commission is not in pending status.");
        }

        $referrer = $commission->referrer;
        if (!$referrer || !$referrer->payout_details) {
            throw new Exception("Referrer payout details missing.");
        }

        $details = $referrer->payout_details;
        $country = $details['country'] ?? 'NG';
        $currency = ($country === 'NG') ? 'NGN' : 'USD';
        
        $amountInUsd = $commission->amount;
        $payoutAmount = $amountInUsd;

        // If paying in NGN, we need to convert
        if ($currency === 'NGN') {
            $fxRateData = $this->flutterwave->getFxRate('USD', 'NGN', $amountInUsd);
            if ($fxRateData && isset($fxRateData['to'][0]['rate'])) {
                $rate = $fxRateData['to'][0]['rate'];
                $payoutAmount = $amountInUsd * $rate;
            } else {
                // Fallback to internal rate if Flutterwave fails
                $rate = (new CurrencyService())->getRateFor('NGN');
                $payoutAmount = $amountInUsd * $rate;
                Log::warning("Flutterwave FX rate failed, using fallback for commission payout.", ['commission_id' => $commission->id]);
            }
        }

        try {
            $transferData = [
                'account_bank' => $details['bank_code'] ?? '',
                'account_number' => $details['account_number'],
                'amount' => $payoutAmount,
                'currency' => $currency,
                'narration' => "GoPathway Affiliate Payout - Commission #{$commission->id}",
                'reference' => 'payout_' . $commission->id . '_' . time(),
                'callback_url' => config('app.url') . '/api/v1/webhooks/flutterwave/transfer',
                'debit_currency' => 'USD' // Assuming the balance is in USD
            ];

            $response = $this->flutterwave->initiateTransfer($transferData);

            $commission->update([
                'status' => 'processing',
                'payout_reference' => $response['reference'],
                'payout_id' => $response['id'],
                'payout_amount' => $payoutAmount,
                'payout_currency' => $currency
            ]);

            Log::info("Payout initiated for commission #{$commission->id}", ['response' => $response]);

            return $response;
        } catch (Exception $e) {
            Log::error("Payout failed for commission #{$commission->id}: " . $e->getMessage());
            throw $e;
        }
    }
}
