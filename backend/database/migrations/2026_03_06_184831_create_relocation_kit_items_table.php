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
        Schema::create('relocation_kit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relocation_kit_id')->constrained('relocation_kits')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content')->nullable(); // Markdown or HTML content
            $table->boolean('is_premium')->default(false); // Can lock specific items in a free kit
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relocation_kit_items');
    }
};
