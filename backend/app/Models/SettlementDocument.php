<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettlementDocument extends Model
{
    protected $fillable = [
        'name',
        'description',
        'required_for',
        'issuing_authority',
    ];
}
