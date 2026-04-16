<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialService;

class GoogleAuthController extends Controller
{
    protected $socialService;

    //Inyectar el servicio de SocialService para manejar la lógica de autenticación social
    public function __construct(SocialService $socialService)
    {
        $this->socialService = $socialService;
    }

    //Redirigir al usuario a la página de autenticación de Google
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    //Manejar la respuesta de Google después de la autenticación
    public function callback()
    {
        //Obtener la información del usuario autenticado con Google
        try{
            $googleUser = Socialite::driver('google')->stateless()->user();
        }
        catch (\Exception $e) {
            $frontend = env('FRONTEND_URL', 'http://localhost:8000');
            return redirect()->to($frontend . '/login?error=google_auth_failed');
        }

        $user = $this->socialService->findOrCreate($googleUser, 'google');

        $token = $user->createToken('auth_token')->plainTextToken;

        $frontend = env('FRONTEND_URL', 'http://localhost:8000');
        $redirectUrl = $frontend . '/auth/callback?token=' . $token;

        if($this->socialService->profileIncomplete($user)){
            $redirectUrl .= '&action=complete_profile';
        }

        return redirect()->to($redirectUrl);
    }
}
