<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('risk_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pathway_id')->constrained()->cascadeOnDelete();

            // Overall risk
            $table->string('risk_level', 10)->default('medium'); // low, medium, high
            $table->unsignedTinyInteger('risk_score')->default(0); // 0–100 (0 = no risk)

            // Dimension risk scores (each 0–100, higher = riskier)
            $table->unsignedTinyInteger('funds_risk')->default(0);
            $table->unsignedTinyInteger('language_risk')->default(0);
            $table->unsignedTinyInteger('age_risk')->default(0);
            $table->unsignedTinyInteger('experience_risk')->default(0);
            $table->unsignedTinyInteger('documents_risk')->default(0);
            $table->unsignedTinyInteger('travel_history_risk')->default(0);

            // Detailed data
            $table->json('weak_areas')->nullable();   // Top 3 weak areas with tips
            $table->json('full_report')->nullable();   // Full dimension breakdown

            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            // One report per user-pathway combination
            $table->unique(['user_id', 'pathway_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_reports');
    }
};
