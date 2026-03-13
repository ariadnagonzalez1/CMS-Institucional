<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionesDescargablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('secciones_descargables')->insert([
            ['nombre' => 'Trámites'],
            ['nombre' => 'Formularios'],
            ['nombre' => 'Documentos']
        ]);
    }
}
