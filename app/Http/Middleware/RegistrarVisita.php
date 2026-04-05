<?php
// app/Http/Middleware/RegistrarVisita.php

namespace App\Http\Middleware;

use Closure;
use App\Models\EstadisticaVisita;
use App\Models\EstadisticaResumen;

class RegistrarVisita
{
    public function handle($request, Closure $next)
    {
        // Registrar la visita
        $registro = EstadisticaVisita::registrarVisita($request);
        
        // Actualizar resumen
        EstadisticaResumen::actualizarResumen(now());
        
        return $next($request);
    }
}