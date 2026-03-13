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
        Schema::table('pathways', function (Blueprint $table) {
            $table->decimal('current_savings', 15, 2)->default(0)->after('status');
            $table->decimal('monthly_target', 15, 2)->default(0)->after('current_savings');
            $table->date('target_date')->nullable()->after('monthly_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pathways', function (Blueprint $table) {
            $table->dropColumn(['current_savings', 'monthly_target', 'target_date']);
        });
    }
};
