<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserResidencyTracking extends Model
{
    protected $fillable = [
        'user_id',
        'country_id',
        'arrival_date',
        'permit_expiry',
        'language_progress',
        'tax_filing',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'permit_expiry' => 'date',
        'language_progress' => 'array',
        'tax_filing' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}