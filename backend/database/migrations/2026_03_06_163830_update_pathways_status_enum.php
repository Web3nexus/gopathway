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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pathways MODIFY COLUMN status ENUM('planning', 'in_progress', 'submitted', 'approved', 'rejected', 'active', 'inactive') NOT NULL DEFAULT 'planning'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pathways MODIFY COLUMN status ENUM('planning', 'in_progress', 'submitted', 'approved', 'rejected') NOT NULL DEFAULT 'planning'");
    }
};
