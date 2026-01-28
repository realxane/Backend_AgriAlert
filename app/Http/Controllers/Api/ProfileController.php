<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Plantation;

class ProfileController extends Controller
{
    /**
     * AFFICHER LE PROFIL COMPLET (User + Champ + Région + Culture)
     */
    public function show(Request $request)
    {
        $userId = $request->user()->id;

        // On charge l'user avec ses relations en cascade
        $user = User::with(['champs.region', 'champs.plantations.culture'])->find($userId);

        $champ = $user->champs->first();

        // On extrait le nom de la culture via la table plantations
        $nomCulture = 'Non définie';
        if ($champ && $champ->plantations->first() && $champ->plantations->first()->culture) {
            $nomCulture = $champ->plantations->first()->culture->nom;
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'email' => $user->email,
                'telephone' => $user->telephone,
                'photo_url' => $user->photo_url,
                'role' => $user->role,
            ],
            'champ' => $champ ? [
                'id' => $champ->id,
                'nom' => $champ->nom,
                'aire' => $champ->aire,
                'latitude' => $champ->latitude,
                'longitude' => $champ->longitude,
                'type_culture' => $nomCulture,
                'region_name' => $champ->region ? $champ->region->name : 'Non définie',
                'region_id' => $champ->region_id,
            ] : null
        ]);
    }


    /**
 * MODIFIER TOUT LE PROFIL (Nom, Tel, Email + Photo optionnelle)
 */
public function update(Request $request)
{
    $user = $request->user();
    
    // 1. Validation des données texte et de l'image
    $data = $request->validate([
        'nom' => ['sometimes', 'string', 'max:255'],
        'telephone' => ['sometimes', 'string', 'max:30', 'unique:users,telephone,' . $user->id],
        'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'photo' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // Validation photo ajoutée
    ]);

    // 2. Gestion de la photo si elle est présente
    if ($request->hasFile('photo')) {
        // Supprimer l'ancienne photo si elle existe
        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        // Stocker la nouvelle photo
        $path = $request->file('photo')->store('profiles', 'public');
        $user->photo_path = $path;
    }

    // 3. Mise à jour des autres champs (Nom, Email, Tel)
    $user->fill(collect($data)->except('photo')->toArray());
    $user->save();

    return response()->json([
        'message' => 'Profil mis à jour avec succès',
        'user' => [
            'id' => $user->id,
            'nom' => $user->nom,
            'email' => $user->email,
            'telephone' => $user->telephone,
            'photo_url' => asset('storage/' . $user->photo_path),
        ]
    ]);
}
    /**
     * MODIFIER LE MOT DE PASSE
     */
    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'motdepasse_actuel' => ['required', 'string'],
            'nouveau_motdepasse' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = $request->user();

        if (!Hash::check($data['motdepasse_actuel'], $user->motdepasse)) {
            return response()->json(['message' => 'Mot de passe actuel incorrect'], 422);
        }

        $user->motdepasse = Hash::make($data['nouveau_motdepasse']);
        $user->save();

        return response()->json(['message' => 'Mot de passe modifié']);
    }

    /**
     * MODIFIER LE CHAMP (Nom, Région, GPS, Culture, Aire)
     */
    public function updateChamp(Request $request)
    {
        $user = $request->user();
        $champ = $user->champs()->first();

        if (!$champ) {
            return response()->json(['message' => 'Aucun champ trouvé'], 404);
        }

        $request->validate([
            'nom' => 'sometimes|string',
            'region_id' => 'sometimes|exists:regions,id',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'aire' => 'sometimes|numeric',
            'culture_id' => 'sometimes|exists:cultures,id', 
        ]);

        // Mise à jour de la table champs
        $champ->update($request->only(['nom', 'region_id', 'latitude', 'longitude', 'aire']));

        // Mise à jour de la culture via la table plantations
        if ($request->has('culture_id')) {
            Plantation::updateOrCreate(
                ['champ_id' => $champ->id],
                ['culture_id' => $request->culture_id]
            );
        }

        return response()->json(['message' => 'Données du champ mises à jour avec succès']);
    }
}