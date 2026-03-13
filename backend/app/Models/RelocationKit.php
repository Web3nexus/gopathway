<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelocationKit extends Model
{
    protected $fillable = [
        'country_id',
        'title',
        'description',
        'icon',
        'is_premium',
        'order',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function items()
    {
        return $this->hasMany(RelocationKitItem::class)->orderBy('order');
    }
}
