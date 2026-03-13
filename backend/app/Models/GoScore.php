<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoScore extends Model
{
    protected $fillable = [
        'user_id',
        'profile_score',
        'funds_score',
        'language_score',
        'documents_score',
        'timeline_score',
        'total',
        'breakdown',
        'calculated_at',
    ];

    protected $casts = [
        'breakdown' => 'array',
        'calculated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
