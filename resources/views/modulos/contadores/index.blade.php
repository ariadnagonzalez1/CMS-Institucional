{{-- resources/views/modulos/contadores/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Contadores Web')
@section('header-title', 'Contadores Web')

@section('content')
<div class="space-y-6">
    {{-- Tarjeta de resumen total --}}
    <div class="bg-gradient-to-r from-emerald-700 to-emerald-800 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-100 text-sm uppercase tracking-wider">Total de visitas</p>
                <p class="text-5xl font-bold mt-2">{{ number_format($totalGeneral) }}</p>
                <p class="text-emerald-100 text-sm mt-2">Desde el inicio del sitio</p>
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Nivel 1: Años --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-800">Visitas por Año</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($resumenAnual as $anio)
                <a href="{{ route('admin.contadores.index', ['anio' => $anio->anio]) }}" 
                   class="block hover:bg-gray-50 transition group">
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="text-2xl font-bold text-gray-700">{{ $anio->anio }}</span>
                            @if($anioSeleccionado == $anio->anio)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    Seleccionado
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-right">
                                <p class="text-2xl font-semibold text-gray-800">{{ number_format($anio->total_visitas) }}</p>
                                <p class="text-xs text-gray-400">visitas</p>
                            </div>
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-6 py-8 text-center text-gray-400">
                    No hay datos de visitas registradas
                </div>
            @endforelse
        </div>
    </div>

    {{-- Resto del código igual que antes --}}
    @if($anioSeleccionado && $resumenMensual->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    Visitas por Mes - {{ $anioSeleccionado }}
                </h3>
                <a href="{{ route('admin.contadores.index') }}" 
                   class="text-sm text-emerald-600 hover:text-emerald-700">
                    Ver todos los años
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($resumenMensual as $mes)
                    @php
                        $nombreMes = [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ][$mes->mes] ?? $mes->mes;
                        
                        $porcentaje = $totalGeneral > 0 ? round(($mes->total_visitas / $totalGeneral) * 100) : 0;
                    @endphp
                    <a href="{{ route('admin.contadores.index', ['anio' => $anioSeleccionado, 'mes' => $mes->mes]) }}" 
                       class="block hover:bg-gray-50 transition group">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-4">
                                    <span class="font-medium text-gray-700">{{ $nombreMes }}</span>
                                    @if($mesSeleccionado == $mes->mes)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            Seleccionado
                                        </span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-gray-800">{{ number_format($mes->total_visitas) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-12">{{ $porcentaje }}%</span>
                            </div>
                        </div>
                    </a>
                @endforeach
                <div class="px-6 py-4 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-700">TOTAL</span>
                        <span class="text-2xl font-bold text-emerald-700">{{ number_format($resumenMensual->sum('total_visitas')) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($mesSeleccionado && $detalleDias->count() > 0)
        @php
            $nombreMes = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ][$mesSeleccionado] ?? $mesSeleccionado;
        @endphp
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    Visitas por Día - {{ $nombreMes }} {{ $anioSeleccionado }}
                </h3>
                <a href="{{ route('admin.contadores.index', ['anio' => $anioSeleccionado]) }}" 
                   class="text-sm text-emerald-600 hover:text-emerald-700">
                    Volver a meses
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($detalleDias as $dia)
                    @php
                        $porcentaje = $totalMensual > 0 ? round(($dia->total / $totalMensual) * 100) : 0;
                    @endphp
                    <a href="{{ route('admin.contadores.index', ['anio' => $anioSeleccionado, 'mes' => $mesSeleccionado, 'dia' => $dia->dia]) }}" 
                       class="block hover:bg-gray-50 transition group">
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-4">
                                    <span class="font-medium text-gray-700">Día {{ $dia->dia }}</span>
                                    @if($diaSeleccionado == $dia->dia)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            Seleccionado
                                        </span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-semibold text-gray-800">{{ number_format($dia->total) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-12">{{ $porcentaje }}%</span>
                            </div>
                        </div>
                    </a>
                @endforeach
                <div class="px-6 py-4 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-700">TOTAL MENSUAL</span>
                        <span class="text-2xl font-bold text-emerald-700">{{ number_format($totalMensual) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($diaSeleccionado && $detalleHoras->count() > 0)
        @php
            $nombreMes = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ][$mesSeleccionado] ?? $mesSeleccionado;
        @endphp
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">
                    Visitas por Hora - {{ $diaSeleccionado }} de {{ $nombreMes }} {{ $anioSeleccionado }}
                </h3>
                <a href="{{ route('admin.contadores.index', ['anio' => $anioSeleccionado, 'mes' => $mesSeleccionado]) }}" 
                   class="text-sm text-emerald-600 hover:text-emerald-700">
                    Volver a días
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($detalleHoras as $hora)
                    @php
                        $porcentaje = $totalDia > 0 ? round(($hora->visitas / $totalDia) * 100) : 0;
                    @endphp
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-700">{{ $hora->hora }}:00 hs</span>
                            <div class="text-right">
                                <p class="text-xl font-semibold text-gray-800">{{ number_format($hora->visitas) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $porcentaje }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-12">{{ $porcentaje }}%</span>
                        </div>
                    </div>
                @endforeach
                <div class="px-6 py-4 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-700">TOTAL DÍA</span>
                        <span class="text-2xl font-bold text-emerald-700">{{ number_format($totalDia) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection