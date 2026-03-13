<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'code', 'currency_code', 'currency_symbol', 'description', 'image_url', 'competitiveness_score', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function visaTypes()
    {
        return $this->hasMany(VisaType::class);
    }

    public function pathways()
    {
        return $this->hasMany(Pathway::class);
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class , 'preferred_country_id');
    }

    public function score()
    {
        return $this->hasOne(CountryScore::class);
    }

    public function relocationKits()
    {
        return $this->hasMany(RelocationKit::class);
    }

    public function settlementSteps()
    {
        return $this->hasMany(SettlementStep::class);
    }
}