<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCv extends Model
{
    protected $fillable = [
        'user_id',
        'country_id',
        'cv_template_id',
        'cv_data',
        'file_path',
    ];

    protected $casts = [
        'cv_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function template()
    {
        return $this->belongsTo(CvTemplate::class , 'cv_template_id');
    }
}