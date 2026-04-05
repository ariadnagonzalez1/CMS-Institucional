<?php
// app/Models/EstadisticaResumen.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadisticaResumen extends Model
{
    use HasFactory;

    protected $table = 'estadisticas_resumen';

    protected $fillable = [
        'anio',
        'mes',
        'total_visitas',
        'visitas_unicas',
    ];

    protected $casts = [
        'total_visitas' => 'integer',
        'visitas_unicas' => 'integer',
    ];

    /**
     * Obtener resumen anual
     */
    public static function getResumenAnual()
    {
        return self::whereNull('mes')
                   ->orderBy('anio', 'desc')
                   ->get();
    }

    /**
     * Obtener resumen mensual por año
     */
    public static function getResumenMensual($anio)
    {
        return self::where('anio', $anio)
                   ->whereNotNull('mes')
                   ->orderBy('mes')
                   ->get();
    }

    /**
     * Actualizar resumen
     */
    public static function actualizarResumen($fecha)
    {
        $anio = $fecha->year;
        $mes = $fecha->month;
        
        // Resumen anual
        $totalAnual = EstadisticaVisita::whereYear('fecha', $anio)->sum('visitas');
        $unicasAnual = EstadisticaVisita::whereYear('fecha', $anio)->sum('visitas_unicas');
        
        self::updateOrCreate(
            ['anio' => $anio, 'mes' => null],
            ['total_visitas' => $totalAnual, 'visitas_unicas' => $unicasAnual]
        );
        
        // Resumen mensual
        $totalMensual = EstadisticaVisita::whereYear('fecha', $anio)
                                         ->whereMonth('fecha', $mes)
                                         ->sum('visitas');
        $unicasMensual = EstadisticaVisita::whereYear('fecha', $anio)
                                          ->whereMonth('fecha', $mes)
                                          ->sum('visitas_unicas');
        
        self::updateOrCreate(
            ['anio' => $anio, 'mes' => $mes],
            ['total_visitas' => $totalMensual, 'visitas_unicas' => $unicasMensual]
        );
    }
}