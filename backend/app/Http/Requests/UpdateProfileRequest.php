<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'age' => ['nullable', 'integer', 'min:18', 'max:99'],
            'education_level' => ['nullable', 'string', 'in:high_school,bachelors,masters,phd,other'],
            'work_experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
            'funds_range' => ['nullable', 'string', 'in:under_5k,5k_10k,10k_20k,20k_50k,over_50k'],
            'ielts_status' => ['nullable', 'string', 'in:not_taken,scheduled,band_5,band_6,band_7,band_8_plus'],
            'preferred_country_id' => ['nullable', 'exists:countries,id'],
        ];
    }
}
