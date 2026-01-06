<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterStep2Request;
use App\Models\ActiviteAgricole;
use App\Models\Champ;
use App\Models\Plantation;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RegisterAgriculteurStep2Controller extends Controller
{
    public function __invoke(RegisterStep2Request $request)
    {
        $data = $request->validated();

        $cacheKey = "register:{$data['registration_session_id']}";
        $payload = Cache::get($cacheKey);

        if (!$payload || empty($payload['user_id'])) {
            return response()->json([
                'message' => "Session d'inscription invalide ou expirée. Recommencez l'inscription.",
            ], 422);
        }

        $user = User::find($payload['user_id']);

        if (!$user) {
            Cache::forget($cacheKey);
            return response()->json(['message' => "Utilisateur introuvable."], 404);
        }

        if ($user->role !== Role::Agriculteur) {
            return response()->json([
                'message' => "Seul un agriculteur peut finaliser l'inscription.",
            ], 403);
        }

        if ($user->is_active) {
            Cache::forget($cacheKey);
            return response()->json([
                'message' => "Inscription déjà finalisée.",
            ], 409);
        }

        [$champ, $plantations, $token] = DB::transaction(function () use ($user, $data, $cacheKey) {

            $champ = Champ::create([
                'user_id' => $user->id,
                'nom' => $data['nom'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'aire' => $data['aire'] ?? null,
                'region_id' => $data['region_id'],
            ]);

            $plantations = [];

            foreach ($data['plantations'] as $p) {
                $plantation = Plantation::create([
                    'champ_id' => $champ->id,
                    'culture_id' => $p['culture_id'],
                    'date_debut' => $p['date_debut'] ?? now()->toDateString(), 
                ]);

                // activités initiales (si fournies)
                if (!empty($p['activities']) && is_array($p['activities'])) {
                    foreach ($p['activities'] as $a) {
                        ActiviteAgricole::create([
                            'user_id' => $user->id,
                            'plantation_id' => $plantation->id,
                            'type' => $a['type'],
                            'done_at' => $a['done_at'] ?? now(),
                        ]);
                    }
                }

                $plantations[] = $plantation;
            }

            $user->forceFill(['is_active' => true])->save();
            Cache::forget($cacheKey);

            $token = $user->createToken('mobile')->plainTextToken;

            return [$champ, $plantations, $token];
        });

        return response()->json([
            'message' => "Inscription finalisée. Bienvenue !",
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
            'champ' => $champ,
            'plantations' => collect($plantations)->map(fn ($pl) => $pl->load(['culture', 'activitesAgricoles'])),
        ], 201);
    }
}