<?php
// app/Http/Controllers/Admin/MultimediaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Multimedia\StoreMultimediaRequest;
use App\Http\Requests\Multimedia\UpdateMultimediaRequest;
use App\Models\Multimedia;
use App\Models\SeccionMultimedia;
use App\Models\TipoMultimedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MultimediaController extends Controller
{
    /**
     * Obtener datos comunes para el sidebar
     */
    private function getSidebarData()
    {
        $user = Auth::user();
        $nombreUsuario = $user ? $user->name : 'Marcos Andres Ortiz';

        $modulosPrincipales = collect([
            (object)['nombre' => 'Root', 'icono' => 'fas fa-database', 'path_home' => '/admin/root'],
            (object)['nombre' => 'Admin y Usuarios', 'icono' => 'fas fa-users-cog', 'path_home' => '/admin/usuarios'],
            (object)['nombre' => 'Novedades y Noticias', 'icono' => 'fas fa-newspaper', 'path_home' => '/admin/noticias'],
            (object)['nombre' => 'Publicidad y Banners', 'icono' => 'fas fa-ad', 'path_home' => '/admin/banners'],
            (object)['nombre' => 'Audio/Video', 'icono' => 'fas fa-video', 'path_home' => '/admin/multimedia'],
        ]);

        $modulosSecundarios = collect([
            (object)['nombre' => 'Álbum de Fotos', 'icono' => 'fas fa-images', 'path_home' => '/admin/albumes'],
            (object)['nombre' => 'Calendario Agenda', 'icono' => 'fas fa-calendar-alt', 'path_home' => '/admin/agenda'],
            (object)['nombre' => 'Mi Perfil', 'icono' => 'fas fa-user-circle', 'path_home' => '/profile'],
            (object)['nombre' => 'Contadores Web', 'icono' => 'fas fa-chart-line', 'path_home' => '/admin/contadores'],
            (object)['nombre' => 'Trámites y Formularios', 'icono' => 'fas fa-file-alt', 'path_home' => '/admin/tramites'],
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
        $query = Multimedia::with(['seccion', 'tipo', 'user']);

        // Filtro por búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('tema', 'like', "%{$buscar}%");
        }

        // Filtro por sección
        if ($request->filled('seccion')) {
            $query->where('seccion_multimedia_id', $request->seccion);
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo_multimedia_id', $request->tipo);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $porPagina = (int) $request->input('por_pagina', 10);
        $multimedia = $query->orderByDesc('fecha_publicacion')
                           ->orderByDesc('id')
                           ->paginate($porPagina)
                           ->withQueryString();

        $secciones = SeccionMultimedia::where('visible_en_sitio', 1)
                                      ->orderBy('nombre')
                                      ->get();
        
        $tiposMultimedia = TipoMultimedia::where('activo', 1)
                                         ->orderBy('nombre')
                                         ->get();

        $sidebarData = $this->getSidebarData();

        return view('modulos.multimedia.index', array_merge(
            compact('multimedia', 'secciones', 'tiposMultimedia', 'porPagina'),
            $sidebarData
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $secciones = SeccionMultimedia::where('visible_en_sitio', 1)
                                      ->orderBy('nombre')
                                      ->get();
        
        $tiposMultimedia = TipoMultimedia::where('activo', 1)
                                         ->orderBy('nombre')
                                         ->get();

        return view('modulos.multimedia.create', compact('secciones', 'tiposMultimedia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMultimediaRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['estado'] = $request->has('estado');
        
        $tipo = TipoMultimedia::find($data['tipo_multimedia_id']);
        
        if ($tipo->es_embed) {
            $data['codigo_embed'] = $request->codigo_embed;
            $data['archivo'] = null;
            $data['url_externa'] = null;
        } else {
            if ($request->hasFile('archivo')) {
                $file = $request->file('archivo');
                $path = $file->store('multimedia', 'public');
                $data['archivo'] = $path;
                $data['codigo_embed'] = null;
                $data['url_externa'] = null;
            }
        }

        $multimedia = Multimedia::create($data);

        // Si la petición es AJAX, responder con JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Contenido multimedia creado exitosamente.',
                'data' => $multimedia
            ]);
        }

        return redirect()->route('admin.multimedia.index')
                        ->with('success', 'Contenido multimedia creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Multimedia $multimedium)
    {
        $secciones = SeccionMultimedia::where('visible_en_sitio', 1)
                                      ->orderBy('nombre')
                                      ->get();
        
        $tiposMultimedia = TipoMultimedia::where('activo', 1)
                                         ->orderBy('nombre')
                                         ->get();

        return view('modulos.multimedia.edit', compact('multimedium', 'secciones', 'tiposMultimedia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMultimediaRequest $request, Multimedia $multimedium)
    {
        $data = $request->validated();
        $data['estado'] = $request->has('estado');
        
        $tipo = TipoMultimedia::find($data['tipo_multimedia_id']);
        
        if ($tipo->es_embed) {
            $data['codigo_embed'] = $request->codigo_embed;
            $data['archivo'] = null;
            $data['url_externa'] = null;
        } else {
            if ($request->hasFile('archivo')) {
                if ($multimedium->archivo) {
                    Storage::disk('public')->delete($multimedium->archivo);
                }
                $file = $request->file('archivo');
                $path = $file->store('multimedia', 'public');
                $data['archivo'] = $path;
                $data['codigo_embed'] = null;
                $data['url_externa'] = null;
            } else {
                $data['archivo'] = $multimedium->archivo;
            }
        }

        $multimedium->update($data);

        // Si la petición es AJAX, responder con JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Contenido multimedia actualizado exitosamente.',
                'data' => $multimedium
            ]);
        }

        return redirect()->route('admin.multimedia.index')
                        ->with('success', 'Contenido multimedia actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Multimedia $multimedium)
    {
        if ($multimedium->archivo) {
            Storage::disk('public')->delete($multimedium->archivo);
        }
        
        $multimedium->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Contenido multimedia eliminado exitosamente.'
            ]);
        }

        return redirect()->route('admin.multimedia.index')
                        ->with('success', 'Contenido multimedia eliminado exitosamente.');
    }

    /**
     * Toggle estado
     */
    public function toggleEstado(Multimedia $multimedium)
    {
        $multimedium->estado = !$multimedium->estado;
        $multimedium->save();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'estado' => $multimedium->estado
            ]);
        }

        return back()->with('success', 'Estado actualizado.');
    }
}