<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('go_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // Dimension scores (each out of 100)
            $table->unsignedTinyInteger('profile_score')->default(0);    // Profile completeness
            $table->unsignedTinyInteger('funds_score')->default(0);      // Financial readiness
            $table->unsignedTinyInteger('language_score')->default(0);   // Language readiness (IELTS etc.)
            $table->unsignedTinyInteger('documents_score')->default(0);  // Document readiness
            $table->unsignedTinyInteger('timeline_score')->default(0);   // Timeline progress

            // Composite total (weighted average, out of 100)
            $table->unsignedTinyInteger('total')->default(0);

            // Meta
            $table->json('breakdown')->nullable(); // Stores detailed sub-scores and tips
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('go_scores');
    }
};
