<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;

class ScholarshipParserService
{
    /**
     * Parse scholarship data from HTML content using rules.
     */
    public function parse(string $html, array $rules): array
    {
        $crawler = new Crawler($html);
        $scholarships = [];

        // Assuming rules define how to find each scholarship item and its fields
        // e.g., ['item_selector' => '.scholarship-item', 'fields' => ['title' => '.title', ...]]
        
        $itemSelector = $rules['item_selector'] ?? null;
        if (!$itemSelector) {
            Log::warning("ScholarshipParserService: No item_selector provided in rules.");
            return [];
        }

        $crawler->filter($itemSelector)->each(function (Crawler $node) use (&$scholarships, $rules) {
            $data = [];
            foreach ($rules['fields'] as $field => $selector) {
                try {
                    $element = $node->filter($selector);
                    if (str_ends_with($field, 'url') || str_ends_with($field, 'link')) {
                        $data[$field] = $element->attr('href');
                        // if href is relative, it will need to be made absolute later
                    } else {
                        $data[$field] = $element->text();
                    }
                } catch (\Exception $e) {
                    $data[$field] = null;
                }
            }
            // Ensure at least one required field exists before pushing
            if (array_filter($data)) {
                $scholarships[] = $data;
            }
        });

        return $scholarships;
    }

    /**
     * Fallback to AI-based parsing for messy pages.
     */
    public function parseWithAi(string $html, string $context = 'scholarship'): array
    {
        // This is a placeholder for AI parsing using AiAssistantService
        // Real implementation would involve sending a truncated version of HTML to OpenAI
        Log::info("ScholarshipParserService: Attempting AI parsing for {$context}.");
        
        // Example: $aiService = app(AiAssistantService::class);
        // return $aiService->parseHtml($html, $context);
        
        return [];
    }
}
