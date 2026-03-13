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
        Schema::table('user_timeline_steps', function (Blueprint $table) {
            $table->foreignId('pathway_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
        });

        // Cleanup: Link existing steps to the user's active pathway if possible, or delete them
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $activePathway = $user->pathway()->where('status', 'active')->latest()->first();
            if ($activePathway) {
                \DB::table('user_timeline_steps')
                    ->where('user_id', $user->id)
                    ->whereNull('pathway_id')
                    ->update(['pathway_id' => $activePathway->id]);
            } else {
                \DB::table('user_timeline_steps')
                    ->where('user_id', $user->id)
                    ->whereNull('pathway_id')
                    ->delete();
            }
        }
        
        // Final cleanup for safety: remove any remaining duplicates if multiple sets were created for the same pathway
        $duplicates = \DB::table('user_timeline_steps')
            ->select('user_id', 'pathway_id', 'title', \DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'pathway_id', 'title')
            ->having(\DB::raw('COUNT(*)'), '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            \DB::table('user_timeline_steps')
                ->where('user_id', $dup->user_id)
                ->where('pathway_id', $dup->pathway_id)
                ->where('title', $dup->title)
                ->where('id', '!=', $dup->keep_id)
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_timeline_steps', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pathway_id');
        });
    }
};
