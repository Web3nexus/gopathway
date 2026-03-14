<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'expert_id',
        'amount',
        'commission_amount',
        'net_amount',
        'currency',
        'gateway',
        'reference',
        'status',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
}
