<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Cliente;
use App\Models\Miembro;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    // Obtener todos los proyectos
    public function index()
    {
        try {
            $resultado = DB::table('proyectos')
                ->join('clientes', 'proyectos.cliente_id', '=', 'clientes.id')
                ->leftJoin('miembros', 'proyectos.encargado_id', '=', 'miembros.id')
                ->select(
                    'proyectos.id',
                    'proyectos.nombre',
                    'proyectos.estado',
                    'proyectos.url',
                    'proyectos.repositorio',
                    'proyectos.fecha',
                    'proyectos.encargado_id',
                    'miembros.nombre as encargado_nombre',
                    'clientes.nombre as cliente',
                    'clientes.email',
                    'clientes.telefono',
                    'clientes.negociacion',
                    'clientes.aprobado'
                )
                ->get();

            Log::info('Esto es lo que trae:', ['proyectos' => $resultado]);

            return response()->json($resultado);
        } catch (\Exception $e) {
            Log::error('Error al obtener proyectos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener proyectos'], 500);
        }
    }

    // Crear un nuevo proyecto
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cliente_id' => 'required|exists:clientes,id',
            'estado' => 'nullable|string|max:100',
            'url' => 'nullable|url|max:255',
            'repositorio' => 'nullable|url|max:255',
            'fecha' => 'required|date',
            'tipo' => 'nullable|string|max:100'
        ]);

        $proyecto = Proyecto::create($validated);

        return response()->json([
            'message' => 'Proyecto creado con éxito.',
            'proyecto' => $proyecto
        ], 201);
    }

    // Obtener un proyecto específico
    public function show($id)
    {
        $proyecto = Proyecto::with(['cliente', 'encargado'])->findOrFail($id);
        return response()->json($proyecto);
    }

    // Actualizar un proyecto
    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'estado' => 'sometimes|required|string|max:50',
            'url' => 'nullable|url',
            'repositorio' => 'nullable|url',
            'fecha' => 'nullable|date',
            'encargado_id' => 'nullable|exists:miembros,id',
        ]);

        $proyecto->update($validated);

        return response()->json($proyecto);
    }

    // Eliminar un proyecto
    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return response()->json(['message' => 'Proyecto eliminado correctamente']);
    }

    // Obtener todos los proyectos de un cliente específico
    public function porCliente($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);

        $proyectos = $cliente->proyectos()->select('id', 'nombre', 'estado', 'fecha')->get();

        return response()->json($proyectos);
    }

    // 🔹 Obtener miembros asignados a un proyecto
    public function miembros($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $miembros = $proyecto->miembros()->withPivot('rol_en_proyecto')->get();

        return response()->json($miembros);
    }

    // 🔹 Asignar un miembro a un proyecto
    public function asignarMiembro(Request $request, $id)
    {
        $request->validate([
            'miembro_id' => 'required|exists:miembros,id',
            'rol' => 'required|string|max:50',
        ]);

        $proyecto = Proyecto::findOrFail($id);
        $proyecto->miembros()->syncWithoutDetaching([
            $request->miembro_id => ['rol_en_proyecto' => $request->rol]
        ]);

        return response()->json(['message' => 'Miembro asignado correctamente']);
    }

    public function actualizarRolMiembro(Request $request, $proyectoId, $miembroId)
    {
        $request->validate([
            'rol' => 'required|string|max:50'
        ]);

        $proyecto = Proyecto::findOrFail($proyectoId);

        // Verifica si intentamos poner un líder y ya hay otro
        if (strtolower($request->rol) === 'líder') {
            $existeLider = $proyecto->miembros()
                ->wherePivot('rol_en_proyecto', 'Líder')
                ->where('miembro_id', '!=', $miembroId)
                ->exists();

            if ($existeLider) {
                return response()->json(['error' => 'El proyecto ya tiene un líder asignado'], 400);
            }
        }

        $proyecto->miembros()->updateExistingPivot($miembroId, [
            'rol_en_proyecto' => $request->rol
        ]);

        return response()->json(['message' => 'Rol del miembro actualizado']);
    }

    public function quitarMiembro($proyectoId, $miembroId)
    {
        $proyecto = Proyecto::findOrFail($proyectoId);
        $proyecto->miembros()->detach($miembroId);

        return response()->json(['message' => 'Miembro eliminado del proyecto']);
    }

    public function asignarEncargado(Request $request, $proyectoId)
    {
        $request->validate([
            'encargado_id' => 'required|exists:miembros,id'
        ]);

        $proyecto = \App\Models\Proyecto::findOrFail($proyectoId);
        $proyecto->encargado_id = $request->encargado_id;
        $proyecto->save();

        return response()->json(['message' => 'Encargado asignado correctamente']);
    }
}
