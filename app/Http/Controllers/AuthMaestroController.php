<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\UsuarioMaestro;

class AuthMaestroController extends Controller
{
    public function registro(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'correo' => 'required|email|unique:usuarios_maestros',
            'empresa' => 'required|string|unique:usuarios_maestros',
            'password' => 'required|min:6|confirmed',
        ]);

        $usuario = UsuarioMaestro::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'empresa' => $request->empresa,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Usuario maestro creado con éxito',
            'usuario' => $usuario,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = UsuarioMaestro::where('correo', $request->correo)->first();

        if (! $usuario || ! Hash::check($request->password, $usuario->password)) {
            throw ValidationException::withMessages([
                'correo' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $token = $usuario->createToken('token-maestro')->plainTextToken;

        return response()->json([
            'usuario' => $usuario,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
