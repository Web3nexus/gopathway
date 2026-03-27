<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScholarshipSource extends Model
{
    protected $fillable = [
        'name',
        'base_url',
        'crawl_type',
        'scraping_rules',
        'is_active',
        'last_run_at',
    ];

    protected $casts = [
        'scraping_rules' => 'array',
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
    ];

    public function scholarships(): HasMany
    {
        return $this->hasMany(Scholarship::class);
    }
}
