<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class UpdateCountryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $country = $this->route('country');
        $countryId = is_object($country) ? $country->id : $country;

        return [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|size:2|unique:countries,code,' . $countryId,
            'description' => 'sometimes|required|string',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ];
    }
}
