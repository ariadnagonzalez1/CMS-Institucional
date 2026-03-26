<?php
// app/Http/Controllers/admin/AdminController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\ModoGrupo;
use App\Models\Privilegio;
use App\Models\SalaRedaccion;
use App\Models\User;
use App\Services\CrudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exports\AdministradoresExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
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
        $query = User::with(['salaRedaccion', 'modoGrupo', 'privilegios']);

        // Búsqueda por nombre, email o DNI
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('apellido', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('dni', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por rol (privilegio)
        if ($request->filled('privilegio_id')) {
            $query->whereHas('privilegios', function($q) use ($request) {
                $q->where('privilegio_id', $request->privilegio_id);
            });
        }

        $administradores = $query->orderBy('name')->paginate(10);
        
        // Para el filtro de roles
        $privilegios = Privilegio::where('activo', true)->orderBy('nombre')->get();

        // Combinar datos del layout con los datos específicos
        $layoutData = $this->getLayoutData();
        
        return view('admin.admin.index', array_merge($layoutData, compact('administradores', 'privilegios')));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salasRedaccion = SalaRedaccion::where('activa', true)->orderBy('nombre')->get();
        $modosGrupo = ModoGrupo::where('activo', true)->orderBy('nombre')->get();
        $privilegios = Privilegio::where('activo', true)->orderBy('nombre')->get();

        $layoutData = $this->getLayoutData();
        
        return view('admin.admin.create', array_merge($layoutData, compact('salasRedaccion', 'modosGrupo', 'privilegios')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $request)
    {
        $data = $request->validated();
        
        return $this->crud->store(
            model: new User(),
            data: $data,
            redirectTo: route('admin.admin.index'),
            label: 'administrador',
            beforeSave: function(User $user) use ($data) {
                $user->name = $data['nombre'];
                $user->apellido = $data['apellido'] ?? null;
                $user->username = $data['dni'];
                $user->dni = $data['dni'];
                $user->email = $data['email'];
                $user->celular = $data['celular'] ?? null;
                $user->telefono_fijo = $data['telefono_fijo'] ?? null;
                $user->sala_redaccion_id = $data['sala_redaccion_id'];
                $user->modo_grupo_id = $data['modo_grupo_id'];
                $user->password = Hash::make($data['password']);
                $user->activo = $data['activo'] ?? true;
            },
            afterSave: function(User $user) use ($data) {
                // Sincronizar privilegios
                $user->privilegios()->sync($data['privilegios']);
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $admin)
    {
        // Cargar relaciones
        $admin->load(['salaRedaccion', 'modoGrupo', 'privilegios']);
        
        $layoutData = $this->getLayoutData();
        
        return view('admin.admin.show', array_merge($layoutData, compact('admin')));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        $salasRedaccion = SalaRedaccion::where('activa', true)->orderBy('nombre')->get();
        $modosGrupo = ModoGrupo::where('activo', true)->orderBy('nombre')->get();
        $privilegios = Privilegio::where('activo', true)->orderBy('nombre')->get();
        
        // Cargar los privilegios actuales del usuario
        $admin->load('privilegios');
        $privilegiosSeleccionados = $admin->privilegios->pluck('id')->toArray();

        $layoutData = $this->getLayoutData();
        
        return view('admin.admin.edit', array_merge($layoutData, compact('admin', 'salasRedaccion', 'modosGrupo', 'privilegios', 'privilegiosSeleccionados')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminRequest $request, User $admin)
    {
        $data = $request->validated();
        
        return $this->crud->update(
            model: $admin,
            data: $data,
            redirectTo: route('admin.admin.index'),
            label: 'administrador',
            beforeSave: function(User $user) use ($data) {
                $user->name = $data['nombre'];
                $user->apellido = $data['apellido'] ?? null;
                $user->username = $data['dni'];
                $user->dni = $data['dni'];
                $user->email = $data['email'];
                $user->celular = $data['celular'] ?? null;
                $user->telefono_fijo = $data['telefono_fijo'] ?? null;
                $user->sala_redaccion_id = $data['sala_redaccion_id'];
                $user->modo_grupo_id = $data['modo_grupo_id'];
                $user->activo = $data['activo'] ?? true;
                
                // Solo actualizar contraseña si se envía una nueva
                if (!empty($data['password'])) {
                    $user->password = Hash::make($data['password']);
                }
            },
            afterSave: function(User $user) use ($data) {
                $user->privilegios()->sync($data['privilegios']);
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        // Prevenir eliminar el propio usuario
        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.admin.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        return $this->crud->destroy(
            model: $admin,
            redirectTo: route('admin.admin.index'),
            label: 'administrador',
            beforeDelete: function(User $user) {
                // Desasociar privilegios antes de eliminar
                $user->privilegios()->detach();
            }
        );
    }

    /**
     * Toggle user active status
     */
    public function toggleActivo(User $admin)
    {
        try {
            DB::beginTransaction();
            $admin->activo = !$admin->activo;
            $admin->save();
            DB::commit();

            $estado = $admin->activo ? 'activado' : 'desactivado';
            return redirect()->back()->with('success', "Administrador {$estado} correctamente.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'No se pudo cambiar el estado.');
        }
    }

    /**
     * Exportar administradores a Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $export = new AdministradoresExport($request);
            return Excel::download($export, 'administradores_' . date('Y-m-d_His') . '.xlsx');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }
}