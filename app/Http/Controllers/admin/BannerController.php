<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banners\StoreBannerRequest;
use App\Http\Requests\Banners\UpdateBannerRequest;
use App\Models\Banner;
use App\Models\SeccionBanner;
use App\Models\TipoBanner;
use App\Services\CrudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function __construct(protected CrudService $crud) {}

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

    public function index(Request $request)
    {
        $query = Banner::with(['seccion', 'tipo']); // ✅ Usar 'seccion' y 'tipo'

        if ($request->filled('seccion')) {
            $query->where('seccion_banner_id', $request->seccion);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo_epigrafe', 'like', "%{$buscar}%")
                  ->orWhere('comentario', 'like', "%{$buscar}%");
            });
        }

        $porPagina = (int) $request->input('por_pagina', 10);
        $banners = $query->orderBy('orden')
                        ->orderByDesc('id')
                        ->paginate($porPagina)
                        ->withQueryString();

        $secciones = SeccionBanner::orderBy('nombre')->get();
        $tiposBanner = TipoBanner::where('activo', 1)->orderBy('nombre')->get();
        $layoutData = $this->getLayoutData();

        return view('modulos.banners.index', array_merge(
            compact('banners', 'secciones', 'tiposBanner', 'porPagina'),
            $layoutData
        ));
    }

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

    public function store(StoreBannerRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            
            // Debug: Verificar datos
            Log::info('Datos validados para banner:', $data);
            
            // Verificar que la sección existe
            $seccion = SeccionBanner::find($data['seccion_banner_id']);
            if (!$seccion) {
                throw new \Exception("La sección con ID {$data['seccion_banner_id']} no existe en la tabla secciones_banners");
            }
            
            // Verificar que el tipo banner existe (si se proporcionó)
            if (!empty($data['tipo_banner_id'])) {
                $tipo = TipoBanner::find($data['tipo_banner_id']);
                if (!$tipo) {
                    throw new \Exception("El tipo banner con ID {$data['tipo_banner_id']} no existe en la tabla tipos_banners");
                }
            }
            
            // Intentar guardar directamente para probar
            $banner = Banner::create($data);
            
            DB::commit();
            
            Log::info('Banner guardado exitosamente', ['id' => $banner->id]);
            
            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner creado correctamente.');
                
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Error de base de datos:', [
                'error' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A',
                'bindings' => $e->getBindings() ?? []
            ]);
            
            return back()
                ->with('error', 'Error de base de datos: ' . $e->getMessage())
                ->withInput();
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar banner:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Banner $banner)
    {
        $banner->load(['seccion', 'tipo', 'user']);
        $layoutData = $this->getLayoutData();

        return view('modulos.banners.show', array_merge(
            compact('banner'),
            $layoutData
        ));
    }

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

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            $banner->update($data);
            
            DB::commit();
            
            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner actualizado correctamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar banner:', ['error' => $e->getMessage()]);
            
            return back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            $banner->delete();
            
            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner eliminado correctamente.');
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar banner:', ['error' => $e->getMessage()]);
            
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function toggleEstado(Banner $banner)
    {
        try {
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
            
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado:', ['error' => $e->getMessage()]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el estado'
                ], 500);
            }
            
            return back()->with('error', 'Error al actualizar el estado.');
        }
    }
}