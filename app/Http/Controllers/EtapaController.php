<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etapa;

class EtapaController extends Controller
{
    public function index()
    {
        return Etapa::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'proyecto_id' => 'required|exists:proyectos,id',
            'orden' => 'nullable|integer'
        ]);

        $etapa = Etapa::create([
            'nombre' => $validated['nombre'],
            'proyecto_id' => $validated['proyecto_id'],
            'orden' => $validated['orden'] ?? 0,
        ]);

        return response()->json($etapa, 201);
    }

    public function show($id)
    {
        return Etapa::with('tareas')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $etapa = Etapa::findOrFail($id);
        $etapa->update($request->all());

        return response()->json(['message' => 'Etapa actualizada']);
    }

    public function destroy($id)
    {
        $etapa = Etapa::findOrFail($id);
        $etapa->delete();

        return response()->json(['message' => 'Etapa eliminada']);
    }

    public function porProyecto($id)
    {
        return Etapa::where('proyecto_id', $id)->orderBy('orden')->get();
    }

    public function etapasPorProyecto($proyectoId)
    {
        $etapas = Etapa::with(['tareas' => function ($q) {
            $q->orderBy('posicion');
        }])
            ->where('proyecto_id', $proyectoId)
            ->orderBy('orden')
            ->get();

        return response()->json($etapas);
    }

    public function reordenar(Request $request)
    {
        $data = $request->validate([
            'orden' => 'required|array',
            'orden.*.id' => 'required|exists:etapas,id',
            'orden.*.posicion' => 'required|integer',
        ]);

        foreach ($data['orden'] as $item) {
            Etapa::where('id', $item['id'])->update(['orden' => $item['posicion']]);
        }

        return response()->json(['message' => 'Orden actualizado']);
    }
}
