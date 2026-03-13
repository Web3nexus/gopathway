<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pathway extends Model
{
    protected $fillable = [
        'user_id',
        'country_id',
        'visa_type_id',
        'status',
        'readiness_score',
        'current_savings',
        'monthly_target',
        'target_date',
    ];

    protected $casts = [
        'readiness_score' => 'integer',
        'current_savings' => 'decimal:2',
        'monthly_target' => 'decimal:2',
        'target_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }

    public function costItems()
    {
        return $this->hasMany(CostItem::class);
    }

    public function timelineSteps()
    {
        return $this->hasMany(UserTimelineStep::class);
    }
}
