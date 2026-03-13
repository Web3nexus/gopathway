<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostItem extends Model
{
    protected $fillable = [
        'pathway_id',
        'country_id',
        'visa_type_id',
        'name',
        'amount',
        'description',
        'is_mandatory',
        'currency'
    ];

    public function pathway()
    {
        return $this->belongsTo(Pathway::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }
}
