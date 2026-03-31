<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposMultimediaSeeder extends Seeder
{
    public function run(): void
{
    DB::table('tipos_multimedia')->insert([
        [
            'nombre'      => 'YouTube',
            'descripcion' => 'Video embebido desde YouTube',
            'extension'   => null,
            'es_embed'    => 1,
            'activo'      => 1,
        ],
        [
            'nombre'      => 'MP3',
            'descripcion' => 'Archivo de audio',
            'extension'   => 'mp3',
            'es_embed'    => 0,
            'activo'      => 1,
        ],
        
    ]);
}
}
