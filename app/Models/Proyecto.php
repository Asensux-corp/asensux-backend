<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $fillable = [
        'nombre',
        'cliente_id',
        'estado',
        'url',
        'repositorio',
        'fecha',
        'tipo',
        'encargado_id'
    ];

    // 🔗 Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // 🔗 Relación muchos a muchos con miembros (con campo pivot)
    public function miembros()
    {
        return $this->belongsToMany(Miembro::class, 'proyecto_miembro')
            ->withPivot('rol_en_proyecto')
            ->withTimestamps();
    }

    // 🔗 Relación uno a muchos con tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    // 🔗 Relación uno a muchos con etapas
    public function etapas()
    {
        return $this->hasMany(Etapa::class);
    }

    public function encargado()
    {
        return $this->belongsTo(\App\Models\Miembro::class, 'encargado_id');
    }
}
