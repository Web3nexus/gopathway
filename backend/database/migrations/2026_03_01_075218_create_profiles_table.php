<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('age')->nullable();
            $table->string('education_level')->nullable();
            $table->integer('work_experience_years')->nullable();
            $table->string('funds_range')->nullable();
            $table->string('ielts_status')->nullable();
            $table->unsignedBigInteger('preferred_country_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
