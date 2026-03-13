<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('travel_history', 20)->nullable()->after('preferred_country_id');
            // Values: none, domestic_only, 1_2_countries, 3_5_countries, 5_plus_countries
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('travel_history');
        });
    }
};
