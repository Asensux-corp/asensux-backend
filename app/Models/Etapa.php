<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $fillable = [
        'nombre',
        'orden',
        'proyecto_id'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'etapa_id')->orderBy('posicion');
    }
}
