<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $data = $request->validated();

        // login peut être email OU téléphone
        $login = $data['login'];

        $user = User::query()
            ->where('email', $login)
            ->orWhere('telephone', $login)
            ->first();

        if (!$user || !Hash::check($data['motdepasse'], $user->motdepasse)) {
            return response()->json(['message' => 'Identifiants incorrects.'], 422);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => "Compte non activé. Finalisez l'inscription (étape champ) ou contactez l'admin.",
            ], 403);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'telephone' => $user->telephone,
                'email' => $user->email,
                'role' => $user->role->value,
                'is_active' => $user->is_active,
            ],
        ]);
    }
}