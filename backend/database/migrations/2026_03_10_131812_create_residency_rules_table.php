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
        Schema::create('residency_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->json('temporary_reqs')->nullable(); // permits, validity, documents
            $table->json('permanent_reqs')->nullable(); // years, income, language
            $table->json('citizenship_reqs')->nullable(); // years, language, tests
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residency_rules');
    }
};