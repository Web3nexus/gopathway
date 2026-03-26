<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    protected string $secretKey;
    protected string $encryptionKey;
    protected string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $this->secretKey = \App\Helpers\SettingHelper::get('flutterwave_secret_key') ?: env('FLUTTERWAVE_SECRET_KEY', '');
        $this->encryptionKey = \App\Helpers\SettingHelper::get('flutterwave_encryption_key') ?: env('FLUTTERWAVE_ENCRYPTION_KEY', '');
    }

    /**
     * Initialize a payment (Hosted Checkout).
     */
    public function initializeTransaction(array $data)
    {
        try {
            $response = Http::withToken($this->secretKey)->post($this->baseUrl . '/payments', [
                'amount' => (float)$data['amount'],
                'currency' => $data['currency'],
                'tx_ref' => $data['tx_ref'],
                'redirect_url' => $data['redirect_url'],
                'customer' => [
                    'email' => $data['customer']['email'],
                    'name' => $data['customer']['name'] ?? 'Customer',
                ],
                'customization' => [
                    'title' => 'GoPathway Subscription',
                    'description' => 'Payment for subscription plan',
                ],
                'meta' => $data['meta'] ?? [],
            ]);

            if ($response->successful()) {
                return $response->json()['data'];
            }

            Log::error('Flutterwave V3 Initialization Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'data' => $data
            ]);

            return [
                'status' => 'error',
                'message' => 'Flutterwave Error: ' . ($response->json('message') ?? 'Unknown error')
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave V3 Init Exception: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Internal server error'];
        }
    }

    /**
     * Verify a transaction.
     */
    public function verifyTransaction(string $transactionId)
    {
        try {
            $response = Http::withToken($this->secretKey)->get($this->baseUrl . "/transactions/{$transactionId}/verify");

            if ($response->successful()) {
                return $response->json('data');
            }

            Log::error('Flutterwave V3 Verification Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'id' => $transactionId
            ]);
        } catch (\Exception $e) {
            Log::error('Flutterwave V3 Verify Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get real-time FX rate.
     */
    public function getFxRate(string $from, string $to, float $amount = 1)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/transfers/rates", [
                    'from' => $from,
                    'to' => $to,
                    'amount' => $amount
                ]);

            if ($response->successful()) {
                return $response->json()['data'];
            }
        } catch (\Exception $e) {
            Log::error('Flutterwave V3 FX Rate Fetch Failed: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Get list of banks for a country.
     */
    public function getBanks(string $countryCode)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/banks/{$countryCode}");

            if ($response->successful()) {
                return $response->json()['data'];
            }
        } catch (\Exception $e) {
            Log::error('Flutterwave V3 Bank Fetch Failed: ' . $e->getMessage());
        }
        return [];
    }

    /**
     * Initiate a transfer/payout.
     */
    public function initiateTransfer(array $data)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/transfers", $data);

            if ($response->successful()) {
                return $response->json()['data'];
            }

            Log::error('Flutterwave V3 Transfer Failed', ['data' => $data, 'response' => $response->json()]);
            throw new \Exception($response->json()['message'] ?? 'Flutterwave transfer failed');
        } catch (\Exception $e) {
            Log::error('Flutterwave V3 Transfer Exception: ' . $e->getMessage());
            throw $e;
        }
    }
}
