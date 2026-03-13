<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class StoreCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:2|unique:countries,code',
            'description' => 'required|string',
            'image_url' => 'nullable|url',
            'competitiveness_score' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
        ];
    }
}
