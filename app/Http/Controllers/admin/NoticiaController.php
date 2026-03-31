<?php
// app/Http/Controllers/Admin/NoticiaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Noticias\StoreNoticiaRequest;
use App\Http\Requests\Noticias\UpdateNoticiaRequest;
use App\Models\Noticia;
use App\Models\NoticiaImagen;
use App\Models\SeccionNoticia;
use App\Models\ModoTexto;
use App\Services\CrudService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NoticiaController extends Controller
{
    public function __construct(protected CrudService $crud) {}

    /**
     * Obtener datos comunes para el sidebar (igual que en DashboardController)
     */
    private function getSidebarData()
    {
        // Obtener el usuario actual
        $user = Auth::user();
        $nombreUsuario = $user ? $user->name : 'Marcos Andres Ortiz';

        // Módulos principales (igual que en DashboardController)
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

        // Módulos secundarios (igual que en DashboardController)
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

        return [
            'nombreUsuario' => $nombreUsuario,
            'modulosPrincipales' => $modulosPrincipales,
            'modulosSecundarios' => $modulosSecundarios,
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Noticia::with(['seccion', 'modoTexto', 'user']);

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'like', "%{$buscar}%")
                  ->orWhere('volanta', 'like', "%{$buscar}%")
                  ->orWhere('bajada', 'like', "%{$buscar}%");
            });
        }

        // Filtro por sección
        if ($request->filled('seccion')) {
            $query->where('seccion_noticia_id', $request->seccion);
        }

        // Filtro por modo
        if ($request->filled('modo')) {
            $query->where('modo_texto_id', $request->modo);
        }

        $porPagina = (int) $request->input('por_pagina', 10);
        $noticias = $query->orderByDesc('fecha_publicacion')
                         ->orderByDesc('id')
                         ->paginate($porPagina)
                         ->withQueryString();

        $secciones = SeccionNoticia::where('visible_en_sitio', 1)
                                  ->orderBy('orden')
                                  ->get();
        
        $modosTexto = ModoTexto::where('estado', 1)
                              ->orderBy('nombre')
                              ->get();

        // Datos del sidebar
        $sidebarData = $this->getSidebarData();

        return view('modulos.noticias.index', array_merge(
            compact('noticias', 'secciones', 'modosTexto', 'porPagina'),
            $sidebarData
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todos los modos de texto activos
        $modosTexto = ModoTexto::where('estado', 1)
                              ->orderBy('nombre')
                              ->get();
        
        // Obtener todas las secciones con su relación al modo
        $secciones = SeccionNoticia::with('modoTexto')
                                  ->where('visible_en_sitio', 1)
                                  ->orderBy('orden')
                                  ->get();
        
        // Agrupar secciones por modo_texto_id
        $seccionesPorModo = [];
        foreach ($secciones as $seccion) {
            $modoId = $seccion->modo_texto_id;
            if (!isset($seccionesPorModo[$modoId])) {
                $seccionesPorModo[$modoId] = [];
            }
            $seccionesPorModo[$modoId][] = [
                'id' => $seccion->id,
                'nombre' => $seccion->nombre,
                'color_fondo' => $seccion->color_fondo,
                'color_texto' => $seccion->color_texto,
            ];
        }
        
        // Datos del sidebar
        $sidebarData = $this->getSidebarData();
        
        return view('modulos.noticias.create', array_merge(
            compact('modosTexto', 'seccionesPorModo'),
            $sidebarData
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoticiaRequest $request)
    {
        $data = $request->validated();
        
        // Generar slug único
        $data['slug'] = Str::slug($data['titulo']) . '-' . uniqid();
        $data['user_id'] = auth()->id();
        $data['visitas'] = 0;
        $data['comentarios_count'] = 0;
        
        // Manejar checkboxes
        $data['visible'] = $request->has('visible');
        $data['activa'] = $request->has('activa');
        $data['es_destacado_portada'] = $request->has('es_destacado_portada');
        $data['es_superdestacado_portada'] = $request->has('es_superdestacado_portada');
        $data['permite_comentarios'] = $request->has('permite_comentarios');
        
        // Nivel destacado (0=normal, 1=destacado, 2=superdestacado)
        if ($request->has('es_superdestacado_portada')) {
            $data['nivel_destacado'] = 2;
        } elseif ($request->has('es_destacado_portada')) {
            $data['nivel_destacado'] = 1;
        } else {
            $data['nivel_destacado'] = 0;
        }

        $noticia = Noticia::create($data);

        // Manejar imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagen) {
                $path = $imagen->store('noticias', 'public');
                
                NoticiaImagen::create([
                    'noticia_id' => $noticia->id,
                    'archivo' => $path,
                    'orden' => $index,
                    'es_principal' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.noticias.index')
                        ->with('success', 'Noticia creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Noticia $noticium)
    {
        // Obtener todos los modos de texto activos
        $modosTexto = ModoTexto::where('estado', 1)
                              ->orderBy('nombre')
                              ->get();
        
        // Obtener todas las secciones con su relación al modo
        $secciones = SeccionNoticia::with('modoTexto')
                                  ->where('visible_en_sitio', 1)
                                  ->orderBy('orden')
                                  ->get();
        
        // Agrupar secciones por modo_texto_id
        $seccionesPorModo = [];
        foreach ($secciones as $seccion) {
            $modoId = $seccion->modo_texto_id;
            if (!isset($seccionesPorModo[$modoId])) {
                $seccionesPorModo[$modoId] = [];
            }
            $seccionesPorModo[$modoId][] = [
                'id' => $seccion->id,
                'nombre' => $seccion->nombre,
                'color_fondo' => $seccion->color_fondo,
                'color_texto' => $seccion->color_texto,
            ];
        }
        
        // Datos del sidebar
        $sidebarData = $this->getSidebarData();
        
        return view('modulos.noticias.edit', array_merge(
            compact('noticium', 'modosTexto', 'seccionesPorModo'),
            $sidebarData
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoticiaRequest $request, Noticia $noticium)
    {
        $data = $request->validated();
        
        // Actualizar slug si cambió el título
        if ($noticium->titulo !== $data['titulo']) {
            $data['slug'] = Str::slug($data['titulo']) . '-' . uniqid();
        }
        
        // Manejar checkboxes
        $data['visible'] = $request->has('visible');
        $data['activa'] = $request->has('activa');
        $data['es_destacado_portada'] = $request->has('es_destacado_portada');
        $data['es_superdestacado_portada'] = $request->has('es_superdestacado_portada');
        $data['permite_comentarios'] = $request->has('permite_comentarios');
        
        // Nivel destacado
        if ($request->has('es_superdestacado_portada')) {
            $data['nivel_destacado'] = 2;
        } elseif ($request->has('es_destacado_portada')) {
            $data['nivel_destacado'] = 1;
        } else {
            $data['nivel_destacado'] = 0;
        }

        $noticium->update($data);

        // Manejar nuevas imágenes
        if ($request->hasFile('imagenes')) {
            $currentCount = $noticium->imagenes()->count();
            foreach ($request->file('imagenes') as $index => $imagen) {
                $path = $imagen->store('noticias', 'public');
                
                NoticiaImagen::create([
                    'noticia_id' => $noticium->id,
                    'archivo' => $path,
                    'orden' => $currentCount + $index,
                ]);
            }
        }

        return redirect()->route('admin.noticias.index')
                        ->with('success', 'Noticia actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Noticia $noticium)
    {
        // Eliminar imágenes asociadas
        foreach ($noticium->imagenes as $imagen) {
            Storage::disk('public')->delete($imagen->archivo);
            $imagen->delete();
        }
        
        $noticium->delete();

        return redirect()->route('admin.noticias.index')
                        ->with('success', 'Noticia eliminada exitosamente.');
    }

    /**
     * Toggle destacado en portada
     */
    public function toggleDestacado(Noticia $noticium)
    {
        $noticium->es_destacado_portada = !$noticium->es_destacado_portada;
        
        if ($noticium->es_destacado_portada) {
            $noticium->es_superdestacado_portada = false;
            $noticium->nivel_destacado = 1;
        } else {
            $noticium->nivel_destacado = 0;
        }
        
        $noticium->save();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'destacado' => $noticium->es_destacado_portada,
                'superdestacado' => $noticium->es_superdestacado_portada
            ]);
        }

        return back()->with('success', 'Estado destacado actualizado.');
    }

    /**
     * Toggle superdestacado en portada
     */
    public function toggleSuperDestacado(Noticia $noticium)
    {
        $noticium->es_superdestacado_portada = !$noticium->es_superdestacado_portada;
        
        if ($noticium->es_superdestacado_portada) {
            $noticium->es_destacado_portada = false;
            $noticium->nivel_destacado = 2;
        } else {
            $noticium->nivel_destacado = 0;
        }
        
        $noticium->save();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'destacado' => $noticium->es_destacado_portada,
                'superdestacado' => $noticium->es_superdestacado_portada
            ]);
        }

        return back()->with('success', 'Estado superdestacado actualizado.');
    }

    /**
     * Toggle visible/invisible
     */
    public function toggleVisible(Noticia $noticium)
    {
        $noticium->visible = !$noticium->visible;
        $noticium->save();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'visible' => $noticium->visible
            ]);
        }

        return back()->with('success', 'Visibilidad actualizada.');
    }

    /**
     * Toggle activa/inactiva
     */
    public function toggleActiva(Noticia $noticium)
    {
        $noticium->activa = !$noticium->activa;
        $noticium->save();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'activa' => $noticium->activa
            ]);
        }

        return back()->with('success', 'Estado activa actualizado.');
    }

    /**
     * Eliminar imagen de noticia
     */
    public function destroyImage($id)
    {
        $imagen = NoticiaImagen::findOrFail($id);
        
        Storage::disk('public')->delete($imagen->archivo);
        $imagen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Imagen eliminada'
        ]);
    }
}