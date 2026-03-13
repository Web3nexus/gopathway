<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    /**
     * Fallback minimal hardcoded rates just in case the API goes down.
     * Base currency is always USD.
     */
    protected array $fallbackRates = [
        'USD' => 1.0,
        'EUR' => 0.92,
        'GBP' => 0.81,
        'NGN' => 1500.0,
        'CAD' => 1.35,
        'AUD' => 1.50,
        'INR' => 83.0,
        'ZAR' => 19.0,
        'GHS' => 14.0,
        'KES' => 135.0,
    ];

    /**
     * Cache duration for exchange rates (12 hours).
     */
    public const CACHE_TTL = 43200;

    /**
     * Supported curreny mapping for UI.
     */
    public const SUPPORTED_CURRENCIES = [
        'USD' => ['label' => 'US Dollar', 'symbol' => '$'],
        'EUR' => ['label' => 'Euro', 'symbol' => '€'],
        'GBP' => ['label' => 'British Pound', 'symbol' => '£'],
        'NGN' => ['label' => 'Nigerian Naira', 'symbol' => '₦'],
        'CAD' => ['label' => 'Canadian Dollar', 'symbol' => 'C$'],
        'AUD' => ['label' => 'Australian Dollar', 'symbol' => 'A$'],
        'INR' => ['label' => 'Indian Rupee', 'symbol' => '₹'],
        'ZAR' => ['label' => 'South African Rand', 'symbol' => 'R'],
        'GHS' => ['label' => 'Ghanaian Cedi', 'symbol' => 'GH₵'],
        'KES' => ['label' => 'Kenyan Shilling', 'symbol' => 'KSh'],
    ];

    /**
     * Country Code to Currency Code mapping.
     */
    public const COUNTRY_CURRENCY_MAP = [
        'US' => 'USD',
        'GB' => 'GBP',
        'CA' => 'CAD',
        'AU' => 'AUD',
        'DE' => 'EUR',
        'ES' => 'EUR',
        'FR' => 'EUR',
        'IT' => 'EUR',
        'IE' => 'EUR',
        'NL' => 'EUR',
        'AT' => 'EUR',
        'FI' => 'EUR',
        'NG' => 'NGN',
        'GH' => 'GHS',
        'KE' => 'KES',
        'IN' => 'INR',
        'ZA' => 'ZAR',
        'NZ' => 'NZD',
        'SE' => 'SEK',
        'NO' => 'NOK',
    ];

    /**
     * Fetch standard exchange rates keyed against USD.
     */
    public function getExchangeRates(): array
    {
        return Cache::remember('currency_exchange_rates', self::CACHE_TTL, function () {
            try {
                // You can replace this with any real provider like ExchangeRate-API, Fixer, OpenExchangeRates
                $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/USD');

                if ($response->successful() && isset($response->json()['rates'])) {
                    $rates = $response->json()['rates'];
                    // Ensure our fallback currencies exist
                    foreach ($this->fallbackRates as $currency => $rate) {
                        if (!isset($rates[$currency])) {
                            $rates[$currency] = $rate;
                        }
                    }
                    return $rates;
                }

                Log::warning("Currency API returned unexpected response", ['status' => $response->status()]);
            }
            catch (Exception $e) {
                Log::error("Failed to fetch live exchange rates: " . $e->getMessage());
            }

            // Return hardcoded fallbacks if API call completely failed
            return $this->fallbackRates;
        });
    }

    /**
     * Get a specific multiplier for a target currency (from USD Base).
     */
    public function getRateFor(string $currencyCode): float
    {
        $code = strtoupper($currencyCode);
        if ($code === 'USD')
            return 1.0;

        $rates = $this->getExchangeRates();

        return $rates[$code] ?? 1.0;
    }

    /**
     * Return list of supported currencies with their formatting rules.
     */
    public function getSupportedList(): array
    {
        return self::SUPPORTED_CURRENCIES;
    }

    /**
     * Get the currency code for a given country.
     */
    public function getCurrencyForCountry($country): string
    {
        if (!$country)
            return 'USD';

        $code = is_string($country) ? strtoupper($country) : strtoupper($country->code);

        return self::COUNTRY_CURRENCY_MAP[$code] ?? 'USD';
    }
}