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
        'admission_opening_date', 'admission_deadline_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'admission_opening_date' => 'date',
        'admission_deadline_date' => 'date',
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

    public function trackers()
    {
        return $this->belongsToMany(User::class, 'user_school_tracks', 'school_id', 'user_id')->withTimestamps();
    }
}