<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaType extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'pathway_type',
        'description',
        'requirements',
        'restrictions',
        'benefits',
        'processing_time',
        'pr_possibility',
        'official_source_link',
        'last_verified_at',
        'is_active',
        'min_education_level',
        'min_work_experience_years',
        'min_ielts_score',
        'min_funds_required',
    ];

    protected $casts = [
        'requirements' => 'array',
        'restrictions' => 'array',
        'benefits' => 'array',
        'is_active' => 'boolean',
        'pr_possibility' => 'boolean',
        'last_verified_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function pathways()
    {
        return $this->hasMany(Pathway::class);
    }

    public function documentTypes()
    {
        return $this->hasMany(DocumentType::class);
    }

    public function costTemplates()
    {
        return $this->hasMany(CostTemplate::class);
    }
}