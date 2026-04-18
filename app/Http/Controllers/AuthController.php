<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

//AuthController
class AuthController extends Controller
{
    //Registrar un nuevo usuario
    public function register (Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'lastname' => 'nullable|string|max:50',
            'username' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'phone' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:15|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'document' => $request->document,
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
        //Eliminar y crear un nuevo token para el usuario
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

    //Completar el perfil del usuario después de la autenticación social
    public function completeProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'lastname' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:15|unique:users,document,' . $user->id,
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Perfil completado exitosamente'
        ], 200);
    }

    //Enviar enlace de restablecimiento de contraseña
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        //Verificar si el enlace se envió correctamente
        if($status === Password::RESET_LINK_SENT){
            return response()->json([
                'success' => true,
                'message' => 'Enlace de restablecimiento de contraseña enviado exitosamente'
            ], 200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar el enlace de restablecimiento de contraseña'
            ], 400);
        }
    }

    //Restablecer la contraseña del usuario
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function($user, $password){
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
            }
        );
        //Verificar si la contraseña se restableció correctamente
        if($status === Password::PASSWORD_RESET){
            return response()->json([
                'success' => true,
                'message' => 'Contraseña restablecida exitosamente'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'No se pudo restablecer la contraseña'
            ], 400);
        }
    }

    //SECCION WEB:
    //Web login
    public function webLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->route('login')->with('error', 'Credenciales invalidas');
        }

        auth()->login($user, true);

        return redirect()->route('dashboard');
    }

    //Web register
    public function webRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'lastname' => 'nullable|string|max:50',
            'username' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'phone' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:15|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'document' => $request->document,
            'password' => Hash::make($request->password),
        ]);

        auth()->login($user, true);

        return redirect()->route('dashboard');
    }

    //Web logout
    public function webLogout(Request $request)
    {
        auth()->logout();

        return redirect()->route('login');
    }

    //Procesar la solicitud de recuperación de contraseña web
    public function webForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if($status === Password::RESET_LINK_SENT)
            return redirect()->route('password.request')->with('success', 'Recovery link sent successfully. Please check your email.');
        else
            return redirect()->route('password.request')->with('error', 'Cannot send the recovery email, try again.');
        
    }

    //Procesar el restablecimiento de contraseña web
    public function webResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function($user, $password){
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                
                $user->save();
            }
        );
        if($status === Password::PASSWORD_RESET)
            return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now log in with your new password.');
        else 
            return back()->withErrors(['email' => __($status)]);
    }

    //web completar perfil después de autenticación social
    public function webCompleteProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'lastname' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'document' => 'nullable|string|max:15|unique:users,document,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('dashboard')->with('success', 'Profile registered successfully');
    }
}
