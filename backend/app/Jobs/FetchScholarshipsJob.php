<?php

namespace App\Jobs;

use App\Models\ScholarshipSource;
use App\Services\ScholarshipFetcherService;
use App\Services\SchoolFetcherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchScholarshipsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $source;

    /**
     * Create a new job instance.
     */
    public function __construct(ScholarshipSource $source)
    {
        $this->source = $source;
    }

    /**
     * Execute the job.
     */
    public function handle(ScholarshipFetcherService $scholarshipFetcher, SchoolFetcherService $schoolFetcher): void
    {
        Log::info("FetchScholarshipsJob: Started for source: {$this->source->name} (Type: {$this->source->crawl_type})");
        
        try {
            if ($this->source->crawl_type === 'school') {
                $schoolFetcher->processSource($this->source);
            } else {
                $scholarshipFetcher->processSource($this->source);
            }
            Log::info("FetchScholarshipsJob: Completed successfully for source: {$this->source->name}");
        } catch (\Exception $e) {
            Log::error("FetchScholarshipsJob: Failed for source: {$this->source->name}. Error: {$e->getMessage()}");
            throw $e;
        }
    }
}
