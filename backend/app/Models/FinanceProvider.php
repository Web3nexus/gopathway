<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceProvider extends Model
{
    protected $fillable = [
        'name',
        'provider_type',
        'supported_countries',
        'supported_pathways',
        'website',
        'contact_email',
        'description',
        'logo_url',
        'rating',
        'is_active',
    ];

    protected $casts = [
        'supported_countries' => 'array',
        'supported_pathways' => 'array',
        'is_active' => 'boolean',
        'rating' => 'float',
    ];
}