<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Etapa;

class TareaEtapaController extends Controller
{
    public function mover(Request $request, $tareaId)
    {
        $request->validate([
            'etapa_id' => 'required|exists:etapas,id',
            'posicion' => 'nullable|integer'
        ]);

        $tarea = Tarea::findOrFail($tareaId);
        $tarea->etapa_id = $request->etapa_id;
        $tarea->posicion = $request->posicion ?? 0;
        $tarea->save();

        return response()->json(['message' => 'Tarea movida con éxito']);
    }
}
