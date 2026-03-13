<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class VisaTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|array',
            'processing_time' => 'required|string',
            'is_active' => 'boolean',
            'min_education_level' => 'nullable|string',
            'min_work_experience_years' => 'nullable|integer|min:0',
            'min_ielts_score' => 'nullable|numeric|min:0|max:9',
            'min_funds_required' => 'nullable|numeric|min:0',
        ];
    }
}
