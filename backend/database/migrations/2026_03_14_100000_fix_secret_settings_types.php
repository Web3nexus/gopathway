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
        DB::table('settings')->where('key', 'turnstile_secret_key')->update([
            'type' => 'encrypted_string'
        ]);
        
        // Also ensure paystack and flutterwave are correct if for some reason they aren't
        DB::table('settings')->whereIn('key', ['paystack_secret_key', 'flutterwave_secret_key'])->update([
            'type' => 'encrypted_string'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'turnstile_secret_key',
            'paystack_secret_key',
            'flutterwave_secret_key'
        ])->update([
            'type' => 'string'
        ]);
    }
};
