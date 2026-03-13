<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'tier',
        'price',
        'prices',
        'currency',
        'interval',
        'features',
        'description',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'prices' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}