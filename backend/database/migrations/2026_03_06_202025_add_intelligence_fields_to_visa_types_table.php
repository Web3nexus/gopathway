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
        Schema::table('visa_types', function (Blueprint $table) {
            $table->string('pathway_type')->nullable()->after('name'); // e.g. Study, Skilled Work, Digital Nomad
            $table->boolean('pr_possibility')->default(false)->after('processing_time');
            $table->string('official_source_link')->nullable()->after('pr_possibility');
            $table->timestamp('last_verified_at')->nullable()->after('official_source_link');
            $table->json('restrictions')->nullable()->after('requirements');
            $table->json('benefits')->nullable()->after('restrictions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visa_types', function (Blueprint $table) {
            $table->dropColumn([
                'pathway_type', 
                'pr_possibility', 
                'official_source_link', 
                'last_verified_at', 
                'restrictions', 
                'benefits'
            ]);
        });
    }
};
