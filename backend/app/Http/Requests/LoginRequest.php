<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'cf_turnstile_response' => [\App\Models\Setting::where('key', 'turnstile_secret_key')->whereNotNull('value')->exists() ? 'required' : 'nullable', new \App\Rules\Turnstile()],
        ];
    }
}
