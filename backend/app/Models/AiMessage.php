<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiMessage extends Model
{
    protected $fillable = [
        'ai_chat_id',
        'role',
        'content',
        'tokens_used',
    ];

    public function chat()
    {
        return $this->belongsTo(AiChat::class , 'ai_chat_id');
    }
}