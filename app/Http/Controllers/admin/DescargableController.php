<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Descargable;
use App\Models\SeccionDescargable;
use App\Services\CrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DescargableController extends Controller
{
    public function __construct(private CrudService $crud) {}

    // ── Variables compartidas del layout ─────────────────────────────────────

    private function layoutVars(): array
    {
        $user          = Auth::user();
        $nombreUsuario = $user?->name ?? 'Administrador';

        $modulosPrincipales = collect([
            (object)['nombre' => 'Root',                 'icono' => 'fas fa-database',    'path_home' => '/admin/root',       'descripcion' => 'Configuración general del sistema'],
            (object)['nombre' => 'Admin y Usuarios',     'icono' => 'fas fa-users-cog',   'path_home' => '/admin/usuarios',   'descripcion' => 'Gestión de usuarios y permisos'],
            (object)['nombre' => 'Novedades y Noticias', 'icono' => 'fas fa-newspaper',   'path_home' => '/admin/noticias',   'descripcion' => 'Publicar y administrar noticias'],
            (object)['nombre' => 'Publicidad y Banners', 'icono' => 'fas fa-ad',          'path_home' => '/admin/banners',    'descripcion' => 'Gestionar banners publicitarios'],
            (object)['nombre' => 'Audio/Video',          'icono' => 'fas fa-video',       'path_home' => '/admin/multimedia', 'descripcion' => 'Contenido multimedia'],
        ]);

        $modulosSecundarios = collect([
            (object)['nombre' => 'Álbum de Fotos',        'icono' => 'fas fa-images',       'path_home' => '/admin/albumes',              'descripcion' => 'Crear y gestionar álbumes'],
            (object)['nombre' => 'Calendario Agenda',     'icono' => 'fas fa-calendar-alt', 'path_home' => '/admin/agenda',               'descripcion' => 'Eventos y programación'],
            (object)['nombre' => 'Mi Perfil',             'icono' => 'fas fa-user-circle',  'path_home' => '/profile',                    'descripcion' => 'Datos personales y cuenta'],
            (object)['nombre' => 'Contadores Web',        'icono' => 'fas fa-chart-line',   'path_home' => '/admin/contadores',           'descripcion' => 'Estadísticas y métricas'],
            (object)['nombre' => 'Trámites y Formularios','icono' => 'fas fa-file-alt',     'path_home' => '/admin/tramites-formularios', 'descripcion' => 'Documentos descargables'],
        ]);

        return compact('nombreUsuario', 'modulosPrincipales', 'modulosSecundarios');
    }

    // ── Index ────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Descargable::with('seccion')
            ->latest('fecha_publicacion');

        if ($seccionId = $request->input('seccion_id')) {
            $query->where('seccion_descargable_id', $seccionId);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado') === 'activo');
        }

        if ($tema = $request->input('tema')) {
            $query->where('tema', 'like', "%{$tema}%");
        }

        $descargables = $query->paginate(20)->withQueryString();
        $secciones    = SeccionDescargable::orderBy('orden')->get();

        return view('admin.descargables.index', array_merge(
            compact('descargables', 'secciones'),
            $this->layoutVars()
        ));
    }

    // ── Store ────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'seccion_descargable_id' => 'required|exists:secciones_descargables,id',
            'fecha_publicacion'      => 'required|date',
            'tema'                   => 'required|string|max:255',
            'comentario'             => 'nullable|string|max:1000',
            'archivo'                => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:10240',
        ]);

        return $this->crud->store(
            model      : new Descargable,
            data       : $data,
            redirectTo : route('admin.descargables.index'),
            label      : 'documento',
            beforeSave : function (Descargable $m) use ($request) {
                $file = $request->file('archivo');
                $path = $file->store('descargables', 'public');

                $m->archivo               = $path;
                $m->nombre_original_archivo = $file->getClientOriginalName();
                $m->tipo_archivo          = $file->getClientOriginalExtension();
                $m->tamano_archivo_kb     = (int) round($file->getSize() / 1024);
                $m->estado                = true;
                $m->visible               = true;
                $m->total_descargas       = 0;
                $m->user_id               = Auth::id();
            },
        );
    }

    // ── Update ───────────────────────────────────────────────────────────────

    public function update(Request $request, Descargable $descargable): RedirectResponse
    {
        $data = $request->validate([
            'seccion_descargable_id' => 'required|exists:secciones_descargables,id',
            'fecha_publicacion'      => 'required|date',
            'tema'                   => 'required|string|max:255',
            'comentario'             => 'nullable|string|max:1000',
            'archivo'                => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:10240',
        ]);

        return $this->crud->update(
            model      : $descargable,
            data       : $data,
            redirectTo : route('admin.descargables.index'),
            label      : 'documento',
            beforeSave : function (Descargable $m) use ($request) {
                if ($request->hasFile('archivo')) {
                    if ($m->archivo && Storage::disk('public')->exists($m->archivo)) {
                        Storage::disk('public')->delete($m->archivo);
                    }
                    $file = $request->file('archivo');
                    $path = $file->store('descargables', 'public');

                    $m->archivo               = $path;
                    $m->nombre_original_archivo = $file->getClientOriginalName();
                    $m->tipo_archivo          = $file->getClientOriginalExtension();
                    $m->tamano_archivo_kb     = (int) round($file->getSize() / 1024);
                }
            },
        );
    }

    // ── Toggle estado ─────────────────────────────────────────────────────────

    public function toggleActivo(Descargable $descargable): RedirectResponse
    {
        return $this->crud->update(
            model      : $descargable,
            data       : ['estado' => ! $descargable->estado],
            redirectTo : route('admin.descargables.index'),
            label      : 'documento',
        );
    }

    // ── Destroy ──────────────────────────────────────────────────────────────

    public function destroy(Descargable $descargable): RedirectResponse
    {
        return $this->crud->destroy(
            model        : $descargable,
            redirectTo   : route('admin.descargables.index'),
            label        : 'documento',
            beforeDelete : function (Descargable $m) {
                if ($m->archivo && Storage::disk('public')->exists($m->archivo)) {
                    Storage::disk('public')->delete($m->archivo);
                }
            },
        );
    }

    // ── Descargar (incrementa contador) ──────────────────────────────────────

    public function descargar(Descargable $descargable)
    {
        abort_unless($descargable->estado, 404);

        $descargable->increment('total_descargas');

        return Storage::disk('public')->download(
            $descargable->archivo,
            $descargable->nombre_original_archivo,
        );
    }
}