<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Identify and Merge Duplicates
        $duplicates = DB::table('document_types')
            ->select('name', 'visa_type_id', DB::raw('COUNT(*) as count'))
            ->groupBy('name', 'visa_type_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $ids = DB::table('document_types')
                ->where('name', $duplicate->name)
                ->where('visa_type_id', $duplicate->visa_type_id)
                ->orderBy('id')
                ->pluck('id');

            $keepId = $ids->shift(); // Keep the first one

            // Update associated user_documents
            DB::table('user_documents')
                ->whereIn('document_type_id', $ids)
                ->update(['document_type_id' => $keepId]);

            // Delete duplicates
            DB::table('document_types')->whereIn('id', $ids)->delete();
        }

        // 2. Add Unique Constraint
        Schema::table('document_types', function (Blueprint $table) {
            $table->unique(['name', 'visa_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
