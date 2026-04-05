<?php
// app/Models/EstadisticaVisita.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstadisticaVisita extends Model
{
    use HasFactory;

    protected $table = 'estadisticas_visitas';

    protected $fillable = [
        'fecha',
        'hora',
        'visitas',
        'visitas_unicas',
    ];

    protected $casts = [
        'fecha' => 'date',
        'visitas' => 'integer',
        'visitas_unicas' => 'integer',
    ];

    /**
     * Registrar una visita
     */
    public static function registrarVisita($request)
    {
        $fecha = now()->format('Y-m-d');
        $hora = now()->hour;
        
        $registro = self::firstOrCreate([
            'fecha' => $fecha,
            'hora' => $hora,
        ], [
            'visitas' => 0,
            'visitas_unicas' => 0,
        ]);
        
        $registro->increment('visitas');
        
        if (!$request->session()->has('visitante_id')) {
            $request->session()->put('visitante_id', uniqid());
            $registro->increment('visitas_unicas');
        }
        
        return $registro;
    }
}