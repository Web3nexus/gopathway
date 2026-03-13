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
            'processing_time' => $this->processing_time,
            'requirements' => $this->requirements,
            'active' => $this->is_active,
            'country_id' => $this->country_id,
        ];
    }
}
