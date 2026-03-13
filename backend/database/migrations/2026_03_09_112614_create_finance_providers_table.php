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
        Schema::create('finance_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider_type'); // Education Loan, Relocation Financing, etc.
            $table->string('supported_countries')->nullable(); // JSON or comma-separated
            $table->string('supported_pathways')->nullable(); // JSON or comma-separated
            $table->string('website');
            $table->string('contact_email')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_providers');
    }
};