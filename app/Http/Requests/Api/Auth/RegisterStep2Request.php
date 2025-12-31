<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStep2Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nom' => is_string($this->nom) ? trim($this->nom) : $this->nom,
        ]);
    }

    public function rules(): array
    {
        return [
            'registration_session_id' => ['required', 'string'],

            'nom' => ['required', 'string', 'max:255'],

            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'aire' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du champ est obligatoire.',
            'latitude.between' => 'La latitude doit être entre -90 et 90.',
            'longitude.between' => 'La longitude doit être entre -180 et 180.',
            'aire.min' => "L'aire doit être supérieure ou égale à 0.",
        ];
    }

    public function attributes(): array
    {
        return [
            'nom' => 'nom du champ',
            'aire' => 'aire',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
        ];
    }
}