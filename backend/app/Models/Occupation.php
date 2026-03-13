<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $fillable = ['name', 'category', 'base_demand_score'];

    /**
     * Get the countries demanding this occupation, with their specific demand multiplier.
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_occupation_demands')
            ->withPivot('demand_multiplier')
            ->withTimestamps();
    }
}
