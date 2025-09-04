<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Etapa;

class TareaController extends Controller
{
    public function index()
    {
        return Tarea::all();
    }

    public function show($id)
    {
        return Tarea::findOrFail($id);
    }

    public function porProyecto($id)
    {
        return Tarea::where('proyecto_id', $id)->get();
    }

    public function porEtapa($etapaId)
    {
        $tareas = Tarea::where('etapa_id', $etapaId)
            ->orderBy('posicion')
            ->get();

        return response()->json($tareas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'proyecto_id' => 'required|exists:proyectos,id',
            'etapa_id' => 'required|exists:etapas,id',
        ]);

        $tarea = Tarea::create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'proyecto_id' => $validated['proyecto_id'],
            'etapa_id' => $validated['etapa_id'], // 👈 IMPORTANTE
            'estado' => 'pendiente',
            'posicion' => 0, // Opcional: podrías calcular posición también
        ]);

        // $tarea->etapas()->attach($validated['etapa_id'], [
        //     'fecha_movimiento' => now()
        // ]);

        return response()->json($tarea, 201);
    }

    public function update(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'etapa_id' => 'sometimes|exists:etapas,id',
            'miembro_id' => 'nullable|exists:miembros,id',
            'estado' => 'nullable|string',
            'posicion' => 'nullable|integer',
        ]);

        $tarea->update($validated);

        return response()->json(['message' => 'Tarea actualizada']);
    }

    public function destroy($id)
    {
        $tarea = Tarea::findOrFail($id);
        $tarea->delete();

        return response()->json(['message' => 'Tarea eliminada']);
    }
    public function asignarMiembro(Request $request, $tareaId)
    {
        $request->validate([
            'miembro_id' => 'required|exists:miembros,id'
        ]);

        $tarea = \App\Models\Tarea::findOrFail($tareaId);

        $tarea->miembro_id = $request->miembro_id;
        $tarea->save();

        return response()->json(['message' => 'Miembro asignado a la tarea correctamente']);
    }
}
