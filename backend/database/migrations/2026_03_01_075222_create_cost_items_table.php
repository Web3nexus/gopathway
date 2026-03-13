<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cost_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pathway_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->boolean('is_mandatory')->default(true);
            $table->string('currency')->default('USD');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_items');
    }
};
