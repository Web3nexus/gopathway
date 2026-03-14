<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertWithdrawal extends Model
{
    protected $fillable = [
        'expert_id',
        'amount',
        'currency',
        'status',
        'payout_details',
        'admin_notes',
    ];

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
}
