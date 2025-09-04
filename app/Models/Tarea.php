<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'proyecto_id',
        'miembro_id',
        'fecha_inicio',
        'fecha_fin',
        'etapa_id',
        'posicion'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function miembro()
    {
        return $this->belongsTo(Miembro::class);
    }

    public function etapa()
    {
        return $this->belongsTo(Etapa::class, 'etapa_id');
    }
    // public function etapas()
    // {
    //     return $this->belongsToMany(Etapa::class, 'tarea_etapa')
    //         ->withPivot('fecha_movimiento')
    //         ->withTimestamps();
    // }
}
