<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class SocialAuthController extends Controller
{
    public function handleSocialLogin(Request $request, $provider)
    {
       
        $validated = $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            
            $socialUser = Socialite::driver($provider)->userFromToken($validated['access_token']);

            if (!$socialUser->getEmail()) {
                return response()->json(['error' => 'Email non fourni par le fournisseur social.'], 422);
            }

            $user = User::where($provider . '_id', $socialUser->getId())
                        ->orWhere('email', $socialUser->getEmail())
                        ->first();

            if (!$user) {
                
                $user = User::create([
                    'id' => (string) Str::uuid(),
                    'nom' => $socialUser->getName() ?? 'Utilisateur',
                    'email' => $socialUser->getEmail(),
                    $provider . '_id' => $socialUser->getId(),
                    'role' => Role::Agriculteur,
                    'is_active' => true, 
                 
                ]);
            } else {
                
                $user->update([
                    $provider . '_id' => $socialUser->getId(),
                ]);
            }

            
            $user->tokens()->delete(); 
            $token = $user->createToken('mobile-auth')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Authentification réussie',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 200);

        } catch (Exception $e) {
          
            Log::error("Erreur Social Login ($provider): " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Authentification échouée',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}