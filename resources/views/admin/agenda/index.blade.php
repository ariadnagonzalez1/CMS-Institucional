{{-- resources/views/admin/agenda/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Agenda de Eventos')

@section('header-title', 'Agenda de Eventos')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    {{-- Header con acciones --}}
    <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Buscador --}}
            <form method="GET" action="{{ route('admin.agenda.index') }}" class="flex">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por título o lugar..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-80 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent">
                </div>
                <button type="submit" class="ml-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Buscar
                </button>
            </form>

            {{-- Filtro por sección --}}
            <form method="GET" action="{{ route('admin.agenda.index') }}" class="flex items-center gap-2">
                <select name="seccion_id" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <option value="">Todas las secciones</option>
                    @foreach($secciones as $seccion)
                        <option value="{{ $seccion->id }}" {{ request('seccion_id') == $seccion->id ? 'selected' : '' }}>
                            {{ $seccion->nombre }}
                        </option>
                    @endforeach
                </select>
            </form>

            {{-- Filtro por tipo de fijación --}}
            <form method="GET" action="{{ route('admin.agenda.index') }}" class="flex items-center gap-2">
                <select name="tipo_fijacion" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <option value="">Todos los tipos</option>
                    @foreach($tiposFijacion as $key => $tipo)
                        <option value="{{ $key }}" {{ request('tipo_fijacion') == $key ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if(request('search') || request('seccion_id') || request('tipo_fijacion') || request('fecha_desde') || request('fecha_hasta'))
                <a href="{{ route('admin.agenda.index') }}" class="text-red-500 hover:text-red-700 text-sm">
                    Limpiar filtros
                </a>
            @endif
        </div>

        <a href="{{ route('admin.agenda.create') }}" class="bg-[#1a3b2e] text-white px-4 py-2 rounded-lg hover:bg-[#2a5a45] transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Agregar Evento
        </a>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lugar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($eventos as $evento)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($evento->tipo_fijacion == 'superdestacado')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                    Super Destacado
                                </span>
                            @elseif($evento->tipo_fijacion == 'destacado')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Destacado
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    Normal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $evento->titulo }}</div>
                            <div class="text-xs text-gray-500">{{ $evento->seccion->nombre ?? 'Sin sección' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($evento->fecha_evento)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $evento->hora_evento ? \Carbon\Carbon::parse($evento->hora_evento)->format('H:i') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $evento->lugar ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $evento->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $evento->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Botón Toggle Estado --}}
                                <form action="{{ route('admin.agenda.toggle-estado', $evento) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-white hover:bg-yellow-600 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-1" 
                                            title="{{ $evento->estado ? 'Desactivar' : 'Activar' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </form>
                                
                                {{-- Botón Editar --}}
                                <x-btn-edit 
                                    onclick="window.location.href='{{ route('admin.agenda.edit', $evento) }}'"
                                    title="Editar evento"
                                />
                                
                                {{-- Botón Eliminar --}}
                                <x-btn-delete 
                                    :action="route('admin.agenda.destroy', $evento)"
                                    :confirm="'¿Eliminar el evento ' . $evento->titulo . '? Esta acción no se puede deshacer.'"
                                    title="Eliminar evento"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No se encontraron eventos.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $eventos->withQueryString()->links() }}
    </div>
</div>
@endsection