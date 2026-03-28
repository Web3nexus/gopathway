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

            Log::warning("ScraperService: Failed to fetch URL: {$url}. Status: " . $response->status() . ". Attempting cURL fallback.");
            
            $fallbackHtml = $this->fetchWithCurl($url);
            if ($fallbackHtml) {
                return $fallbackHtml;
            }
            
        } catch (\Exception $e) {
            Log::error("ScraperService: Error fetching URL: {$url}. Message: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Fallback to native PHP cURL which sometimes bypasses basic bot protection
     * that blocks Guzzle/Laravel HTTP client.
     */
    protected function fetchWithCurl(string $url): ?string
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            // Some protections look for specific cURL properties or headers.
            // But basic ones might just block Guzzle's signature.
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.5',
            ]);
            
            $html = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode >= 200 && $httpCode < 300 && $html) {
                return $html;
            }
            
            Log::warning("ScraperService: cURL fallback also failed for {$url}. Status: {$httpCode}");
        } catch (\Exception $e) {
            Log::error("ScraperService: cURL fallback exception for {$url}. Message: " . $e->getMessage());
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
