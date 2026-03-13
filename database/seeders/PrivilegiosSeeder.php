<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivilegiosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('privilegios')->insert([
            ['nombre' => 'Gerente General'],
            ['nombre' => 'Jefe de Redacción'],
            ['nombre' => 'Periodista / Opinólogo'],
            ['nombre' => 'Crónica y Tipeado'],
            ['nombre' => 'Área de Corrección'],
            ['nombre' => 'Vendedores'],
            ['nombre' => 'Test / Registrado Público']
        ]);
    }
}
