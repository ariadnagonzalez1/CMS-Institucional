<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposBannersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipos_banners')->insert([
            ['nombre' => 'Imagen'],
            ['nombre' => 'Popup'],
            ['nombre' => 'Link Externo']
        ]);
    }
}
