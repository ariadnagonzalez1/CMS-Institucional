<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'apellido' => 'Sistema',
            'username' => 'admin',
            'dni' => '00000000',
            'email' => 'admin@cms.com',
            'password' => Hash::make('admin123'),
            'activo' => true
        ]);
    }
}
