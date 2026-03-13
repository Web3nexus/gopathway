<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'reference',
        'status',
        'plan_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
