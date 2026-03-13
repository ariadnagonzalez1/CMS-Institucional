<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModosTextoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('modos_texto')->insert([
            ['nombre' => 'Modo Diario'],
            ['nombre' => 'Modo Revista'],
            ['nombre' => 'Modo Blog']
        ]);
    }
}
