<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SubscriptionPlan; // Added this line to resolve potential 'SubscriptionPlan not found' error if it's not already globally imported or aliased.

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'paystack_id',
        'paystack_code',
        'status',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class , 'subscription_plan_id');
    }

    public function extend()
    {
        $plan = $this->plan;
        if (!$plan)
            return;

        $interval = $plan->interval ?? 'month';
        $currentEndsAt = $this->ends_at ?? now();

        // If it already expired, start from now
        $baseDate = $currentEndsAt->isPast() ? now() : $currentEndsAt;

        if ($interval === 'year') {
            $this->ends_at = $baseDate->addYear();
        }
        else {
            $this->ends_at = $baseDate->addMonth();
        }

        $this->status = 'active';
        $this->save();
    }
}