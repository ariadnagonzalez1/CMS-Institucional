<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionesBannersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('secciones_banners')->insert([
            ['nombre' => 'Banner Principal'],
            ['nombre' => 'Publicidad Lateral'],
            ['nombre' => 'Publicidad Inferior']
        ]);
    }
}
