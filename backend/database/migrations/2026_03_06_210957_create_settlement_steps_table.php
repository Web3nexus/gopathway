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
        Schema::create('settlement_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->foreignId('visa_type_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('phase', ['week1', 'month1', 'long_term']);
            $table->string('title');
            $table->text('description');
            $table->text('required_documents')->nullable();
            $table->string('official_link')->nullable();
            $table->string('estimated_time')->nullable();
            $table->boolean('mandatory')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlement_steps');
    }
};
