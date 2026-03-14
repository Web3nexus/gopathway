<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {
            $table->string('payout_reference')->nullable()->after('status');
            $table->string('payout_id')->nullable()->after('payout_reference');
            $table->decimal('payout_amount', 15, 2)->nullable()->after('payout_id');
            $table->string('payout_currency')->nullable()->after('payout_amount');
            $table->text('payout_error')->nullable()->after('payout_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {
            $table->dropColumn(['payout_reference', 'payout_id', 'payout_amount', 'payout_currency', 'payout_error']);
        });
    }
};
