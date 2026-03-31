<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar duplicados dejando solo el de menor id por nombre
        DB::statement('
            DELETE t1 FROM tipos_multimedia t1
            INNER JOIN tipos_multimedia t2
            WHERE t1.id > t2.id AND t1.nombre = t2.nombre
        ');

        // Corregir es_embed
        DB::table('tipos_multimedia')->where('nombre', 'YouTube')->update(['es_embed' => 1]);
        DB::table('tipos_multimedia')->where('nombre', 'MP3')->update(['es_embed' => 0]);
    }

    public function down(): void
    {
        //
    }
};