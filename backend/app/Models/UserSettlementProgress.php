<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettlementProgress extends Model
{
    protected $table = 'user_settlement_progress';

    protected $fillable = [
        'user_id',
        'settlement_step_id',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function settlementStep(): BelongsTo
    {
        return $this->belongsTo(SettlementStep::class);
    }
}
