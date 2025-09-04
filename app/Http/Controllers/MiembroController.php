<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Miembro;
use Illuminate\Validation\Rule;


class MiembroController extends Controller
{
    public function index()
    {
        $usuario = Auth::guard('sanctum_maestro')->user();

        if (!$usuario) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $miembros = Miembro::where('usuario_maestro_id', $usuario->id)->get();
        return response()->json($miembros);
    }

    public function store(Request $request)
    {
        $usuario = Auth::guard('sanctum_maestro')->user();

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('miembros', 'correo')->where('usuario_maestro_id', $usuario->id)
            ],
            'cargo'  => 'nullable|string|max:50',
        ]);

        $usuario = Auth::guard('sanctum_maestro')->user();

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $validated['usuario_maestro_id'] = $usuario->id;
        $validated['password'] = bcrypt('cambiar123'); // ✅ IMPORTANTE

        return Miembro::create($validated);
    }

    public function update(Request $request, $id)
    {
        $usuario = Auth::guard('sanctum_maestro')->user();

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $miembro = Miembro::where('usuario_maestro_id', $usuario->id)->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => [
                'nullable',
                'email',
                'max:100',
                Rule::unique('miembros', 'correo')
                    ->where('usuario_maestro_id', $usuario->id)
                    ->ignore($miembro->id)
            ],
            'cargo'  => 'nullable|string|max:50',
        ]);

        $miembro->update($validated);
        return response()->json($miembro);
    }

    public function destroy($id)
    {
        $usuario = Auth::guard('sanctum_maestro')->user();

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $miembro = Miembro::where('usuario_maestro_id', $usuario->id)->findOrFail($id);
        $miembro->delete();

        return response()->json(['message' => 'Miembro eliminado']);
    }

    public function cambiarPassword(Request $request, $id)
    {
        $usuario = Auth::guard('sanctum_maestro')->user();

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $miembro = Miembro::where('usuario_maestro_id', $usuario->id)->findOrFail($id);
        $miembro->password = bcrypt($request->password);
        $miembro->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente.']);
    }
}
