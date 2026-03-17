<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Registrar componentes Blade
        Blade::component('sidebar', \App\View\Components\Sidebar::class);
        Blade::component('module-card', \App\View\Components\ModuleCard::class);
    }
}