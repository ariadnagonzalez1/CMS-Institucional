<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secciones_multimedia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->string('descripcion', 255)->nullable();
            $table->boolean('visible_en_sitio')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secciones_multimedia');
    }
};