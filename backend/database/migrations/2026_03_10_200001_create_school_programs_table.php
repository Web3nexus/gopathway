<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('degree_type', ['certificate', 'diploma', 'bachelor', 'master', 'phd']);
            $table->string('field_of_study')->nullable();
            $table->decimal('duration_years', 3, 1)->default(4);
            $table->decimal('tuition_per_year', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('application_deadline')->nullable(); // e.g. "January 15"
            $table->json('intake_periods')->nullable(); // ["Fall", "Winter", "Spring"]
            $table->decimal('min_gpa', 3, 2)->nullable();
            $table->decimal('ielts_min', 3, 1)->nullable();
            $table->decimal('toefl_min', 5, 1)->nullable();
            $table->decimal('pte_min', 5, 1)->nullable();
            $table->json('admission_requirements')->nullable(); // Array of requirement strings
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_programs');
    }
};