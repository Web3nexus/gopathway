<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActionLog extends Model
{
    protected $fillable = [
        'user_id',
        'pathway_id',
        'action_type',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
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
