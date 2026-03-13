<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModosGrupoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('modos_grupo')->insert([
            [
                'nombre' => 'Ver y compartir contenido',
                'puede_ver' => true,
                'puede_compartir' => true
            ],
            [
                'nombre' => 'No ver ni compartir contenido',
                'puede_ver' => false,
                'puede_compartir' => false
            ]
        ]);
    }
}
