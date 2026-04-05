<?php
// database/seeders/EstadisticasSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadisticaVisita;
use App\Models\EstadisticaResumen;
use Carbon\Carbon;

class EstadisticasSeeder extends Seeder
{
    public function run(): void
    {
        // Generar datos de prueba desde 2020 hasta 2025
        $years = [2020, 2021, 2022, 2023, 2024, 2025];
        
        foreach ($years as $year) {
            $totalAnual = 0;
            
            // Generar datos por mes
            for ($month = 1; $month <= 12; $month++) {
                $totalMensual = 0;
                
                // Días en el mes
                $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
                
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $totalDia = 0;
                    $fecha = Carbon::create($year, $month, $day);
                    
                    // Si es fin de semana, menos visitas
                    $isWeekend = $fecha->isWeekend();
                    
                    for ($hour = 0; $hour <= 23; $hour++) {
                        // Simular visitas según la hora
                        $visitas = 0;
                        
                        // Horas pico: 9-12 y 16-19
                        if (($hour >= 9 && $hour <= 12) || ($hour >= 16 && $hour <= 19)) {
                            $visitas = $isWeekend ? rand(10, 50) : rand(50, 200);
                        } 
                        // Horas medias: 13-15 y 20-22
                        elseif (($hour >= 13 && $hour <= 15) || ($hour >= 20 && $hour <= 22)) {
                            $visitas = $isWeekend ? rand(5, 30) : rand(20, 80);
                        }
                        // Horas bajas
                        else {
                            $visitas = rand(0, 10);
                        }
                        
                        if ($visitas > 0) {
                            $totalDia += $visitas;
                            
                            EstadisticaVisita::create([
                                'fecha' => $fecha,
                                'hora' => $hour,
                                'visitas' => $visitas,
                                'visitas_unicas' => rand(round($visitas * 0.7), $visitas),
                            ]);
                        }
                    }
                    
                    $totalMensual += $totalDia;
                }
                
                $totalAnual += $totalMensual;
                
                // Crear resumen mensual
                EstadisticaResumen::create([
                    'anio' => $year,
                    'mes' => $month,
                    'total_visitas' => $totalMensual,
                    'visitas_unicas' => round($totalMensual * 0.8),
                ]);
            }
            
            // Crear resumen anual
            EstadisticaResumen::create([
                'anio' => $year,
                'mes' => null,
                'total_visitas' => $totalAnual,
                'visitas_unicas' => round($totalAnual * 0.8),
            ]);
        }
    }
}