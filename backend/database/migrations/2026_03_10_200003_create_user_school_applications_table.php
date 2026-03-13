<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_school_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_program_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('status', [
                'researching',
                'preparing_documents',
                'applied',
                'offer_received',
                'deposit_paid',
                'visa_applied',
                'accepted',
                'rejected',
            ])->default('researching');
            $table->date('applied_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_school_applications');
    }
};