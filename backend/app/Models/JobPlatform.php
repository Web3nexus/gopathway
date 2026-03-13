<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPlatform extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'website_url',
        'category',
        'tips',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}