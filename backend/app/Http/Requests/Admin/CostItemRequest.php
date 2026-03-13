<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CostItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pathway_id' => 'nullable|exists:pathways,id',
            'country_id' => 'nullable|exists:countries,id',
            'visa_type_id' => 'nullable|exists:visa_types,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_mandatory' => 'boolean',
            'currency' => 'string|size:3',
        ];
    }
}
