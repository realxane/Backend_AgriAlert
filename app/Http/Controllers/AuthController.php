<?php
//  A effecer avant de prendre les modification de Alassane

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->motdepasse)) {
            return response()->json([
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Compte bloqué'
            ], 403);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }
}
