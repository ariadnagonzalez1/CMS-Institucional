<?php
// routes/web.php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\RootController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get ('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // ── Perfil ───────────────────────────────────────────────────────────────
    Route::get   ('/profile',          [ProfileController::class, 'edit'])          ->name('profile.edit');
    Route::patch ('/profile',          [ProfileController::class, 'update'])        ->name('profile.update');
    Route::patch ('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::patch ('/profile/avatar',   [ProfileController::class, 'updateAvatar'])  ->name('profile.avatar');
    Route::delete('/profile',          [ProfileController::class, 'destroy'])       ->name('profile.destroy');

    // ── Admin ────────────────────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', fn() => redirect()->route('admin.dashboard'));

        // Módulo Root
        Route::prefix('root')->name('root.')->group(function () {

            Route::get('/', [RootController::class, 'index'])->name('index');

            // Módulos
            Route::post  ('/modulos',          [RootController::class, 'moduloStore'])  ->name('modulos.store');
            Route::put   ('/modulos/{modulo}',  [RootController::class, 'moduloUpdate']) ->name('modulos.update');
            Route::delete('/modulos/{modulo}',  [RootController::class, 'moduloDestroy'])->name('modulos.destroy');

            // Secciones de Banners
            Route::post  ('/secciones-banners',                 [RootController::class, 'seccionBannerStore'])  ->name('secciones-banners.store');
            Route::post  ('/secciones-banners/{seccionBanner}', [RootController::class, 'seccionBannerUpdate']) ->name('secciones-banners.update');
            Route::delete('/secciones-banners/{seccionBanner}', [RootController::class, 'seccionBannerDestroy'])->name('secciones-banners.destroy');

            // Modos de Texto
            Route::post  ('/modos-texto',             [RootController::class, 'modoTextoStore'])  ->name('modos-texto.store');
            Route::put   ('/modos-texto/{modoTexto}', [RootController::class, 'modoTextoUpdate']) ->name('modos-texto.update');
            Route::delete('/modos-texto/{modoTexto}', [RootController::class, 'modoTextoDestroy'])->name('modos-texto.destroy');

            // Secciones de Texto
            Route::post  ('/secciones',                [RootController::class, 'seccionStore'])  ->name('secciones.store');
            Route::put   ('/secciones/{seccionTexto}', [RootController::class, 'seccionUpdate']) ->name('secciones.update');
            Route::delete('/secciones/{seccionTexto}', [RootController::class, 'seccionDestroy'])->name('secciones.destroy');
        });
    });
});