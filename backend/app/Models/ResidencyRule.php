<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidencyRule extends Model
{
    protected $fillable = [
        'country_id',
        'temporary_reqs',
        'permanent_reqs',
        'citizenship_reqs',
    ];

    protected $casts = [
        'temporary_reqs' => 'array',
        'permanent_reqs' => 'array',
        'citizenship_reqs' => 'array',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}