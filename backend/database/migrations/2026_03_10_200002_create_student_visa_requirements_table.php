<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_visa_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('visa_name'); // e.g. "Tier 4 Student Visa"
            $table->decimal('visa_fee', 10, 2)->nullable();
            $table->string('visa_fee_currency', 3)->default('USD');
            $table->string('processing_time')->nullable(); // e.g. "3-8 weeks"
            $table->boolean('financial_proof_required')->default(true);
            $table->decimal('min_funds_required', 12, 2)->nullable();
            $table->string('min_funds_currency', 3)->default('USD');
            $table->string('min_funds_description')->nullable(); // e.g. "per year of study"
            $table->integer('work_hours_per_week')->nullable(); // e.g. 20
            $table->boolean('post_study_work_permit')->default(false);
            $table->string('post_study_work_duration')->nullable(); // e.g. "2 years"
            $table->json('required_documents')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_visa_requirements');
    }
};