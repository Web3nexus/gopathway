<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'country_id', 'name', 'location', 'type', 'ranking',
        'website', 'application_portal', 'description', 'logo_url', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(SchoolProgram::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(UserSchoolApplication::class);
    }
}