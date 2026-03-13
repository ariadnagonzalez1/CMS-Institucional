<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalasRedaccionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('salas_redaccion')->insert([
            ['nombre' => 'Ingenieros de Formosa'],
            ['nombre' => 'Administración'],
            ['nombre' => 'Prensa']
        ]);
    }
}
