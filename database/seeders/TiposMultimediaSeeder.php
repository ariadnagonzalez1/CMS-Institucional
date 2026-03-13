<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposMultimediaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipos_multimedia')->insert([
            ['nombre' => 'YouTube'],
            ['nombre' => 'MP3'],
            ['nombre' => 'MP4']
        ]);
    }
}
