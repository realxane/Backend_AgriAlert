<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'motdepasse' => ['required', 'confirmed', 'min:8'],
            // confirmed => attend motdepasse_confirmation
        ]);

        $credentials = [
            'email' => $request->email,
            'token' => $request->token,
            'password' => $request->motdepasse,
            'password_confirmation' => $request->motdepasse_confirmation,
        ];

        $status = Password::reset(
            $credentials,
            function ($user, $password) {
                $user->forceFill([
                    'motdepasse' => $password,
                ]);

                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json(['message' => __($status)], 422);
        }

        return response()->json(['message' => 'Mot de passe réinitialisé avec succès.']);
    }
}