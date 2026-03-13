<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('album_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('albumes_fotos')->cascadeOnDelete();
            $table->string('archivo', 255);
            $table->string('nombre_archivo', 255)->nullable();
            $table->string('epigrafe', 255)->nullable();
            $table->boolean('es_foto_epigrafe')->default(false);
            $table->boolean('es_portada')->default(false);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->unsignedSmallInteger('ancho')->nullable();
            $table->unsignedSmallInteger('alto')->nullable();
            $table->boolean('recortada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('album_fotos');
    }
};