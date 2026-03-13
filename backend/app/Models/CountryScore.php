<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryScore extends Model
{
    protected $fillable = [
        'country_id',
        'visa_difficulty',
        'cost_index',
        'processing_speed',
        'pr_ease',
        'job_market',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    protected static function booted()
    {
        static::saved(function ($score) {
            $score->updateGlobalScore();
        });
    }

    public function updateGlobalScore()
    {
        $avg = ($this->visa_difficulty + 
                $this->cost_index + 
                $this->processing_speed + 
                $this->pr_ease + 
                $this->job_market) / 5;

        $this->country()->update(['competitiveness_score' => (int)$avg]);
    }
}
