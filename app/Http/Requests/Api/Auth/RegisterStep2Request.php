<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\ActiviteType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterStep2Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'registration_session_id' => ['required', 'uuid'],

            // Champ
            'nom' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'aire' => ['nullable', 'numeric', 'min:0'],
            'region_id' => ['required', 'uuid', 'exists:regions,id'],

            // ✅ Plusieurs cultures => plusieurs plantations
            'plantations' => ['required', 'array', 'min:1'],
            'plantations.*.culture_id' => ['required', 'uuid', 'exists:cultures,id'],
            'plantations.*.date_debut' => ['nullable', 'date'],

            // ✅ Activités initiales (optionnel)
            'plantations.*.activities' => ['nullable', 'array'],
            'plantations.*.activities.*.type' => ['required', Rule::enum(ActiviteType::class)],
            'plantations.*.activities.*.done_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Optionnel: éviter doublons si l’utilisateur sélectionne 2x la même culture
        if (is_array($this->plantations)) {
            $this->merge([
                'plantations' => array_values($this->plantations),
            ]);
        }
    }
}