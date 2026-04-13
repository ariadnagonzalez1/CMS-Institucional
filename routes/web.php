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
use App\Http\Controllers\admin\NoticiaController;
use App\Http\Controllers\admin\MultimediaController;
use App\Http\Controllers\admin\ContadorWebController;
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (sin autenticación)
|--------------------------------------------------------------------------
*/
Route::name('public.')->group(function () {

    Route::get('/', [PublicController::class, 'inicio'])->name('inicio');

    Route::get('/institucional',  [PublicController::class, 'institucional'])->name('institucional');
    Route::get('/servicios',      [PublicController::class, 'servicios'])->name('servicios');
    Route::get('/novedades',      [PublicController::class, 'novedades'])->name('novedades');
    Route::get('/contacto',       [PublicController::class, 'contacto'])->name('contacto');

    // Noticia individual (slug)
    Route::get('/novedades/{slug}', [PublicController::class, 'noticia'])->name('noticia');

    // Formulario buzón empresas
    Route::post('/contacto/enviar', [PublicController::class, 'contactoEnviar'])->name('contacto.enviar');

    // Descarga de archivos
    Route::get('/descargables/{id}/descargar', [PublicController::class, 'descargableDownload'])
        ->name('descargable.download')
        ->where('id', '[0-9]+');
});

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren autenticación)
|--------------------------------------------------------------------------
*/
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

        Route::get('usuarios/exportar/excel', [AdminController::class, 'exportExcel'])
            ->name('admin.export-excel');

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

        Route::prefix('albumes/{album}')->name('albumes.')->group(function () {
            Route::post('fotos', [AlbumFotoItemController::class, 'store'])->name('fotos.store');
            Route::match(['PUT', 'PATCH'], 'fotos/{foto}', [AlbumFotoItemController::class, 'update'])->name('fotos.update');
            Route::delete('fotos/{foto}', [AlbumFotoItemController::class, 'destroy'])->name('fotos.destroy');
            Route::patch('fotos/{foto}/portada', [AlbumFotoItemController::class, 'setPortada'])->name('fotos.portada');
            Route::post('fotos/{foto}/recortar', [AlbumFotoItemController::class, 'recortar'])->name('fotos.recortar');
        });

        Route::patch('albumes/{album}/toggle-estado', [AlbumFotoController::class, 'toggleEstado'])->name('albumes.toggle-estado');
        Route::patch('albumes/{album}/toggle-visible', [AlbumFotoController::class, 'toggleVisible'])->name('albumes.toggle-visible');

        // ── Módulo Root ──────────────────────────────────────────────────
        Route::prefix('root')->name('root.')->group(function () {
            Route::get('/', [RootController::class, 'index'])->name('index');

            Route::post  ('/modulos',          [RootController::class, 'moduloStore'])  ->name('modulos.store');
            Route::put   ('/modulos/{modulo}',  [RootController::class, 'moduloUpdate']) ->name('modulos.update');
            Route::delete('/modulos/{modulo}',  [RootController::class, 'moduloDestroy'])->name('modulos.destroy');

            Route::post  ('/secciones-banners',                 [RootController::class, 'seccionBannerStore'])  ->name('secciones-banners.store');
            Route::post  ('/secciones-banners/{seccionBanner}', [RootController::class, 'seccionBannerUpdate']) ->name('secciones-banners.update');
            Route::delete('/secciones-banners/{seccionBanner}', [RootController::class, 'seccionBannerDestroy'])->name('secciones-banners.destroy');

            Route::post  ('/modos-texto',             [RootController::class, 'modoTextoStore'])  ->name('modos-texto.store');
            Route::put   ('/modos-texto/{modoTexto}', [RootController::class, 'modoTextoUpdate']) ->name('modos-texto.update');
            Route::delete('/modos-texto/{modoTexto}', [RootController::class, 'modoTextoDestroy'])->name('modos-texto.destroy');

            Route::post  ('/secciones',                [RootController::class, 'seccionStore'])  ->name('secciones.store');
            Route::put   ('/secciones/{seccionTexto}', [RootController::class, 'seccionUpdate']) ->name('secciones.update');
            Route::delete('/secciones/{seccionTexto}', [RootController::class, 'seccionDestroy'])->name('secciones.destroy');
        });

        // ── Banners ─────────────────────────────────────────────────────────
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

        // ── Noticias ─────────────────────────────────────────────────────────
        Route::prefix('noticias')->name('noticias.')->group(function () {
            Route::get('/', [NoticiaController::class, 'index'])->name('index');
            Route::get('/crear', [NoticiaController::class, 'create'])->name('create');
            Route::post('/', [NoticiaController::class, 'store'])->name('store');
            Route::get('/{noticium}', [NoticiaController::class, 'show'])->name('show');
            Route::get('/{noticium}/editar', [NoticiaController::class, 'edit'])->name('edit');
            Route::put('/{noticium}', [NoticiaController::class, 'update'])->name('update');
            Route::delete('/{noticium}', [NoticiaController::class, 'destroy'])->name('destroy');

            Route::patch('/{noticium}/toggle-destacado',      [NoticiaController::class, 'toggleDestacado'])->name('toggle-destacado');
            Route::patch('/{noticium}/toggle-superdestacado', [NoticiaController::class, 'toggleSuperDestacado'])->name('toggle-superdestacado');
            Route::patch('/{noticium}/toggle-visible',        [NoticiaController::class, 'toggleVisible'])->name('toggle-visible');
            Route::patch('/{noticium}/toggle-activa',         [NoticiaController::class, 'toggleActiva'])->name('toggle-activa');

            Route::delete('/imagen/{id}', [NoticiaController::class, 'destroyImage'])->name('destroy-image');
        });

        // ── Módulo Multimedia ────────────────────────────────────────────────
        Route::resource('multimedia', MultimediaController::class, [
            'names' => [
                'index'   => 'multimedia.index',
                'create'  => 'multimedia.create',
                'store'   => 'multimedia.store',
                'edit'    => 'multimedia.edit',
                'update'  => 'multimedia.update',
                'destroy' => 'multimedia.destroy',
            ],
            'parameters' => [
                'multimedia' => 'multimedium'
            ]
        ]);

        Route::patch('multimedia/{multimedium}/toggle-estado', [MultimediaController::class, 'toggleEstado'])
            ->name('multimedia.toggle-estado');

        // ── Módulo Contadores Web ────────────────────────────────────────────
        Route::get('contadores', [ContadorWebController::class, 'index'])->name('contadores.index');

        // ── Módulo Trámites y Formularios (Descargables) ─────────────────────
        Route::prefix('tramites-formularios')->name('descargables.')->group(function () {
            Route::get('/',                              [DescargableController::class, 'index'])        ->name('index');
            Route::post('/',                             [DescargableController::class, 'store'])        ->name('store');
            Route::put('/{descargable}',                 [DescargableController::class, 'update'])       ->name('update');
            Route::delete('/{descargable}',              [DescargableController::class, 'destroy'])      ->name('destroy');
            Route::patch('/{descargable}/toggle-activo', [DescargableController::class, 'toggleActivo'])->name('toggleActivo');
            Route::get('/{descargable}/descargar',       [DescargableController::class, 'descargar'])   ->name('descargar');
        });

    }); // ← Cierra prefix('admin')
}); // ← Cierra middleware(['auth'])