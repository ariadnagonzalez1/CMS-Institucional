<?php
// app/Http/Controllers/admin/AgendaController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AgendaEventoRequest;
use App\Models\AgendaEvento;
use App\Models\SeccionAgenda;
use App\Services\CrudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    protected CrudService $crud;

    public function __construct(CrudService $crud)
    {
        $this->crud = $crud;
    }

    /**
     * Método privado para obtener los datos del usuario y módulos
     */
    private function getLayoutData()
    {
        $user = Auth::user();
        $nombreUsuario = $user ? ($user->name . ' ' . ($user->apellido ?? '')) : 'Administrador';

        $modulosPrincipales = collect([
            (object)[
                'nombre' => 'Root',
                'icono' => 'fas fa-database',
                'path_home' => '/admin/root',
                'descripcion' => 'Configuración general del sistema'
            ],
            (object)[
                'nombre' => 'Admin y Usuarios',
                'icono' => 'fas fa-users-cog',
                'path_home' => '/admin/usuarios',
                'descripcion' => 'Gestión de usuarios y permisos'
            ],
            (object)[
                'nombre' => 'Novedades y Noticias',
                'icono' => 'fas fa-newspaper',
                'path_home' => '/admin/noticias',
                'descripcion' => 'Publicar y administrar noticias'
            ],
            (object)[
                'nombre' => 'Publicidad y Banners',
                'icono' => 'fas fa-ad',
                'path_home' => '/admin/banners',
                'descripcion' => 'Gestionar banners publicitarios'
            ],
            (object)[
                'nombre' => 'Audio/Video',
                'icono' => 'fas fa-video',
                'path_home' => '/admin/multimedia',
                'descripcion' => 'Contenido multimedia'
            ],
        ]);

        $modulosSecundarios = collect([
            (object)[
                'nombre' => 'Álbum de Fotos',
                'icono' => 'fas fa-images',
                'path_home' => '/admin/albumes',
                'descripcion' => 'Crear y gestionar álbumes'
            ],
            (object)[
                'nombre' => 'Calendario Agenda',
                'icono' => 'fas fa-calendar-alt',
                'path_home' => '/admin/agenda',
                'descripcion' => 'Eventos y programación'
            ],
            (object)[
                'nombre' => 'Mi Perfil',
                'icono' => 'fas fa-user-circle',
                'path_home' => '/profile',
                'descripcion' => 'Datos personales y cuenta'
            ],
            (object)[
                'nombre' => 'Contadores Web',
                'icono' => 'fas fa-chart-line',
                'path_home' => '/admin/contadores',
                'descripcion' => 'Estadísticas y métricas'
            ],
            (object)[
                'nombre' => 'Trámites y Formularios',
                'icono' => 'fas fa-file-alt',
                'path_home' => '/admin/tramites',
                'descripcion' => 'Documentos descargables'
            ],
        ]);

        return compact('nombreUsuario', 'modulosPrincipales', 'modulosSecundarios');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AgendaEvento::with(['seccion', 'user']);

        // Búsqueda por título
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('titulo', 'LIKE', "%{$search}%")
                  ->orWhere('lugar', 'LIKE', "%{$search}%");
        }

        // Filtro por sección
        if ($request->filled('seccion_id')) {
            $query->where('seccion_agenda_id', $request->seccion_id);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_evento', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_evento', '<=', $request->fecha_hasta);
        }

        // Filtro por tipo de fijación
        if ($request->filled('tipo_fijacion')) {
            $query->where('tipo_fijacion', $request->tipo_fijacion);
        }

        $eventos = $query->orderBy('fecha_evento', 'desc')
                         ->orderBy('hora_evento', 'asc')
                         ->paginate(10);
        
        // Para los filtros
        $secciones = SeccionAgenda::where('visible_en_sitio', true)->orderBy('nombre')->get();
        $tiposFijacion = [
            'ninguno' => 'Ninguno',
            'destacado' => 'Destacado',
            'superdestacado' => 'Super Destacado'
        ];

        $layoutData = $this->getLayoutData();
        
        return view('admin.agenda.index', array_merge($layoutData, compact('eventos', 'secciones', 'tiposFijacion')));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $secciones = SeccionAgenda::where('visible_en_sitio', true)->orderBy('nombre')->get();
        $tiposFijacion = [
            'ninguno' => 'Ninguno',
            'destacado' => 'Destacado',
            'superdestacado' => 'Super Destacado'
        ];
        $tiposVentana = [
            '_self' => 'Misma ventana',
            '_blank' => 'Nueva ventana',
        ];

        $layoutData = $this->getLayoutData();
        
        return view('admin.agenda.create', array_merge($layoutData, compact('secciones', 'tiposFijacion', 'tiposVentana')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgendaEventoRequest $request)
    {
        $data = $request->validated();
        
        return $this->crud->store(
            model: new AgendaEvento(),
            data: $data,
            redirectTo: route('admin.agenda.index'),
            label: 'evento',
            beforeSave: function(AgendaEvento $evento) use ($data) {
                $evento->user_id = Auth::id();
                $evento->estado = $data['estado'] ?? true;
            }
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AgendaEvento $agendum)
    {
        $secciones = SeccionAgenda::where('visible_en_sitio', true)->orderBy('nombre')->get();
        $tiposFijacion = [
            'ninguno' => 'Ninguno',
            'destacado' => 'Destacado',
            'superdestacado' => 'Super Destacado'
        ];
        $tiposVentana = [
            '_self' => 'Misma ventana',
            '_blank' => 'Nueva ventana',
        ];

        $layoutData = $this->getLayoutData();
        
        return view('admin.agenda.edit', array_merge($layoutData, compact('agendum', 'secciones', 'tiposFijacion', 'tiposVentana')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AgendaEventoRequest $request, AgendaEvento $agendum)
    {
        $data = $request->validated();
        
        return $this->crud->update(
            model: $agendum,
            data: $data,
            redirectTo: route('admin.agenda.index'),
            label: 'evento'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AgendaEvento $agendum)
    {
        return $this->crud->destroy(
            model: $agendum,
            redirectTo: route('admin.agenda.index'),
            label: 'evento'
        );
    }

    /**
     * Toggle event status
     */
    public function toggleEstado(AgendaEvento $agendum)
    {
        try {
            $agendum->estado = !$agendum->estado;
            $agendum->save();

            $estado = $agendum->estado ? 'activado' : 'desactivado';
            return redirect()->back()->with('success', "Evento {$estado} correctamente.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'No se pudo cambiar el estado.');
        }
    }
}