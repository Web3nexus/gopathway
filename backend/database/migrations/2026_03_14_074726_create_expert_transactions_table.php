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
        Schema::create('expert_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('The user making the payment');
            $table->foreignId('expert_id')->constrained('users')->onDelete('cascade')->comment('The expert receiving the payment');
            $table->decimal('amount', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('gateway')->nullable();
            $table->string('reference')->unique()->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expert_transactions');
    }
};
