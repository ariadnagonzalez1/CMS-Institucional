<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        SalasRedaccionSeeder::class,
        ModosGrupoSeeder::class,
        PrivilegiosSeeder::class,
        IngenieriasSeeder::class,
        ModosTextoSeeder::class,
        SeccionesNoticiasSeeder::class,
        SeccionesBannersSeeder::class,
        SeccionesMultimediaSeeder::class,
        TiposMultimediaSeeder::class,
        TiposBannersSeeder::class,
        SeccionesDescargablesSeeder::class,
        SeccionesAgendaSeeder::class,
        AdminSeeder::class
    ]);
}
}
