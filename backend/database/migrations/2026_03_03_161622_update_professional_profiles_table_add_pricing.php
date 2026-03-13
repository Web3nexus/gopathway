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
        Schema::table('professional_profiles', function (Blueprint $table) {
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('years_of_experience');
            $table->string('currency', 3)->default('USD')->after('hourly_rate');
            $table->boolean('is_available')->default(true)->after('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professional_profiles', function (Blueprint $table) {
            $table->dropColumn(['hourly_rate', 'currency', 'is_available']);
        });
    }
};
