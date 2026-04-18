<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;

//Rutas API para autenticación y gestión de usuarios
//Rutas públicas para registro, login y recuperación de contraseña
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

//Rutas para recuperación de contraseña
Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);

//Rutas para autenticación social
Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect']);
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback']);

//Rutas protegidas para usuarios autenticados con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/profile/complete', [AuthController::class, 'completeProfile']);
});



