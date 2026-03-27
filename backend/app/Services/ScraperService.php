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
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
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
