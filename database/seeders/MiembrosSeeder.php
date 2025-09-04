<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MiembrosSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = ['Developer', 'QA', 'Project Manager', 'Designer', 'DevOps', 'Analyst'];

        for ($i = 1; $i <= 20; $i++) {
            DB::table('miembros')->insert([
                'nombre' => 'Miembro ' . $i . ' ' . Str::random(5),
                'correo' => 'miembro' . $i . '@demo.com',
                'cargo'  => $cargos[array_rand($cargos)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
