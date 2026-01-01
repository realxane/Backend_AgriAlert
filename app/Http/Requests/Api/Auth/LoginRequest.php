<?php

namespace App\Http\Requests\Api\Auth;

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
            // tu peux te connecter par email OU telephone
            'login' => ['required', 'string'],
            'motdepasse' => ['required', 'string'],
        ];
    }
}