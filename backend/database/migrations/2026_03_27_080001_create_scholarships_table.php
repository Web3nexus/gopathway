<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('scholarship_source_id')->nullable()->constrained()->nullOnDelete();
            $blueprint->string('title');
            $blueprint->string('provider');
            $blueprint->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $blueprint->string('region')->nullable();
            $blueprint->text('eligibility')->nullable();
            $blueprint->string('program_level')->nullable(); // undergraduate, masters, phd
            $blueprint->string('funding_type')->nullable(); // full, partial
            $blueprint->date('deadline')->nullable();
            $blueprint->string('application_link');
            $blueprint->text('description')->nullable();
            $blueprint->string('source_url')->unique();
            $blueprint->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $blueprint->timestamp('last_checked_at')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
