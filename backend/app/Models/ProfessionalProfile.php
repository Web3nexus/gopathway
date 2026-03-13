<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalProfile extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'bio',
        'specialization',
        'languages',
        'years_of_experience',
        'hourly_rate',
        'currency',
        'is_verified',
        'is_available',
    ];

    protected $casts = [
        'specialization' => 'array',
        'languages' => 'array',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
