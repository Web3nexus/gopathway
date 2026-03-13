<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class PaystackService
{
    protected string $secretKey;
    protected string $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $dbKey = Setting::where('key', 'paystack_secret_key')->value('value');
        $this->secretKey = $dbKey ?: (config('services.paystack.secret_key') ?? env('PAYSTACK_SECRET_KEY', ''));
    }

    /**
     * Initialize a transaction.
     */
    public function initializeTransaction(array $data)
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/transaction/initialize", $data);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Paystack Initialization Failed', ['response' => $response->json()]);
        throw new \Exception($response->json()['message'] ?? 'Paystack initialization failed');
    }

    /**
     * Verify a transaction.
     */
    public function verifyTransaction(string $reference)
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transaction/verify/{$reference}");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        Log::error('Paystack Verification Failed', ['reference' => $reference, 'response' => $response->json()]);
        return null;
    }

    /**
     * Create a customer on Paystack.
     */
    public function createCustomer(array $data)
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/customer", $data);

        return $response->successful() ? $response->json()['data'] : null;
    }
}
