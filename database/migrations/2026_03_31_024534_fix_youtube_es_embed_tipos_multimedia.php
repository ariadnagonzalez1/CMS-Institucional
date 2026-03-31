<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tipos_multimedia')
            ->where('nombre', 'YouTube')
            ->update(['es_embed' => 1]);
    }

    public function down(): void
    {
        DB::table('tipos_multimedia')
            ->where('nombre', 'YouTube')
            ->update(['es_embed' => 0]);
    }
};