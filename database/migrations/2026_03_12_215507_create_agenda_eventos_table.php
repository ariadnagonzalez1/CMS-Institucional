<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seccion_agenda_id')->constrained('secciones_agenda');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo', 255);
            $table->date('fecha_evento');
            $table->time('hora_evento')->nullable();
            $table->string('lugar', 255)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tipo_fijacion', 30)->default('ninguno');
            $table->string('tipo_ventana', 30)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_eventos');
    }
};