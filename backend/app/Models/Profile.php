<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'age',
        'education_level',
        'work_experience_years',
        'funds_range',
        'ielts_status',
        'preferred_country_id',
        'preferred_country_id',
        'travel_history',
        'occupation_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preferredCountry()
    {
        return $this->belongsTo(Country::class, 'preferred_country_id');
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }
}
