<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'age' => $this->age,
            'education_level' => $this->education_level,
            'work_experience_years' => $this->work_experience_years,
            'funds_range' => $this->funds_range,
            'ielts_status' => $this->ielts_status,
            'preferred_country_id' => $this->preferred_country_id,
            'current_savings' => $this->user->current_savings ?? 0,
            'monthly_savings_target' => $this->user->monthly_savings_target ?? 0,
            'preferred_country' => $this->whenLoaded('preferredCountry', fn() => [
                'id' => $this->preferredCountry->id,
                'name' => $this->preferredCountry->name,
                'code' => $this->preferredCountry->code,
            ]),
        ];
    }
}
