<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nom' => ['sometimes','string','max:255'],
            'telephone' => ['sometimes','string','max:30'],
            'email' => ['sometimes','email','max:255'],
        ]);

        $user = $request->user();
        $user->fill($data)->save();

        return response()->json(['message' => 'Profil mis à jour', 'user' => $user]);
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'motdepasse_actuel' => ['required','string'],
            'nouveau_motdepasse' => ['required','string','min:6','confirmed'],
            // new_password_confirmation doit être envoyé
        ]);

        $user = $request->user();

        if (!Hash::check($data['motdepasse_actuel'], $user->motdepasse)) {
            return response()->json(['message' => 'Mot de passe actuel incorrect'], 422);
        }

        $user->save();

        return response()->json(['message' => 'Mot de passe modifié']);
    }

    public function updatePhoto(Request $request)
    {
        $data = $request->validate([
            'photo' => ['required','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        $path = $request->file('photo')->store('profiles', 'public');

        $user = $request->user();
        $user->photo_path = $path; 
        $user->save();

        return response()->json([
            'message' => 'Photo mise à jour',
            'photo_url' => asset('storage/'.$path),
        ]);
    }
}