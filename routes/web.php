<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

//Procesar login y registro con formularios tradicionales
Route::post('/register', [AuthController::class, 'webLogin'])->name('web.login');
Route::post('/login', [AuthController::class, 'webRegister'])->name('web.register');

//Rutas para recuperación de contraseña web
Route::get('/forgot-password', function () {
    return view('auth.forgot_password');
})->name('password.request');

Route::get('/reset-password', function () {
    return view('auth.reset_password');
})->name('password.reset');

//Procesar solicitudes de recuperación de contraseña web
Route::post('/forgot-password', [AuthController::class, 'webForgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'webResetPassword'])->name('password.update');

//Rutas para autenticación social
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'webCallback'])->name('social.callback');

//Rutas protegidas para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('profile.dashboard');
    })->name('dashboard');

    Route::get('/profile/complete', function () {
        return view('auth.complete_profile');
    })->name('profile.complete');

    Route::post('/logout', [AuthController::class, 'webLogout'])->name('web.logout');
    
});

