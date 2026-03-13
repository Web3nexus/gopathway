<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop simple old field
            $table->dropColumn('payout_account');
            // Add structured JSON field for all bank/paypal details
            $table->json('payout_details')->nullable()->after('payout_method');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('payout_details');
            $table->string('payout_account')->nullable()->after('payout_method');
        });
    }
};