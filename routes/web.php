<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

//Procesar login y registro con formularios tradicionales
Route::post('/login', [AuthController::class, 'webLogin'])->name('web.login');
Route::post('/register', [AuthController::class, 'webRegister'])->name('web.register');

//Rutas para recuperación de contraseña web
Route::get('/forgot-password', function () {
    return view('auth.forgot_password');
})->name('password.request');

Route::get('/reset-password/{token}', function (Request $request, $token) {
    return view('auth.reset_password', [
        'token' => $token,
        'email' => $request->query('email')
    ]);
})->name('password.reset');

//Procesar solicitudes de recuperación de contraseña web
Route::post('/forgot-password', [AuthController::class, 'webForgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'webResetPassword'])->name('password.update');

//Rutas para autenticación social
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'webRedirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'webCallback'])->name('social.callback');

//Rutas protegidas para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('profile.dashboard');
    })->name('dashboard');

    Route::get('/profile/complete', function () {
        return view('auth.complete_profile');
    })->name('profile.complete');

    Route::post('/profile/complete', [AuthController::class, 'webCompleteProfile'])->name('auth.complete_profile');

    Route::post('/logout', [AuthController::class, 'webLogout'])->name('web.logout');
    
});

