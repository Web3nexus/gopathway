<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTimelineStep extends Model
{
    protected $fillable = [
        'user_id',
        'pathway_id',
        'title',
        'description',
        'status',
        'order',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
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
