<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisaTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'pathway_type' => $this->pathway_type,
            'processing_time' => $this->processing_time,
            'requirements' => $this->requirements,
            'restrictions' => $this->restrictions,
            'benefits' => $this->benefits,
            'min_funds_required' => $this->min_funds_required,
            'active' => $this->is_active,
            'country_id' => $this->country_id,
            'cost_templates' => $this->costTemplates,
            'cost_templates_count' => $this->costTemplates()->count(),
        ];
    }
}
