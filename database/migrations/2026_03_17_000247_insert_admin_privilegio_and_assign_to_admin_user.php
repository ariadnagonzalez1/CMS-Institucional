<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Insertar el privilegio admin
        $privilegioId = DB::table('privilegios')->insertGetId([
            'nombre'      => 'admin',
            'descripcion' => 'Administrador del sistema',
            'activo'      => 1,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Buscar el usuario Admin y asignarle el privilegio
        $userId = DB::table('users')->where('username', 'Admin')->value('id');

        if ($userId) {
            DB::table('usuarios_privilegios')->insert([
                'user_id'      => $userId,
                'privilegio_id' => $privilegioId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('usuarios_privilegios')
            ->whereIn('privilegio_id', function ($query) {
                $query->select('id')->from('privilegios')->where('nombre', 'admin');
            })->delete();

        DB::table('privilegios')->where('nombre', 'admin')->delete();
    }
};