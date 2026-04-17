<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialService;

class SocialAuthController extends Controller
{
    protected $socialService;

    //Inyectar el servicio de SocialService para manejar la lógica de autenticación social
    public function __construct(SocialService $socialService)
    {
        $this->socialService = $socialService;
    }

    //Redirigir al usuario a la página de autenticación del proveedor social
    public function redirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    //Manejar la respuesta del proveedor social después de la autenticación API
    public function callback($provider)
    {
        try{
            $socialUser = Socialite::driver($provider)->stateless()->user();
        }
        catch (\Exception $e) {
            $frontend = env('FRONTEND_URL', 'http://localhost:8000');
            return redirect()->to($frontend . '/login?error=' . $provider . '_auth_failed');
        }

        $user = $this->socialService->findOrCreate($socialUser, $provider);

        $token = $user->createToken('auth_token')->plainTextToken;

        $frontend = env('FRONTEND_URL', 'http://localhost:8000');
        $redirectUrl = $frontend . '/auth/callback?token=' . $token;

        if($this->socialService->profileIncomplete($user)){
            $redirectUrl .= '&action=complete_profile';
        }

        return redirect()->to($redirectUrl);
    }

    public function webRedirect($provider)
    {
        // SIN stateless() para Web
        return Socialite::driver($provider)->redirect(); 
    }

    public function webCallback($provider)
    {
        try{
            $socialUser = Socialite::driver($provider)->user();
        }
        catch (\Exception $e) {
            return redirect()->route('login')->with('error', $provider . ' authentication failed');
        }

        $user = $this->socialService->findOrCreate($socialUser, $provider);

        auth()->login($user, true);

        if($this->socialService->profileIncomplete($user)){
            return redirect()->route('profile.complete');
        }

        return redirect()->route('dashboard');
    }
}
