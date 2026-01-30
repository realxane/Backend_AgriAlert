<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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

        // 1. Vérification de l'existence et du mot de passe
        if (!$user || !Hash::check($data['motdepasse'], $user->motdepasse)) {
            return response()->json(['message' => 'Identifiants incorrects.'], 422);
        }

        // 2. SÉCURITÉ : Vérification du rôle si c'est une connexion Admin
        // On vérifie si un flag 'source' est envoyé par React
        if ($request->filled('source') && $request->input('source') === 'admin_dashboard') {
            if ($user->role->value !== 'Admin') {
                return response()->json([
                    'message' => "Accès réservé aux administrateurs."
                ], 403);
            }
        }

        // 3. Vérification si le compte est actif
        if (!$user->is_active) {
            return response()->json([
                'message' => "Compte non activé. Contactez l'administrateur.",
            ], 403);
        }

        // 4. Création du token (le nom change selon la source)
        $tokenName = ($request->input('source') === 'admin_dashboard') ? 'admin_token' : 'mobile_token';
        $token = $user->createToken($tokenName)->plainTextToken;

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