<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FlutterwaveService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $encryptionKey;
    protected string $baseUrl = 'https://api.flutterwave.com/v4';
    protected string $idpUrl = 'https://idp.flutterwave.com/realms/flutterwave/protocol/openid-connect/token';

    public function __construct()
    {
        $this->clientId = Setting::where('key', 'flutterwave_public_key')->value('value') ?: env('FLUTTERWAVE_CLIENT_ID', '');
        $this->clientSecret = Setting::where('key', 'flutterwave_secret_key')->value('value') ?: env('FLUTTERWAVE_CLIENT_SECRET', '');
        $this->encryptionKey = Setting::where('key', 'flutterwave_encryption_key')->value('value') ?: env('FLUTTERWAVE_ENCRYPTION_KEY', '');
    }

    /**
     * Get OAuth Access Token for V4
     */
    protected function getAccessToken(): ?string
    {
        $cacheKey = 'flutterwave_v4_access_token';
        
        return Cache::remember($cacheKey, 3000, function () {
            try {
                $response = Http::asForm()->post($this->idpUrl, [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                Log::error('Flutterwave V4 Token Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            } catch (\Exception $e) {
                Log::error('Flutterwave V4 Token Exception: ' . $e->getMessage());
            }
            return null;
        });
    }

    /**
     * Initialize a transaction (Hosted Checkout).
     */
    public function initializeTransaction(array $data)
    {
        $token = $this->getAccessToken();
        if (!$token) throw new \Exception('Flutterwave Authentication Failed');

        try {
            $payload = [
                'amount' => (float)$data['amount'],
                'currency' => $data['currency'],
                'tx_ref' => $data['tx_ref'],
                'redirect_url' => $data['redirect_url'],
                'customer' => [
                    'email' => $data['customer']['email'],
                    'name' => $data['customer']['name'] ?? 'Customer',
                ],
                'customization' => [
                    'title' => 'GoPathway Payment',
                    'description' => 'Subscription Payment',
                ],
                'meta' => $data['meta'] ?? [],
            ];

            // Trying /payments on V4 as some docs suggest it's still the hosted checkout endpoint
            $response = Http::withToken($token)->post("{$this->baseUrl}/payments", $payload);

            if ($response->successful()) {
                return $response->json()['data'];
            }

            Log::error('Flutterwave V4 Initialization Failed', [
                'status' => $response->status(),
                'body' => $response->body(), // Raw body for better debugging
                'payload' => $payload
            ]);
            
            throw new \Exception($response->json('message') ?? 'Flutterwave initialization failed (Code: ' . $response->status() . ')');
        } catch (\Exception $e) {
            Log::error('Flutterwave V4 Init Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verify a transaction.
     */
    public function verifyTransaction(string $transactionId)
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        try {
            $response = Http::withToken($token)
                ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

            if ($response->successful()) {
                return $response->json()['data'];
            }

            Log::error('Flutterwave V4 Verification Failed', ['transaction_id' => $transactionId, 'response' => $response->json()]);
        } catch (\Exception $e) {
            Log::error('Flutterwave V4 Verify Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get real-time FX rate.
     */
    public function getFxRate(string $from, string $to, float $amount = 1)
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/transfers/rates", [
                    'from' => $from,
                    'to' => $to,
                    'amount' => $amount
                ]);

            if ($response->successful()) {
                return $response->json()['data'];
            }
        } catch (\Exception $e) {
            Log::error('Flutterwave V4 FX Rate Fetch Exception: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Get list of banks for a country.
     */
    public function getBanks(string $countryCode)
    {
        $token = $this->getAccessToken();
        if (!$token) return [];

        try {
            $response = Http::withToken($token)
                ->get("{$this->baseUrl}/banks/{$countryCode}");

            if ($response->successful()) {
                return $response->json()['data'];
            }
        } catch (\Exception $e) {
            Log::error('Flutterwave V4 Bank Fetch Exception: ' . $e->getMessage());
        }
        return [];
    }

    /**
     * Initiate a transfer/payout.
     */
    public function initiateTransfer(array $data)
    {
        $token = $this->getAccessToken();
        if (!$token) throw new \Exception('Flutterwave Authentication Failed');

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/transfers", $data);

            if ($response->successful()) {
                return $response->json()['data'];
            }

            Log::error('Flutterwave V4 Transfer Failed', ['data' => $data, 'response' => $response->json()]);
            throw new \Exception($response->json()['message'] ?? 'Flutterwave transfer failed');
        } catch (\Exception $e) {
            Log::error('Flutterwave V4 Transfer Exception: ' . $e->getMessage());
            throw $e;
        }
    }
}
