<?php
// app/Http/Controllers/admin/DescargableController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DescargableRequest;
use App\Models\Descargable;
use App\Models\SeccionDescargable;
use App\Services\CrudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DescargableController extends Controller
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
                'path_home' => '/admin/descargables',
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
        $query = Descargable::with(['seccion', 'user']);

        // Búsqueda por tema
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('tema', 'LIKE', "%{$search}%")
                  ->orWhere('comentario', 'LIKE', "%{$search}%");
        }

        // Filtro por sección
        if ($request->filled('seccion_id')) {
            $query->where('seccion_descargable_id', $request->seccion_id);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $descargables = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Para los filtros
        $secciones = SeccionDescargable::where('visible_en_sitio', true)->orderBy('orden')->orderBy('nombre')->get();

        $layoutData = $this->getLayoutData();
        
        return view('admin.descargables.index', array_merge($layoutData, compact('descargables', 'secciones')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DescargableRequest $request)
    {
        $data = $request->validated();
        
        return $this->crud->store(
            model: new Descargable(),
            data: $data,
            redirectTo: route('admin.descargables.index'),
            label: 'documento',
            beforeSave: function(Descargable $descargable) use ($request, $data) {
                $descargable->user_id = Auth::id();
                $descargable->estado = $data['estado'] ?? true;
                $descargable->total_descargas = 0;
                
                // Subir archivo
                if ($request->hasFile('archivo')) {
                    $file = $request->file('archivo');
                    $extension = $file->getClientOriginalExtension();
                    $filename = Str::uuid() . '.' . $extension;
                    $path = $file->storeAs('descargables', $filename, 'public');
                    
                    $descargable->archivo = $path;
                    $descargable->nombre_original_archivo = $file->getClientOriginalName();
                    $descargable->tipo_archivo = $extension;
                    $descargable->tamano_archivo_kb = round($file->getSize() / 1024, 2);
                }
            }
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DescargableRequest $request, Descargable $descargable)
    {
        $data = $request->validated();
        
        return $this->crud->update(
            model: $descargable,
            data: $data,
            redirectTo: route('admin.descargables.index'),
            label: 'documento',
            beforeSave: function(Descargable $descargable) use ($request, $data) {
                $descargable->estado = $data['estado'] ?? true;
                
                // Subir nuevo archivo si se proporciona
                if ($request->hasFile('archivo')) {
                    // Eliminar archivo anterior
                    if ($descargable->archivo && Storage::disk('public')->exists($descargable->archivo)) {
                        Storage::disk('public')->delete($descargable->archivo);
                    }
                    
                    $file = $request->file('archivo');
                    $extension = $file->getClientOriginalExtension();
                    $filename = Str::uuid() . '.' . $extension;
                    $path = $file->storeAs('descargables', $filename, 'public');
                    
                    $descargable->archivo = $path;
                    $descargable->nombre_original_archivo = $file->getClientOriginalName();
                    $descargable->tipo_archivo = $extension;
                    $descargable->tamano_archivo_kb = round($file->getSize() / 1024, 2);
                }
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Descargable $descargable)
    {
        // Eliminar archivo físico
        if ($descargable->archivo && Storage::disk('public')->exists($descargable->archivo)) {
            Storage::disk('public')->delete($descargable->archivo);
        }
        
        return $this->crud->destroy(
            model: $descargable,
            redirectTo: route('admin.descargables.index'),
            label: 'documento'
        );
    }

    /**
     * Download the file and increment download count
     */
    public function download(Descargable $descargable)
    {
        try {
            // Incrementar contador de descargas
            $descargable->increment('total_descargas');
            
            // Verificar si el archivo existe
            if ($descargable->archivo && Storage::disk('public')->exists($descargable->archivo)) {
                return Storage::disk('public')->download($descargable->archivo, $descargable->nombre_original_archivo);
            }
            
            return redirect()->back()->with('error', 'El archivo no existe.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al descargar el archivo.');
        }
    }

    /**
     * Toggle document status
     */
    public function toggleEstado(Descargable $descargable)
    {
        try {
            $descargable->estado = !$descargable->estado;
            $descargable->save();

            $estado = $descargable->estado ? 'activado' : 'desactivado';
            return redirect()->back()->with('success', "Documento {$estado} correctamente.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'No se pudo cambiar el estado.');
        }
    }
}