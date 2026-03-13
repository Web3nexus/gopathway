<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineStepTemplate extends Model
{
    protected $fillable = ['visa_type_id', 'title', 'description', 'order'];

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }
}
