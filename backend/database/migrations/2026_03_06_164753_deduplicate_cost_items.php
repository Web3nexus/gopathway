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
        $duplicates = \Illuminate\Support\Facades\DB::table('cost_items')
            ->select('name', 'country_id', 'visa_type_id', \Illuminate\Support\Facades\DB::raw('MIN(id) as min_id'))
            ->groupBy('name', 'country_id', 'visa_type_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            \Illuminate\Support\Facades\DB::table('cost_items')
                ->where('name', $duplicate->name)
                ->where('country_id', $duplicate->country_id)
                ->where('visa_type_id', $duplicate->visa_type_id)
                ->where('id', '>', $duplicate->min_id)
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
