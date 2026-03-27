<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scholarship extends Model
{
    protected $fillable = [
        'scholarship_source_id',
        'title',
        'provider',
        'country_id',
        'region',
        'eligibility',
        'program_level',
        'funding_type',
        'deadline',
        'application_link',
        'description',
        'source_url',
        'status',
        'last_checked_at',
        'opening_date',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
        'deadline' => 'date',
        'opening_date' => 'date',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(ScholarshipSource::class, 'scholarship_source_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
