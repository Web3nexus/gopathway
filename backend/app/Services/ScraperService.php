<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScraperService
{
    /**
     * Fetch HTML content from a URL.
     */
    public function fetchHtml(string $url): ?string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'GoPathwayBot/1.0 (+https://gopathway.com)',
            ])->timeout(30)->get($url);

            if ($response->successful()) {
                return $response->body();
            }

            Log::warning("ScraperService: Failed to fetch URL: {$url}. Status: " . $response->status());
        } catch (\Exception $e) {
            Log::error("ScraperService: Error fetching URL: {$url}. Message: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Basic rate limiting (sleep between requests).
     */
    public function throttle(int $seconds = 2): void
    {
        sleep($seconds);
    }
}
