<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = ['name', 'description', 'is_required', 'visa_type_id'];

    public function visaType()
    {
        return $this->belongsTo(VisaType::class);
    }
}
