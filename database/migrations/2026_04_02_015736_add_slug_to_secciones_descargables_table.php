<?php
// database/migrations/2026_04_02_015736_add_slug_to_secciones_descargables_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Verificar si la columna slug ya existe
        if (!Schema::hasColumn('secciones_descargables', 'slug')) {
            // Agregar la columna slug como nullable
            Schema::table('secciones_descargables', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('nombre');
            });
        }

        // Generar slugs para los registros que no tienen slug
        $secciones = DB::table('secciones_descargables')->whereNull('slug')->get();
        foreach ($secciones as $seccion) {
            $slug = Str::slug($seccion->nombre);
            $originalSlug = $slug;
            $counter = 1;
            while (DB::table('secciones_descargables')->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            DB::table('secciones_descargables')
                ->where('id', $seccion->id)
                ->update(['slug' => $slug]);
        }

        // Verificar si la restricción unique existe
        $tableName = 'secciones_descargables';
        $indexName = 'secciones_descargables_slug_unique';
        
        $indexExists = DB::select("SHOW INDEX FROM {$tableName} WHERE Key_name = '{$indexName}'");
        
        if (empty($indexExists)) {
            // Agregar la restricción unique
            Schema::table('secciones_descargables', function (Blueprint $table) {
                $table->unique('slug');
            });
        }
    }

    public function down(): void
    {
        // No eliminar la columna para evitar pérdida de datos
        // Solo eliminar la restricción unique si existe
        Schema::table('
        secciones_descargables', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });
    }
};