<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banners\StoreBannerRequest;
use App\Http\Requests\Banners\UpdateBannerRequest;
use App\Models\Banner;
use App\Models\SeccionBanner;
use App\Models\TipoBanner;
use App\Models\Modulo;
use App\Services\CrudService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(protected CrudService $crud) {}

    /**
     * Obtener datos comunes para el layout
     */
    private function getLayoutData()
{
    return [
        'nombreUsuario' => auth()->user()->name ?? 'Usuario',
        'modulosPrincipales' => collect([
            (object)['nombre' => 'Root', 'icono' => 'fas fa-database', 'path_home' => '/admin/root'],
            (object)['nombre' => 'Admin y Usuarios', 'icono' => 'fas fa-users-cog', 'path_home' => '/admin/usuarios'],
            (object)['nombre' => 'Novedades y Noticias', 'icono' => 'fas fa-newspaper', 'path_home' => '/admin/noticias'],
            (object)['nombre' => 'Publicidad y Banners', 'icono' => 'fas fa-ad', 'path_home' => '/admin/banners'],
            (object)['nombre' => 'Audio/Video', 'icono' => 'fas fa-video', 'path_home' => '/admin/multimedia'],
        ]),
        'modulosSecundarios' => collect([
            (object)['nombre' => 'Álbum de Fotos', 'icono' => 'fas fa-images', 'path_home' => '/admin/albumes'],
            (object)['nombre' => 'Calendario Agenda', 'icono' => 'fas fa-calendar-alt', 'path_home' => '/admin/agenda'],
            (object)['nombre' => 'Mi Perfil', 'icono' => 'fas fa-user-circle', 'path_home' => '/profile'],
            (object)['nombre' => 'Contadores Web', 'icono' => 'fas fa-chart-line', 'path_home' => '/admin/contadores'],
            (object)['nombre' => 'Trámites y Formularios', 'icono' => 'fas fa-file-alt', 'path_home' => '/admin/tramites'],
        ]),
    ];
}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::with(['seccionBanner', 'tipoBanner']);

        // Filtro por sección
        if ($request->filled('seccion')) {
            $query->where('seccion_banner_id', $request->seccion);
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Búsqueda por título o comentario
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo_epigrafe', 'like', "%{$buscar}%")
                  ->orWhere('comentario', 'like', "%{$buscar}%");
            });
        }

        // Paginación
        $porPagina = (int) $request->input('por_pagina', 10);
        $banners = $query->orderBy('orden')
                        ->orderByDesc('id')
                        ->paginate($porPagina)
                        ->withQueryString();

        // Datos para los selects
        $secciones = SeccionBanner::orderBy('nombre')->get();
        $tiposBanner = TipoBanner::where('activo', 1)->orderBy('nombre')->get();

        // Datos del layout
        $layoutData = $this->getLayoutData();

        return view('modulos.banners.index', array_merge(
            compact('banners', 'secciones', 'tiposBanner', 'porPagina'),
            $layoutData
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $secciones = SeccionBanner::orderBy('nombre')->get();
        $tiposBanner = TipoBanner::where('activo', 1)->orderBy('nombre')->get();
        
        $layoutData = $this->getLayoutData();

        return view('modulos.banners.create', array_merge(
            compact('secciones', 'tiposBanner'),
            $layoutData
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        return $this->crud->store(
            model: new Banner,
            data: $data,
            redirectTo: route('admin.banners.index'),
            label: 'banner',
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        $layoutData = $this->getLayoutData();

        return view('modulos.banners.show', array_merge(
            compact('banner'),
            $layoutData
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        $secciones = SeccionBanner::orderBy('nombre')->get();
        $tiposBanner = TipoBanner::where('activo', 1)->orderBy('nombre')->get();
        
        $layoutData = $this->getLayoutData();

        return view('modulos.banners.edit', array_merge(
            compact('banner', 'secciones', 'tiposBanner'),
            $layoutData
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        return $this->crud->update(
            model: $banner,
            data: $request->validated(),
            redirectTo: route('admin.banners.index'),
            label: 'banner',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        return $this->crud->destroy(
            model: $banner,
            redirectTo: route('admin.banners.index'),
            label: 'banner',
        );
    }

    /**
     * Toggle banner status (active/inactive)
     */
    public function toggleEstado(Banner $banner)
    {
        $banner->estado = $banner->estado === 'activo' ? 'inactivo' : 'activo';
        $banner->save();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'estado' => $banner->estado,
                'message' => 'Estado actualizado correctamente'
            ]);
        }

        return back()->with('success', 'Estado del banner actualizado.');
    }
}