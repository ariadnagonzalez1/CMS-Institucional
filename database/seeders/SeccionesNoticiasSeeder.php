<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionesNoticiasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('secciones_noticias')->insert([
            [
                'modo_texto_id' => 1,
                'nombre' => 'Institucional'
            ],
            [
                'modo_texto_id' => 1,
                'nombre' => 'Autoridades'
            ],
            [
                'modo_texto_id' => 1,
                'nombre' => 'Eventos'
            ]
        ]);
    }
}
