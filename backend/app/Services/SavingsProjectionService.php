<?php

namespace App\Services;

use App\Models\Pathway;
use Carbon\Carbon;

class SavingsProjectionService
{
    /**
     * Get savings projection for a pathway.
     */
    public function getProjection(Pathway $pathway): array
    {
        // 1. Sum items specifically attached to this pathway instance
        $directCosts = $pathway->costItems()->sum('amount');

        // 2. Sum relevant template costs (Global + Country specific + Visa specific)
        $templateCosts = \App\Models\CostItem::whereNull('pathway_id')
            ->where(function ($query) use ($pathway) {
                $query->where(function ($q) {
                    $q->whereNull('country_id')->whereNull('visa_type_id'); // Global
                })->orWhere(function ($q) use ($pathway) {
                    $q->where('country_id', $pathway->country_id)->whereNull('visa_type_id'); // Country specific
                })->orWhere(function ($q) use ($pathway) {
                    $q->where('visa_type_id', $pathway->visa_type_id); // Visa specific
                });
            })->sum('amount');

        $totalCost = $directCosts + $templateCosts;
        $currentSavings = $pathway->current_savings;
        $monthlyTarget = $pathway->monthly_target;
        $targetDate = $pathway->target_date ? Carbon::parse($pathway->target_date) : null;

        $gap = max(0, $totalCost - $currentSavings);
        
        $monthsToReady = $monthlyTarget > 0 ? ceil($gap / $monthlyTarget) : null;
        $projectedReadyDate = $monthsToReady !== null ? Carbon::now()->addMonths($monthsToReady) : null;

        $status = 'on-track';
        $message = '';

        if ($targetDate && $projectedReadyDate) {
            if ($projectedReadyDate->gt($targetDate)) {
                $status = 'behind';
                $diff = $targetDate->diffInMonths($projectedReadyDate);
                $message = "You are currently projecting to be ready {$diff} month(s) after your target date.";
            } else {
                $status = 'ahead';
                $diff = $projectedReadyDate->diffInMonths($targetDate);
                $message = "You are on track to be ready {$diff} month(s) before your target date.";
            }
        }

        return [
            'total_cost' => (float) $totalCost,
            'current_savings' => (float) $currentSavings,
            'gap' => (float) $gap,
            'months_to_ready' => $monthsToReady,
            'projected_ready_date' => $projectedReadyDate ? $projectedReadyDate->toDateString() : null,
            'status' => $status,
            'message' => $message,
            'percentage' => $totalCost > 0 ? round(($currentSavings / $totalCost) * 100, 2) : 0,
        ];
    }
}
