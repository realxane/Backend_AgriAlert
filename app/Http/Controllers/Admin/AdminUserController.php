<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\Role; 

class AdminUserController extends Controller
{
    // 1-Liste des utilisateurs ( uniquement agriculteurs)
    public function index()
    {
        $users = User::query()
            ->where('role', Role::Agriculteur) //  filtre agriculteurs
            ->withSum('champs', 'aire')
            ->get();

        return response()->json($users);
    }

    // 2-Modifier utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only([
            'nom',
            'telephone',
            'email',
            'motdepasse'
        ]));

        return response()->json([
            'message' => 'Utilisateur modifié avec succès'
        ]);
    }

    // 3-Bloquer / Débloquer utilisateur
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => $user->is_active ? 'Utilisateur activé' : 'Utilisateur bloqué'
        ]);
    }

    // 4-Supprimer utilisateur
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé'
        ]);
    }
}
