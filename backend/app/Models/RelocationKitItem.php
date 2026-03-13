<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelocationKitItem extends Model
{
    protected $fillable = [
        'relocation_kit_id',
        'title',
        'content',
        'is_premium',
        'order',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];

    public function kit()
    {
        return $this->belongsTo(RelocationKit::class, 'relocation_kit_id');
    }
}
