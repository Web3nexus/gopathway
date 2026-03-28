<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ScholarshipSource;
use App\Services\ScraperService;
use App\Services\ScholarshipParserService;

$source1 = ScholarshipSource::where('crawl_type', 'scholarship')->first();
$source2 = ScholarshipSource::where('crawl_type', 'school')->first();

$scraper = app(ScraperService::class);
$parser = app(ScholarshipParserService::class);

echo "Testing Source 1: " . ($source1 ? $source1->name : 'None') . "\n";
if ($source1) {
    $html = $scraper->fetchHtml($source1->base_url);
    if ($html) {
        $data = $parser->parse($html, $source1->scraping_rules);
        echo "Parsed " . count($data) . " items.\n";
        if (count($data) > 0) {
            print_r($data[0]);
        }
    } else {
        echo "Failed to fetch HTML\n";
    }
}

echo "\nTesting Source 2: " . ($source2 ? $source2->name : 'None') . "\n";
if ($source2) {
    $html = $scraper->fetchHtml($source2->base_url);
    if ($html) {
        $data = $parser->parse($html, $source2->scraping_rules);
        echo "Parsed " . count($data) . " items.\n";
        if (count($data) > 0) {
            print_r($data[0]);
        }
    } else {
        echo "Failed to fetch HTML\n";
    }
}
