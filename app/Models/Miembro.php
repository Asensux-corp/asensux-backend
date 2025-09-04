<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Miembro extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nombre',
        'correo',
        'cargo',
        'password',
        'usuario_maestro_id'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 🔑 Hash automático del password
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => bcrypt($value),
        );
    }

    // 🔗 Relación con proyectos
    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'proyecto_miembro')
            ->withPivot('rol_en_proyecto')
            ->withTimestamps();
    }

    // 🔗 Relación con tareas
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function usuarioMaestro()
    {
        return $this->belongsTo(UsuarioMaestro::class);
    }
}
