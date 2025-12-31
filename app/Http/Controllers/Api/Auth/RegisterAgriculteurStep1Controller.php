<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterStep1Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RegisterAgriculteurStep1Controller extends Controller
{
    public function __invoke(RegisterStep1Request $request)
    {
        $data = $request->validated();

        // Crée l'utilisateur agriculteur "en attente"
        $user = User::create([
            'nom' => $data['nom'],
            'telephone' => $data['telephone'],
            'email' => $data['email'],
            'motdepasse' => $data['motdepasse'], 
            'role' => Role::Agriculteur,
            'is_active' => false, // important : pas "actif" tant que le champ n'est pas créé
        ]);

        // "Session d'inscription" (plutôt qu'une session PHP classique, on stocke en cache)
        $registrationSessionId = (string) Str::uuid();

        Cache::put(
            key: "register:{$registrationSessionId}",
            value: [
                'user_id' => $user->id,
                'step' => 1,
            ],
            ttl: now()->addMinutes(30)
        );

        return response()->json([
            'message' => 'Étape 1 OK. Veuillez renseigner les informations du champ.',
            'registration_session_id' => $registrationSessionId,
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'telephone' => $user->telephone,
                'email' => $user->email,
                'role' => $user->role->value,
                'is_active' => $user->is_active,
            ],
        ], 201);
    }
}