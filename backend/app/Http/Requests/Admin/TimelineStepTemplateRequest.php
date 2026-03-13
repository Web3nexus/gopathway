<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TimelineStepTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'visa_type_id' => 'required|exists:visa_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'integer',
        ];
    }
}
