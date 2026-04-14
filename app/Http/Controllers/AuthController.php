<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

//AuthController
class AuthController extends Controller
{
    //Registrar un nuevo usuario
    public function register (Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Usuario registrado exitosamente'], 201);
    }

    //Iniciar sesión
    public function login (Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        //Verificar el correo electrónico y la contraseña
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales invalidas'], 401);
        }
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Usuario inició sesión exitosamente'], 200);
    }

    //Cerrar sesión
    public function logout (Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario cerró sesión exitosamente'
        ], 200);
    }

    //Obtener el usuario autenticado
    public function user (Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ], 200);
    }

}
