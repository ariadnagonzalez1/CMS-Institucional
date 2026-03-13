<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionesAgendaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('secciones_agenda')->insert([
            ['nombre' => 'Eventos'],
            ['nombre' => 'Reuniones'],
            ['nombre' => 'Capacitaciones']
        ]);
    }
}
