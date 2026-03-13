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
        Schema::create('cost_templates', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('visa_type_id')->constrained()->onDelete('cascade');
            $blueprint->string('category'); // e.g., Government Fees, Travel, Housing
            $blueprint->string('item'); // e.g., Visa Application, Flights
            $blueprint->decimal('min_cost', 15, 2);
            $blueprint->decimal('max_cost', 15, 2);
            $blueprint->string('currency')->default('USD');
            $blueprint->text('notes')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_templates');
    }
};