<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    // 🔒 Desactivar claves foráneas
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // Limpiar tablas en orden correcto
    DB::table('proyectos')->truncate(); // primero la que depende
    DB::table('clientes')->truncate();
    DB::table('users')->truncate();

    // ✅ Reactivar claves foráneas
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    // Insertar datos
    User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    DB::table('clientes')->insert([
        ['nombre' => 'Cliente A', 'email' => 'clientea@example.com', 'telefono' => '3001234567', 'negociacion' => 'Negociación exitosa', 'aprobado' => true],
        ['nombre' => 'Cliente B', 'email' => 'clienteb@example.com', 'telefono' => '3019876543', 'negociacion' => 'En revisión', 'aprobado' => false],
        ['nombre' => 'Cliente C', 'email' => 'clientec@example.com', 'telefono' => '3025678912', 'negociacion' => 'Negociación avanzada', 'aprobado' => true],
    ]);

    DB::table('proyectos')->insert([
        ['cliente_id' => 1, 'nombre' => 'Landing Cliente A', 'estado' => 'Publicado', 'url' => 'https://cliente-a.com', 'repositorio' => 'https://github.com/cliente-a/landing', 'fecha' => '2025-06-01'],
        ['cliente_id' => 2, 'nombre' => 'Sitio Cliente B', 'estado' => 'En revisión', 'url' => 'https://cliente-b.com', 'repositorio' => 'https://github.com/cliente-b/institucional', 'fecha' => '2025-06-05'],
        ['cliente_id' => 3, 'nombre' => 'Ecommerce Cliente C', 'estado' => 'En desarrollo', 'url' => 'https://cliente-c.com', 'repositorio' => 'https://github.com/cliente-c/ecommerce', 'fecha' => '2025-06-10'],
    ]);
}

}

