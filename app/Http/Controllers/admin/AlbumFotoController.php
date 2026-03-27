<?php
// app/Http/Controllers/admin/AlbumFotoController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AlbumFotoRequest;
use App\Models\AlbumFoto;
use App\Models\AlbumFotoItem;
use App\Services\CrudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlbumFotoController extends Controller
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
        $query = AlbumFoto::with(['user', 'fotos']);

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por visibilidad
        if ($request->filled('visible')) {
            $query->where('visible', $request->visible);
        }

        $albumes = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Contar fotos por álbum
        foreach ($albumes as $album) {
            $album->total_fotos = $album->fotos()->count();
            // Obtener la foto de portada
            $album->portada = $album->fotos()->where('es_portada', true)->first();
        }

        $layoutData = $this->getLayoutData();
        
        return view('admin.albumes.index', array_merge($layoutData, compact('albumes')));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $layoutData = $this->getLayoutData();
        
        return view('admin.albumes.create', array_merge($layoutData));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlbumFotoRequest $request)
    {
        $data = $request->validated();
        
        return $this->crud->store(
            model: new AlbumFoto(),
            data: $data,
            redirectTo: route('admin.albumes.index'),
            label: 'álbum',
            beforeSave: function(AlbumFoto $album) use ($data) {
                $album->user_id = Auth::id();
                $album->visible = $data['visible'] ?? true;
                $album->estado = $data['estado'] ?? true;
            },
            afterSave: function(AlbumFoto $album) use ($request) {
                // Subir fotos si se enviaron
                if ($request->hasFile('fotos')) {
                    $this->subirFotos($album, $request->file('fotos'));
                }
            }
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AlbumFoto $album)
    {
        $layoutData = $this->getLayoutData();
        
        return view('admin.albumes.edit', array_merge($layoutData, compact('album')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlbumFotoRequest $request, AlbumFoto $album)
    {
        $data = $request->validated();
        
        return $this->crud->update(
            model: $album,
            data: $data,
            redirectTo: route('admin.albumes.index'),
            label: 'álbum',
            afterSave: function(AlbumFoto $album) use ($request) {
                // Subir fotos nuevas si se enviaron
                if ($request->hasFile('fotos')) {
                    $this->subirFotos($album, $request->file('fotos'));
                }
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AlbumFoto $album)
    {
        // Eliminar todas las fotos del álbum del storage
        foreach ($album->fotos as $foto) {
            if ($foto->archivo && Storage::disk('public')->exists($foto->archivo)) {
                Storage::disk('public')->delete($foto->archivo);
            }
        }
        
        return $this->crud->destroy(
            model: $album,
            redirectTo: route('admin.albumes.index'),
            label: 'álbum'
        );
    }

    /**
     * Toggle album status
     */
    public function toggleEstado(AlbumFoto $album)
    {
        try {
            $album->estado = !$album->estado;
            $album->save();

            $estado = $album->estado ? 'activado' : 'desactivado';
            return redirect()->back()->with('success', "Álbum {$estado} correctamente.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'No se pudo cambiar el estado.');
        }
    }

    /**
     * Toggle album visibility
     */
    public function toggleVisible(AlbumFoto $album)
    {
        try {
            $album->visible = !$album->visible;
            $album->save();

            $estado = $album->visible ? 'visible' : 'oculto';
            return redirect()->back()->with('success', "Álbum ahora está {$estado}.");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'No se pudo cambiar la visibilidad.');
        }
    }

    /**
     * Subir múltiples fotos al álbum
     */
    private function subirFotos(AlbumFoto $album, $fotos)
    {
        $orden = $album->fotos()->count();
        
        foreach ($fotos as $file) {
            try {
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = $file->storeAs('albumes/' . $album->id, $filename, 'public');
                
                // Obtener dimensiones
                $imageInfo = getimagesize($file->getRealPath());
                $ancho = $imageInfo[0] ?? null;
                $alto = $imageInfo[1] ?? null;
                
                // Crear la foto
                $foto = new AlbumFotoItem();
                $foto->album_id = $album->id;
                $foto->archivo = $path;
                $foto->nombre_archivo = $filename;
                $foto->orden = $orden++;
                $foto->ancho = $ancho;
                $foto->alto = $alto;
                $foto->save();
                
                // Si es la primera foto, establecer como portada
                if ($orden === 1) {
                    $foto->es_portada = true;
                    $foto->save();
                }
            } catch (\Exception $e) {
                // Log error but continue with other photos
                \Log::error('Error al subir foto: ' . $e->getMessage());
            }
        }
    }
    public function show(AlbumFoto $album)
{
    // Cargar todas las fotos del álbum ordenadas
    $album->load(['user', 'fotos']);
    $fotos = $album->fotos()->orderBy('orden')->orderBy('id')->get();
    
    $layoutData = $this->getLayoutData();
    
    return view('admin.albumes.show', array_merge($layoutData, compact('album', 'fotos')));
}
}