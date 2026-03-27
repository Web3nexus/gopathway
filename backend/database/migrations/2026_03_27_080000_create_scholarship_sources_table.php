<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_sources', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->string('base_url');
            $blueprint->enum('crawl_type', ['scholarship', 'school'])->default('scholarship');
            $blueprint->json('scraping_rules')->nullable();
            $blueprint->boolean('is_active')->default(true);
            $blueprint->timestamp('last_run_at')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_sources');
    }
};
