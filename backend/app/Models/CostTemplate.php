<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostTemplate extends Model
{
    protected $fillable = [
        'visa_type_id',
        'category',
        'item',
        'min_cost',
        'max_cost',
        'currency',
        'notes',
    ];

    protected $casts = [
        'min_cost' => 'decimal:2',
        'max_cost' => 'decimal:2',
    ];

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }
}