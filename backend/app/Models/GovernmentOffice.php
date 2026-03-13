<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GovernmentOffice extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'service_type',
        'website',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
