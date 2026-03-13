<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettlementStep extends Model
{
    protected $fillable = [
        'country_id',
        'visa_type_id',
        'phase',
        'title',
        'description',
        'required_documents',
        'official_link',
        'estimated_time',
        'mandatory',
        'order',
    ];

    protected $casts = [
        'mandatory' => 'boolean',
        'order' => 'integer',
        'required_documents' => 'array',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function visaType(): BelongsTo
    {
        return $this->belongsTo(VisaType::class);
    }
}
