<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    protected string $secretKey;
    protected string $baseUrl = 'https://api.flutterwave.com/v3';

    public function __construct()
    {
        $dbKey = Setting::where('key', 'flutterwave_secret_key')->value('value');
        $this->secretKey = $dbKey ?: (config('services.flutterwave.secret_key') ?? env('FLUTTERWAVE_SECRET_KEY', ''));
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
}
