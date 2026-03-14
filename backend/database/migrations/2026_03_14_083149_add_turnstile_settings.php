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
                'key' => 'turnstile_site_key',
                'value' => '',
                'type' => 'string',
                'label' => 'Cloudflare Turnstile Site Key',
                'description' => 'Your Cloudflare Turnstile Site Key for frontend forms.',
                'group' => 'Security',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'turnstile_secret_key',
                'value' => '',
                'type' => 'string',
                'label' => 'Cloudflare Turnstile Secret Key',
                'description' => 'Your Cloudflare Turnstile Secret Key for backend validation.',
                'group' => 'Security',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'turnstile_site_key',
            'turnstile_secret_key',
        ])->delete();
    }
};
