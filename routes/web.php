<?php
// routes/web.php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AgendaController;
use App\Http\Controllers\admin\AlbumFotoController;
use App\Http\Controllers\admin\AlbumFotoItemController;
use App\Http\Controllers\admin\DescargableController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
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

        // ── Módulo Administradores (CRUD) ────────────────────────────────────
        Route::resource('usuarios', AdminController::class, [
            'names' => [
                'index'   => 'admin.index',
                'create'  => 'admin.create',
                'store'   => 'admin.store',
                'show'    => 'admin.show',
                'edit'    => 'admin.edit',
                'update'  => 'admin.update',
                'destroy' => 'admin.destroy',
            ],
            'parameters' => [
                'usuarios' => 'admin'
            ]
        ]);
        
        // Ruta para exportar a Excel
        Route::get('usuarios/exportar/excel', [AdminController::class, 'exportExcel'])
            ->name('admin.export-excel');
        
        // Ruta para cambiar estado (activar/desactivar)
        Route::patch('usuarios/{admin}/toggle-estado', [AdminController::class, 'toggleActivo'])
            ->name('admin.toggle-activo');
        
        // ── Módulo Agenda (Calendario) ────────────────────────────────────────
        Route::resource('agenda', AgendaController::class, [
            'names' => [
                'index'   => 'agenda.index',
                'create'  => 'agenda.create',
                'store'   => 'agenda.store',
                'edit'    => 'agenda.edit',
                'update'  => 'agenda.update',
                'destroy' => 'agenda.destroy',
            ],
            'parameters' => [
                'agenda' => 'agendum'
            ],
            'except' => ['show']
        ]);
        
        // Ruta para cambiar estado del evento (activar/desactivar)
        Route::patch('agenda/{agendum}/toggle-estado', [AgendaController::class, 'toggleEstado'])
            ->name('agenda.toggle-estado');
        
        // ── Módulo Álbumes de Fotos ──────────────────────────────────────────
        Route::resource('albumes', AlbumFotoController::class, [
            'names' => [
                'index'   => 'albumes.index',
                'create'  => 'albumes.create',
                'store'   => 'albumes.store',
                'show'    => 'albumes.show',
                'edit'    => 'albumes.edit',
                'update'  => 'albumes.update',
                'destroy' => 'albumes.destroy',
            ],
            'parameters' => [
                'albumes' => 'album'
            ]
        ]);
        
        // Rutas para gestión de fotos dentro del álbum
        Route::prefix('albumes/{album}')->name('albumes.')->group(function () {
            Route::post('fotos', [AlbumFotoItemController::class, 'store'])->name('fotos.store');
            Route::match(['PUT', 'PATCH'], 'fotos/{foto}', [AlbumFotoItemController::class, 'update'])->name('fotos.update');
            Route::delete('fotos/{foto}', [AlbumFotoItemController::class, 'destroy'])->name('fotos.destroy');
            Route::patch('fotos/{foto}/portada', [AlbumFotoItemController::class, 'setPortada'])->name('fotos.portada');
            Route::post('fotos/{foto}/recortar', [AlbumFotoItemController::class, 'recortar'])->name('fotos.recortar');
        });
        
        // Rutas adicionales para el álbum
        Route::patch('albumes/{album}/toggle-estado', [AlbumFotoController::class, 'toggleEstado'])->name('albumes.toggle-estado');
        Route::patch('albumes/{album}/toggle-visible', [AlbumFotoController::class, 'toggleVisible'])->name('albumes.toggle-visible');
        
        // ── Módulo Trámites y Formularios (Descargables) ──────────────────────
        Route::resource('tramites', DescargableController::class, [
            'names' => [
                'index'   => 'descargables.index',
                'store'   => 'descargables.store',
                'update'  => 'descargables.update',
                'destroy' => 'descargables.destroy',
            ],
            'except' => ['create', 'edit', 'show']
        ]);
        
        // Ruta para descargar archivo
        Route::get('tramites/{descargable}/download', [DescargableController::class, 'download'])
            ->name('descargables.download');
        
        // Ruta para cambiar estado (activar/desactivar)
        Route::patch('tramites/{descargable}/toggle-estado', [DescargableController::class, 'toggleEstado'])
            ->name('descargables.toggle-estado');
            
        // ── Módulo Root ──────────────────────────────────────────────────
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

        // ── Banners (Rutas amigables) ─────────────────────────────────────────
        Route::resource('banners', BannerController::class, [
            'names' => [
                'index'   => 'banners.index',
                'create'  => 'banners.create',
                'store'   => 'banners.store',
                'show'    => 'banners.show',
                'edit'    => 'banners.edit',
                'update'  => 'banners.update',
                'destroy' => 'banners.destroy',
            ]
        ]);
        
        Route::patch('banners/{banner}/toggle-estado', [BannerController::class, 'toggleEstado'])
            ->name('banners.toggle-estado');
    });
});