<?php
// routes/web.php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\RootController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Grupo de rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Panel de administración
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', function() {
            return redirect()->route('admin.dashboard');
        });

        // ── Módulo Root ──────────────────────────────────────────────────
        Route::prefix('root')->name('root.')->group(function () {

            // Índice (tabs: modulos | modos-texto | secciones-texto)
            Route::get('/', [RootController::class, 'index'])->name('index');

            // Módulos
            Route::post  ('/modulos',          [RootController::class, 'moduloStore'])  ->name('modulos.store');
            Route::put   ('/modulos/{modulo}',  [RootController::class, 'moduloUpdate']) ->name('modulos.update');
            Route::delete('/modulos/{modulo}',  [RootController::class, 'moduloDestroy'])->name('modulos.destroy');

            // Modos de Texto
            Route::post  ('/modos-texto',             [RootController::class, 'modoTextoStore'])  ->name('modos-texto.store');
            Route::put   ('/modos-texto/{modoTexto}', [RootController::class, 'modoTextoUpdate']) ->name('modos-texto.update');
            Route::delete('/modos-texto/{modoTexto}', [RootController::class, 'modoTextoDestroy'])->name('modos-texto.destroy');

            // Secciones de Texto
            Route::post  ('/secciones',                   [RootController::class, 'seccionStore'])  ->name('secciones.store');
            Route::put   ('/secciones/{seccionNoticia}',  [RootController::class, 'seccionUpdate']) ->name('secciones.update');
            Route::delete('/secciones/{seccionNoticia}',  [RootController::class, 'seccionDestroy'])->name('secciones.destroy');
        });
        // ────────────────────────────────────────────────────────────────
    });
});