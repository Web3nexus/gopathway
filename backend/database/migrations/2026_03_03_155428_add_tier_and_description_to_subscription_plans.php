<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->string('tier')->default('free')->after('slug');
            $table->text('description')->nullable()->after('features');
        });

        // Update existing plans with tier groupings
        DB::table('subscription_plans')->where('slug', 'free')->update(['tier' => 'free', 'description' => 'Get started with basic tools']);
        DB::table('subscription_plans')->where('slug', 'premium-monthly')->update(['tier' => 'premium', 'description' => 'Full access to all relocation tools']);
        DB::table('subscription_plans')->where('slug', 'premium-yearly')->update(['tier' => 'premium', 'description' => 'Full access to all relocation tools']);
        DB::table('subscription_plans')->where('slug', 'global-explorer-annual')->update(['tier' => 'premium', 'description' => 'Full access to all relocation tools']);

        // Deactivate the duplicate annual plan so we have 3 tiers
        DB::table('subscription_plans')->where('slug', 'global-explorer-annual')->update(['is_active' => false]);
    }

    public function down(): void
    {
        DB::table('subscription_plans')->where('slug', 'global-explorer-annual')->update(['is_active' => true]);

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn(['tier', 'description']);
        });
    }
};
