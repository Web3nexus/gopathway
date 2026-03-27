<?php

namespace App\Services;

use App\Models\Scholarship;
use App\Models\ScholarshipSource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ScholarshipFetcherService
{
    protected $scraper;
    protected $parser;

    public function __construct(ScraperService $scraper, ScholarshipParserService $parser)
    {
        $this->scraper = $scraper;
        $this->parser = $parser;
    }

    /**
     * Process a scholarship source.
     */
    public function processSource(ScholarshipSource $source): void
    {
        Log::info("ScholarshipFetcherService: Processing source: {$source->name} ({$source->base_url})");

        $html = $this->scraper->fetchHtml($source->base_url);
        if (!$html) {
            Log::error("ScholarshipFetcherService: Could not fetch HTML for source: {$source->name}");
            return;
        }

        $rules = $source->scraping_rules;
        $extractedData = $this->parser->parse($html, $rules);

        if (empty($extractedData) && !empty($rules)) {
            Log::info("ScholarshipFetcherService: Rule-based parsing failed, attempting AI parsing.");
            $extractedData = $this->parser->parseWithAi($html, $source->crawl_type);
        }

        foreach ($extractedData as $data) {
            $this->saveScholarship($data, $source);
        }

        $source->update(['last_run_at' => now()]);
    }

    /**
     * Save extracted scholarship data.
     */
    protected function saveScholarship(array $data, ScholarshipSource $source): void
    {
        // Deduplication logic: title + provider + deadline OR source_url
        $sourceUrl = $data['source_url'] ?? null;
        if (!$sourceUrl) {
            // Generate a source URL if missing, or use a hash
            $sourceUrl = $source->base_url . '#' . Str::slug($data['title'] ?? 'unknown');
        }

        $scholarship = Scholarship::where('source_url', $sourceUrl)->first();

        if ($scholarship) {
            // Update existing entry if needed
            Log::info("ScholarshipFetcherService: Scholarship already exists: {$data['title']}");
            return;
        }

        Scholarship::create([
            'scholarship_source_id' => $source->id,
            'title' => $data['title'] ?? 'Unknown Scholarship',
            'provider' => $data['provider'] ?? 'Unknown Provider',
            'country_id' => $data['country_id'] ?? null,
            'region' => $data['region'] ?? null,
            'eligibility' => $data['eligibility'] ?? null,
            'program_level' => $data['program_level'] ?? null,
            'funding_type' => $data['funding_type'] ?? null,
            'deadline' => isset($data['deadline']) ? date('Y-m-d', strtotime($data['deadline'])) : null,
            'application_link' => $data['application_link'] ?? $sourceUrl,
            'description' => $data['description'] ?? null,
            'source_url' => $sourceUrl,
            'status' => 'pending',
            'last_checked_at' => now(),
        ]);

        Log::info("ScholarshipFetcherService: Created new pending scholarship: " . ($data['title'] ?? 'Unknown'));
    }
}
