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
        $dbKey = Setting::where('key', 'flutterwave_secret_key')->value('value');
        $this->secretKey = $dbKey ?: (config('services.flutterwave.secret_key') ?? env('FLUTTERWAVE_SECRET_KEY', ''));
        
        $dbEncKey = Setting::where('key', 'flutterwave_encryption_key')->value('value');
        $this->encryptionKey = $dbEncKey ?: env('FLUTTERWAVE_ENCRYPTION_KEY', '');
    }

    /**
     * Initialize a transaction.
     */
    public function initializeTransaction(array $data)
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/payments", $data);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Flutterwave Initialization Failed', ['response' => $response->json()]);
        throw new \Exception($response->json()['message'] ?? 'Flutterwave initialization failed');
    }

    /**
     * Verify a transaction.
     */
    public function verifyTransaction(string $transactionId)
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Flutterwave Verification Failed', ['transaction_id' => $transactionId, 'response' => $response->json()]);
        return null;
    }

    /**
     * Get real-time FX rate.
     */
    public function getFxRate(string $from, string $to, float $amount = 1)
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/transfers/rates", [
                'from' => $from,
                'to' => $to,
                'amount' => $amount
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Flutterwave FX Rate Fetch Failed', ['from' => $from, 'to' => $to, 'response' => $response->json()]);
        return null;
    }

    /**
     * Get list of banks for a country.
     */
    public function getBanks(string $countryCode)
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/banks/{$countryCode}");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Flutterwave Bank Fetch Failed', ['country' => $countryCode, 'response' => $response->json()]);
        return [];
    }

    /**
     * Initiate a transfer/payout.
     */
    public function initiateTransfer(array $data)
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/transfers", $data);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Flutterwave Transfer Failed', ['data' => $data, 'response' => $response->json()]);
        throw new \Exception($response->json()['message'] ?? 'Flutterwave transfer failed');
    }
}
