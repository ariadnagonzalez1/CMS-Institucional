<?php
// database/migrations/2026_04_01_000001_create_autoridades_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autoridades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cargo'); // consejo_directivo, tribunal_fiscalizador, tribunal_etica
            $table->string('cargo_nombre'); // Presidente, Vicepresidente, Secretario, etc.
            $table->integer('orden')->default(0);
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autoridades');
    }
};