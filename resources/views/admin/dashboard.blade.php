{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Panel de Administración')
@section('header-title', 'Panel de Administración')

@section('content')
<div class="space-y-6">

    <!-- Subtítulo -->
    <p class="text-sm text-gray-500">Seleccioná un módulo para comenzar</p>

    <!-- Grid de módulos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($modulosPrincipales as $modulo)
            <x-module-card :module="$modulo" />
        @endforeach
        @foreach($modulosSecundarios as $modulo)
            <x-module-card :module="$modulo" />
        @endforeach
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">

        <!-- Noticias -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Noticias</p>
                <p class="text-3xl font-bold text-gray-800">24</p>
            </div>
            <div class="h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #e6f0eb;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#1e5c3a" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2" />
                </svg>
            </div>
        </div>

        <!-- Álbumes -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Álbumes</p>
                <p class="text-3xl font-bold text-gray-800">12</p>
            </div>
            <div class="h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #e6f0eb;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#1e5c3a" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <!-- Eventos -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Eventos</p>
                <p class="text-3xl font-bold text-gray-800">8</p>
            </div>
            <div class="h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #e6f0eb;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#1e5c3a" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Usuarios</p>
                <p class="text-3xl font-bold text-gray-800">15</p>
            </div>
            <div class="h-12 w-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: #e6f0eb;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#1e5c3a" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

    </div>
</div>
@endsection