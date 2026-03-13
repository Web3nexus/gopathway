<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralCommission extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'amount',
        'status',
        'payment_id',
    ];

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class , 'referrer_id');
    }

    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class , 'referred_id');
    }
}