<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', \Illuminate\Validation\Rules\Password::defaults()],
            'ref' => ['nullable', 'string', 'exists:users,referral_code'],
            'cf_turnstile_response' => [\App\Models\Setting::where('key', 'turnstile_secret_key')->whereNotNull('value')->exists() ? 'required' : 'nullable', new \App\Rules\Turnstile()],
        ];
    }
}