<?php

namespace App\Services;

use App\Models\School;
use App\Models\SchoolProgram;
use App\Models\ScholarshipSource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SchoolFetcherService
{
    protected $scraper;
    protected $parser;

    public function __construct(ScraperService $scraper, ScholarshipParserService $parser)
    {
        $this->scraper = $scraper;
        $this->parser = $parser;
    }

    /**
     * Process a school source.
     */
    public function processSource(ScholarshipSource $source): void
    {
        Log::info("SchoolFetcherService: Processing source: {$source->name} ({$source->base_url})");

        $html = $this->scraper->fetchHtml($source->base_url);
        if (!$html) return;

        $rules = $source->scraping_rules;
        $extractedData = $this->parser->parse($html, $rules);

        if (empty($extractedData)) {
            $extractedData = $this->parser->parseWithAi($html, 'school');
        }

        foreach ($extractedData as $data) {
            $this->saveSchool($data, $source);
        }
    }

    /**
     * Save extracted school and program data.
     */
    protected function saveSchool(array $data, ScholarshipSource $source): void
    {
        $name = $data['school_name'] ?? null;
        if (!$name) return;

        $school = School::where('name', $name)->first();

        if (!$school) {
            $school = School::create([
                'name' => $name,
                'country_id' => $data['country_id'] ?? null,
                'location' => $data['location'] ?? $data['region'] ?? null,
                'website' => $data['official_website_link'] ?? null,
                'description' => $data['description'] ?? "Extracted from {$source->name}",
                'is_active' => false, // All fetched data pending approval
            ]);
        }

        // Handle programs if extraction includes them
        if (!empty($data['programs'])) {
            foreach ($data['programs'] as $progData) {
                SchoolProgram::updateOrCreate(
                    ['school_id' => $school->id, 'name' => $progData['name']],
                    [
                        'degree_type' => $progData['degree_type'] ?? null,
                        'field_of_study' => $progData['course'] ?? null,
                        'tuition_per_year' => $progData['tuition_fees'] ?? null,
                        'admission_requirements' => $progData['admission_requirements'] ?? null,
                        'is_active' => false,
                    ]
                );
            }
        }
    }
}
