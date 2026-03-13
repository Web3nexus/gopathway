<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SopDraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'visa_type_id',
        'answers',
        'generated_text',
        'status',
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }
}
