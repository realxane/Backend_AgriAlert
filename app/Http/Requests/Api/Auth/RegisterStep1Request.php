<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStep1Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30', 'unique:users,telephone'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'motdepasse' => ['required', 'string', 'min:8', 'confirmed'], 
        ];
    }

    public function messages(): array
    {
        return [
            'motdepasse.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}