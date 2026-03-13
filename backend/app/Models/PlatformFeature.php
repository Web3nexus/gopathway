<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformFeature extends Model
{
    protected $fillable = [
        'feature_key',
        'feature_name',
        'description',
        'is_active',
        'is_premium',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
    ];
}