<?php
// app/Http/Controllers/Admin/ContadorWebController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EstadisticaResumen;
use App\Models\EstadisticaVisita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContadorWebController extends Controller
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
        $anioSeleccionado = $request->get('anio', date('Y'));
        $mesSeleccionado = $request->get('mes');
        $diaSeleccionado = $request->get('dia');
        
        $resumenAnual = EstadisticaResumen::whereNull('mes')
                                          ->orderBy('anio', 'desc')
                                          ->get();
        
        $totalGeneral = $resumenAnual->sum('total_visitas');
        
        $resumenMensual = EstadisticaResumen::where('anio', $anioSeleccionado)
                                            ->whereNotNull('mes')
                                            ->orderBy('mes')
                                            ->get();
        
        $detalleDias = collect();
        $totalMensual = 0;
        
        if ($mesSeleccionado) {
            $detalleDias = EstadisticaVisita::whereYear('fecha', $anioSeleccionado)
                                            ->whereMonth('fecha', $mesSeleccionado)
                                            ->selectRaw('DAY(fecha) as dia, SUM(visitas) as total')
                                            ->groupBy('dia')
                                            ->orderBy('dia')
                                            ->get();
            
            $totalMensual = $detalleDias->sum('total');
        }
        
        $detalleHoras = collect();
        $totalDia = 0;
        
        if ($diaSeleccionado && $mesSeleccionado) {
            $fecha = sprintf('%d-%02d-%02d', $anioSeleccionado, $mesSeleccionado, $diaSeleccionado);
            $detalleHoras = EstadisticaVisita::where('fecha', $fecha)
                                             ->orderBy('hora')
                                             ->get();
            
            $totalDia = $detalleHoras->sum('visitas');
        }
        
        $sidebarData = $this->getSidebarData();
        
        return view('modulos.contadores.index', array_merge(
            compact(
                'resumenAnual', 
                'totalGeneral', 
                'anioSeleccionado', 
                'mesSeleccionado',
                'diaSeleccionado',
                'resumenMensual',
                'detalleDias',
                'totalMensual',
                'detalleHoras',
                'totalDia'
            ),
            $sidebarData
        ));
    }
}