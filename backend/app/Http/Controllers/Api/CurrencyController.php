<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Get the current exchange rates and supported list
     */
    public function rates(CurrencyService $currencyService): JsonResponse
    {
        return response()->json([
            'supported' => $currencyService->getSupportedList(),
            'rates' => $currencyService->getExchangeRates(),
            'base' => 'USD'
        ]);
    }

    /**
     * Update the logged-in user's currency preference
     */
    public function updatePreference(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'currency' => ['required', 'string', 'size:3']
        ]);

        $currency = strtoupper($validated['currency']);
        $supported = (new CurrencyService())->getSupportedList();

        if (!isset($supported[$currency])) {
            return response()->json(['message' => 'Unsupported currency code.'], 400);
        }

        $user = $request->user();
        $user->update(['currency' => $currency]);

        return response()->json([
            'message' => 'Currency preference updated successfully.',
            'currency' => $currency
        ]);
    }
}
