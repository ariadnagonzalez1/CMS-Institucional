<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('sala_redaccion_id')
                ->nullable()
                ->after('id')
                ->constrained('salas_redaccion')
                ->nullOnDelete();

            $table->foreignId('modo_grupo_id')
                ->nullable()
                ->after('sala_redaccion_id')
                ->constrained('modos_grupo')
                ->nullOnDelete();

            $table->string('dni', 20)->unique()->after('modo_grupo_id');
            $table->string('username', 50)->unique()->after('dni');
            $table->string('apellido', 100)->nullable()->after('name');
            $table->string('telefono_fijo', 30)->nullable()->after('email');
            $table->string('celular', 30)->nullable()->after('telefono_fijo');
            $table->string('avatar', 255)->nullable()->after('celular');
            $table->boolean('activo')->default(true)->after('avatar');
            $table->dateTime('ultimo_login')->nullable()->after('activo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sala_redaccion_id']);
            $table->dropForeign(['modo_grupo_id']);
            $table->dropColumn([
                'sala_redaccion_id',
                'modo_grupo_id',
                'dni',
                'username',
                'apellido',
                'telefono_fijo',
                'celular',
                'avatar',
                'activo',
                'ultimo_login',
            ]);
        });
    }
};