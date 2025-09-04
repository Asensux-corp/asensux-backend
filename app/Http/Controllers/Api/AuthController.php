<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Miembro;
use Illuminate\Validation\ValidationException;
use App\Models\UsuarioMaestro;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        Log::debug('Login intento', $request->all());

        // 👉 Buscar usuario maestro
        $usuario = UsuarioMaestro::where('correo', $request->correo)->first();

        if (!$usuario) {
            Log::warning('Usuario no encontrado', ['correo' => $request->correo]);
            return response()->json(['message' => 'Las credenciales no son correctas.'], 401);
        }

        if (!Hash::check($request->password, $usuario->password)) {
            Log::warning('Password incorrecto', ['correo' => $request->correo]);
            return response()->json(['message' => 'Las credenciales no son correctas.'], 401);
        }

        // 👉 Crear token con Sanctum
        $token = $usuario->createToken('token-maestro')->plainTextToken;

        return response()->json([
            'token' => $token,
            'usuario' => $usuario
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
