<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngenieriasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ingenierias')->insert([
            ['nombre' => 'Ingeniería Civil'],
            ['nombre' => 'Ingeniería Electromecánica'],
            ['nombre' => 'Ingeniería Mecánica'],
            ['nombre' => 'Ingeniería en Sistemas'],
            ['nombre' => 'Ingeniería Industrial'],
        ]);
    }
}