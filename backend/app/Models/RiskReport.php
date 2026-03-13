<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskReport extends Model
{
    protected $fillable = [
        'user_id',
        'pathway_id',
        'risk_level',
        'risk_score',
        'funds_risk',
        'language_risk',
        'age_risk',
        'experience_risk',
        'documents_risk',
        'travel_history_risk',
        'weak_areas',
        'full_report',
        'calculated_at',
    ];

    protected $casts = [
        'weak_areas' => 'array',
        'full_report' => 'array',
        'calculated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pathway()
    {
        return $this->belongsTo(Pathway::class);
    }
}
