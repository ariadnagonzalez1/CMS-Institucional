<?php

namespace App\Http\Controllers;

use App\Http\Requests\Root\StoreModuloRequest;
use App\Http\Requests\Root\UpdateModuloRequest;
use App\Http\Requests\Root\StoreModoTextoRequest;
use App\Http\Requests\Root\UpdateModoTextoRequest;
use App\Http\Requests\Root\StoreSeccionTextoRequest;
use App\Http\Requests\Root\UpdateSeccionTextoRequest;
use App\Http\Requests\Root\StoreSeccionBannerRequest;
use App\Http\Requests\Root\UpdateSeccionBannerRequest;
use App\Models\Modulo;
use App\Models\ModoTexto;
use App\Models\SeccionTexto;
use App\Models\SeccionBanner;
use App\Services\CrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RootController extends Controller
{
    public function __construct(private readonly CrudService $crud) {}

    // ─────────────────────────────────────────
    //  Variables compartidas del layout
    // ─────────────────────────────────────────
    private function layoutData(): array
    {
        $user          = Auth::user();
        $nombreUsuario = $user ? $user->name : 'Usuario';

        $modulosPrincipales = collect([
            (object)['nombre' => 'Root',                'path_home' => '/admin/root',        'descripcion' => 'Configuración general del sistema'],
            (object)['nombre' => 'Admin y Usuarios',    'path_home' => '/admin/usuarios',    'descripcion' => 'Gestión de usuarios y permisos'],
            (object)['nombre' => 'Novedades y Noticias','path_home' => '/admin/noticias',    'descripcion' => 'Publicar y administrar noticias'],
            (object)['nombre' => 'Publicidad y Banners','path_home' => '/admin/banners',     'descripcion' => 'Gestionar banners publicitarios'],
            (object)['nombre' => 'Audio/Video',          'path_home' => '/admin/multimedia',  'descripcion' => 'Contenido multimedia'],
        ]);

        $modulosSecundarios = collect([
            (object)['nombre' => 'Álbum de Fotos',        'path_home' => '/admin/albumes',    'descripcion' => 'Crear y gestionar álbumes'],
            (object)['nombre' => 'Calendario Agenda',     'path_home' => '/admin/agenda',     'descripcion' => 'Eventos y programación'],
            (object)['nombre' => 'Mi Perfil',             'path_home' => '/admin/perfil',     'descripcion' => 'Datos personales y cuenta'],
            (object)['nombre' => 'Contadores Web',        'path_home' => '/admin/contadores', 'descripcion' => 'Estadísticas y métricas'],
            (object)['nombre' => 'Trámites y Formularios','path_home' => '/admin/tramites',   'descripcion' => 'Documentos descargables'],
        ]);

        return compact('nombreUsuario', 'modulosPrincipales', 'modulosSecundarios');
    }

    // ─────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────
    public function index(): View
    {
        $modulos         = Modulo::orderBy('orden')->get();
        $modosTexto      = ModoTexto::orderBy('id')->get();
        $secciones       = SeccionTexto::with('modoTexto')->orderBy('orden')->get();
        $seccionesBanners = SeccionBanner::orderBy('nombre')->get();

        return view('modulos.root.index', array_merge(
            $this->layoutData(),
            compact('modulos', 'modosTexto', 'secciones', 'seccionesBanners')
        ));
    }

    // ─────────────────────────────────────────
    //  MÓDULOS
    // ─────────────────────────────────────────
    public function moduloStore(StoreModuloRequest $request): RedirectResponse
    {
        return $this->crud->store(
            model      : new Modulo,
            data       : $request->validated(),
            redirectTo : route('admin.root.index', ['tab' => 'modulos']),
            label      : 'módulo',
        );
    }

    public function moduloUpdate(UpdateModuloRequest $request, Modulo $modulo): RedirectResponse
    {
        return $this->crud->update(
            model      : $modulo,
            data       : $request->validated(),
            redirectTo : route('admin.root.index', ['tab' => 'modulos']),
            label      : 'módulo',
        );
    }

    public function moduloDestroy(Modulo $modulo): RedirectResponse
    {
        return $this->crud->destroy(
            model      : $modulo,
            redirectTo : route('admin.root.index', ['tab' => 'modulos']),
            label      : 'módulo',
        );
    }

    // ─────────────────────────────────────────
    //  SECCIONES DE BANNERS
    // ─────────────────────────────────────────
    public function seccionBannerStore(StoreSeccionBannerRequest $request): RedirectResponse
    {
        return $this->crud->store(
            model      : new SeccionBanner,
            data       : $request->validated(),
            redirectTo : route('admin.root.index', ['tab' => 'secciones-banners']),
            label      : 'sección de banner',
            beforeSave : function (SeccionBanner $m) use ($request) {
                if ($request->hasFile('imagen_ayuda')) {
                    $m->imagen_ayuda = $request->file('imagen_ayuda')
                        ->store('secciones_banners', 'public');
                }
            },
        );
    }

    public function seccionBannerUpdate(UpdateSeccionBannerRequest $request, SeccionBanner $seccionBanner): RedirectResponse
    {
        return $this->crud->update(
            model      : $seccionBanner,
            data       : $request->validated(),
            redirectTo : route('admin.root.index', ['tab' => 'secciones-banners']),
            label      : 'sección de banner',
            beforeSave : function (SeccionBanner $m) use ($request, $seccionBanner) {
                if ($request->hasFile('imagen_ayuda')) {
                    // Eliminar imagen anterior si existe
                    if ($seccionBanner->imagen_ayuda) {
                        Storage::disk('public')->delete($seccionBanner->imagen_ayuda);
                    }
                    $m->imagen_ayuda = $request->file('imagen_ayuda')
                        ->store('secciones_banners', 'public');
                }
            },
        );
    }

    public function seccionBannerDestroy(SeccionBanner $seccionBanner): RedirectResponse
    {
        return $this->crud->destroy(
            model        : $seccionBanner,
            redirectTo   : route('admin.root.index', ['tab' => 'secciones-banners']),
            label        : 'sección de banner',
            beforeDelete : function (SeccionBanner $m) {
                if ($m->imagen_ayuda) {
                    Storage::disk('public')->delete($m->imagen_ayuda);
                }
            },
        );
    }

    // ─────────────────────────────────────────
    //  MODOS DE TEXTO
    // ─────────────────────────────────────────
    public function modoTextoStore(StoreModoTextoRequest $request): RedirectResponse
    {
        return $this->crud->store(
            model      : new ModoTexto,
            data       : $request->validated(),
            redirectTo : route('admin.root.index', ['tab' => 'modos-texto']),
            label      : 'modo de texto',
        );
    }

    public function modoTextoUpdate(UpdateModoTextoRequest $request, ModoTexto $modoTexto): RedirectResponse
    {
        return $this->crud->update(
            model      : $modoTexto,
            data       : $request->validated(),
            redirectTo : route('admin.root.index', ['tab' => 'modos-texto']),
            label      : 'modo de texto',
        );
    }

    public function modoTextoDestroy(ModoTexto $modoTexto): RedirectResponse
    {
        return $this->crud->destroy(
            model      : $modoTexto,
            redirectTo : route('admin.root.index', ['tab' => 'modos-texto']),
            label      : 'modo de texto',
        );
    }

    // ─────────────────────────────────────────
    //  SECCIONES DE TEXTO
    // ─────────────────────────────────────────
    public function seccionStore(StoreSeccionTextoRequest $request): RedirectResponse
    {
        return $this->crud->store(
            model      : new SeccionTexto,
            data       : $request->validated(),
            redirectTo : route('admin.root.index', ['tab' => 'secciones-texto']),
            label      : 'sección',
        );
    }

    public function seccionUpdate(UpdateSeccionTextoRequest $request, SeccionTexto $seccionTexto): RedirectResponse
    {
        return $this->crud->update(
            model      : $seccionTexto,
            data       : $request->validated(),
            redirectTo : route('admin.root.index', ['tab' => 'secciones-texto']),
            label      : 'sección',
        );
    }

    public function seccionDestroy(SeccionTexto $seccionTexto): RedirectResponse
    {
        return $this->crud->destroy(
            model      : $seccionTexto,
            redirectTo : route('admin.root.index', ['tab' => 'secciones-texto']),
            label      : 'sección',
        );
    }
}