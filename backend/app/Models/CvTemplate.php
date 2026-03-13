<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvTemplate extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'format_rules',
        'structure_json',
    ];

    protected $casts = [
        'format_rules' => 'array',
        'structure_json' => 'array',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}