<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionesMultimediaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('secciones_multimedia')->insert([
            ['nombre' => 'Entrevistas'],
            ['nombre' => 'Videos Institucionales']
        ]);
    }
}
