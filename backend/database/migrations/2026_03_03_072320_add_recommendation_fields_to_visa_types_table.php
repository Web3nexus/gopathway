<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('visa_types', function (Blueprint $table) {
            $table->string('min_education_level')->nullable()->after('description');
            $table->integer('min_work_experience_years')->default(0)->after('min_education_level');
            $table->decimal('min_ielts_score', 3, 1)->nullable()->after('min_work_experience_years');
            $table->decimal('min_funds_required', 15, 2)->nullable()->after('min_ielts_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visa_types', function (Blueprint $table) {
            $table->dropColumn(['min_education_level', 'min_work_experience_years', 'min_ielts_score', 'min_funds_required']);
        });
    }
};
