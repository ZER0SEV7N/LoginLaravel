<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    //Redirigir al usuario a la página de autenticación de Google
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    //Manejar la respuesta de Google después de la autenticación
    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate([
            'email' => $googleUser->getEmail()
            ], [
                'username' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(str()->random(24)),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            $frontend = env('FRONTEND_URL', 'http://localhost:8000/dashboard.html');

        return redirect()->to($frontend . '/auth/callback?token=' . $token);
    }
}
