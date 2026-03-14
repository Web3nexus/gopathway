<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insertOrIgnore([
            [
                'key' => 'google_analytics_id',
                'value' => '',
                'type' => 'string',
                'label' => 'Google Analytics Measurement ID',
                'description' => 'Your GA4 Measurement ID (e.g., G-XXXXXXXXXX). Leave empty to disable tracking.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('key', 'google_analytics_id')->delete();
    }
};
