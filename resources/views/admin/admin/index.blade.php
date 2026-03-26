{{-- resources/views/admin/admin/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Administradores')

@section('header-title', 'Administradores')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    {{-- Header con acciones --}}
    <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Buscador --}}
            <form method="GET" action="{{ route('admin.admin.index') }}" class="flex">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por nombre, email o DNI..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-80 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent">
                </div>
                <button type="submit" class="ml-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Buscar
                </button>
                @if(request('search') || request('privilegio_id'))
                    <a href="{{ route('admin.admin.index') }}" class="ml-2 px-4 py-2 text-gray-500 hover:text-gray-700">
                        Limpiar
                    </a>
                @endif
            </form>

            {{-- Filtro por rol --}}
            <form method="GET" action="{{ route('admin.admin.index') }}" class="flex items-center gap-2">
                <select name="privilegio_id" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <option value="">Todos los roles</option>
                    @foreach($privilegios as $privilegio)
                        <option value="{{ $privilegio->id }}" {{ request('privilegio_id') == $privilegio->id ? 'selected' : '' }}>
                            {{ $privilegio->nombre }}
                        </option>
                    @endforeach
                </select>
                @if(request('privilegio_id'))
                    <a href="{{ route('admin.admin.index') }}" class="text-red-500 hover:text-red-700 text-sm">✕</a>
                @endif
            </form>
        </div>

        <div class="flex gap-2">
            {{-- Botón Exportar Excel --}}
            <a href="{{ route('admin.admin.export-excel', request()->query()) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                Exportar a Excel
            </a>
            
            {{-- Botón Agregar --}}
            <a href="{{ route('admin.admin.create') }}" class="bg-[#1a3b2e] text-white px-4 py-2 rounded-lg hover:bg-[#2a5a45] transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Administrador
            </a>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Privilegios</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Celular</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($administradores as $admin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $admin->name }} {{ $admin->apellido }}</div>
                            <div class="text-sm text-gray-500">DNI: {{ $admin->dni }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $admin->email }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($admin->privilegios as $privilegio)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ $privilegio->nombre }}
                                    </span>
                                @empty
                                    <span class="text-gray-400 text-sm">Sin privilegios</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $admin->celular ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $admin->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $admin->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.admin.toggle-activo', $admin) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-gray-500 hover:text-yellow-600 transition" title="{{ $admin->activo ? 'Desactivar' : 'Activar' }}">
                                        {{ $admin->activo ? '🔘' : '⚪' }}
                                    </button>
                                </form>
                                
                                <a href="{{ route('admin.admin.edit', $admin) }}" class="text-blue-600 hover:text-blue-900" title="Editar">✏️</a>
                                
                                @if($admin->id !== auth()->id())
                                    <form action="{{ route('admin.admin.destroy', $admin) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este administrador?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">🗑️</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No se encontraron administradores.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $administradores->withQueryString()->links() }}
    </div>
</div>
@endsection