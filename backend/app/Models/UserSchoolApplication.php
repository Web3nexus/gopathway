<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSchoolApplication extends Model
{
    protected $fillable = [
        'user_id', 'school_id', 'school_program_id',
        'status', 'applied_at', 'notes',
    ];

    protected $casts = [
        'applied_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(SchoolProgram::class , 'school_program_id');
    }
}