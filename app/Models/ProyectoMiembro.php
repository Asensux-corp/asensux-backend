<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProyectoMiembro extends Pivot
{
    protected $table = 'proyecto_miembro';

    protected $fillable = [
        'proyecto_id',
        'miembro_id',
        'rol_en_proyecto',
    ];
}
