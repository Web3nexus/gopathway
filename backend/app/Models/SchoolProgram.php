<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolProgram extends Model
{
    protected $fillable = [
        'school_id', 'name', 'degree_type', 'field_of_study',
        'duration_years', 'tuition_per_year', 'currency',
        'application_deadline', 'intake_periods',
        'min_gpa', 'ielts_min', 'toefl_min', 'pte_min',
        'admission_requirements', 'is_active',
    ];

    protected $casts = [
        'intake_periods' => 'array',
        'admission_requirements' => 'array',
        'is_active' => 'boolean',
        'tuition_per_year' => 'float',
        'duration_years' => 'float',
        'min_gpa' => 'float',
        'ielts_min' => 'float',
        'toefl_min' => 'float',
        'pte_min' => 'float',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}