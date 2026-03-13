<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentVisaRequirement extends Model
{
    protected $fillable = [
        'country_id', 'visa_name', 'visa_fee', 'visa_fee_currency',
        'processing_time', 'financial_proof_required', 'min_funds_required',
        'min_funds_currency', 'min_funds_description', 'work_hours_per_week',
        'post_study_work_permit', 'post_study_work_duration',
        'required_documents', 'notes',
    ];

    protected $casts = [
        'financial_proof_required' => 'boolean',
        'post_study_work_permit' => 'boolean',
        'required_documents' => 'array',
        'visa_fee' => 'float',
        'min_funds_required' => 'float',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}