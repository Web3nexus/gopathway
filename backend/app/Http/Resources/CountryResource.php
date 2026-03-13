<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'active' => $this->is_active,
            'visa_types_count' => $this->whenCounted('visaTypes'),
            'visa_types' => VisaTypeResource::collection($this->whenLoaded('visaTypes')),
            'competitiveness_score' => $this->competitiveness_score,
            'scores' => $this->score ? [
                'visa_difficulty' => $this->score->visa_difficulty,
                'cost_index' => $this->score->cost_index,
                'processing_speed' => $this->score->processing_speed,
                'pr_ease' => $this->score->pr_ease,
                'job_market' => $this->score->job_market,
            ] : null,
        ];
    }
}
