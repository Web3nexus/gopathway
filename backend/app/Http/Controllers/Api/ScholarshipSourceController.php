<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipSource;
use App\Jobs\FetchScholarshipsJob;
use Illuminate\Http\Request;

class ScholarshipSourceController extends Controller
{
    public function index()
    {
        return ScholarshipSource::latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_url' => 'required|url',
            'crawl_type' => 'required|in:scholarship,school',
            'scraping_rules' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $source = ScholarshipSource::create($validated);

        return response()->json($source, 201);
    }

    public function update(Request $request, ScholarshipSource $source)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'base_url' => 'url',
            'crawl_type' => 'in:scholarship,school',
            'scraping_rules' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $source->update($validated);

        return response()->json($source);
    }

    public function destroy(ScholarshipSource $source)
    {
        $source->delete();
        return response()->json(null, 204);
    }

    /**
     * Manually trigger a crawl for a source.
     */
    public function crawl(ScholarshipSource $source)
    {
        FetchScholarshipsJob::dispatch($source);

        return response()->json(['message' => 'Crawl job dispatched successfully.']);
    }
}
