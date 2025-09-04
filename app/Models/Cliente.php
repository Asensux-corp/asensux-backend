<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'negociacion',
        'aprobado',
    ];
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'cliente_id');
    }
}
