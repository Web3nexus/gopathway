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
        $users = \Illuminate\Support\Facades\DB::table('users')->get();

        foreach ($users as $user) {
            $pathway = \Illuminate\Support\Facades\DB::table('pathways')
                ->where('user_id', $user->id)
                ->latest()
                ->first();
            
            if ($pathway) {
                \Illuminate\Support\Facades\DB::table('pathways')
                    ->where('id', $pathway->id)
                    ->update([
                        'current_savings' => $user->current_savings ?? 0,
                        'monthly_target' => $user->monthly_savings_target ?? 0,
                        'status' => 'active',
                    ]);
            }
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
