{{-- resources/views/modulos/noticias/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Noticias y Títulos')
@section('header-title', 'Noticias y Títulos')

@section('content')
<div class="space-y-5">
    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-emerald-800 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-600">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-red-800 flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-600">✕</button>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            {{-- Filtro por búsqueda --}}
            <form method="GET" action="{{ route('admin.noticias.index') }}" id="form-buscar" class="relative">
                <input type="text" 
                       name="buscar" 
                       value="{{ request('buscar') }}"
                       placeholder="Buscar noticias..."
                       class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl w-64 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"
                       onchange="this.form.submit()">
                <svg class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>

            {{-- Filtro por sección --}}
            <select name="seccion" 
                    onchange="window.location.href='{{ route('admin.noticias.index') }}?seccion='+this.value+'&buscar={{ request('buscar') }}&modo={{ request('modo') }}'"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200">
                <option value="">Todas las secciones</option>
                @foreach($secciones as $seccion)
                    <option value="{{ $seccion->id }}" {{ request('seccion') == $seccion->id ? 'selected' : '' }}>
                        {{ $seccion->nombre }}
                    </option>
                @endforeach
            </select>

            {{-- Filtro por modo --}}
            <select name="modo" 
                    onchange="window.location.href='{{ route('admin.noticias.index') }}?modo='+this.value+'&seccion={{ request('seccion') }}&buscar={{ request('buscar') }}'"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-200">
                <option value="">Todos los modos</option>
                @foreach($modosTexto as $modo)
                    <option value="{{ $modo->id }}" {{ request('modo') == $modo->id ? 'selected' : '' }}>
                        {{ $modo->nombre }}
                    </option>
                @endforeach
            </select>

            {{-- Limpiar filtros --}}
            @if(request()->hasAny(['seccion', 'modo', 'buscar']))
                <a href="{{ route('admin.noticias.index') }}" 
                   class="text-xs text-gray-400 hover:text-gray-600 underline underline-offset-2">
                    Limpiar filtros
                </a>
            @endif

            {{-- Contador --}}
            <span class="text-sm text-gray-500 ml-2">
                {{ $noticias->total() }} {{ $noticias->total() === 1 ? 'noticia' : 'noticias' }}
            </span>
        </div>

        {{-- Botón agregar --}}
        <a href="{{ route('admin.noticias.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Agregar Título
        </a>
    </div>

    {{-- Tabla de noticias --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Título</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sección</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">☑</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">D</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">U</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($noticias as $noticia)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($noticia->fecha_publicacion)->format('d-m-Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <div>
                                    @if($noticia->volanta)
                                        <p class="text-xs text-emerald-600 mb-0.5">{{ $noticia->volanta }}</p>
                                    @endif
                                    <p class="font-medium text-gray-800">{{ Str::limit($noticia->titulo, 80) }}</p>
                                    @if($noticia->bajada)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($noticia->bajada, 60) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $noticia->seccion->nombre ?? '—' }}
                                </span>
                            </td>
                            
                            {{-- Visible (☑) --}}
                            <td class="px-5 py-4 text-center">
                                <button onclick="toggleEstado({{ $noticia->id }}, 'visible', this)"
                                        class="toggle-visible-{{ $noticia->id }} text-xl {{ $noticia->visible ? 'text-emerald-600' : 'text-gray-300' }} hover:opacity-75 transition">
                                    ☑
                                </button>
                            </td>
                            
                            {{-- Destacado (D) --}}
                            <td class="px-5 py-4 text-center">
                                <button onclick="toggleEstado({{ $noticia->id }}, 'destacado', this)"
                                        class="toggle-destacado-{{ $noticia->id }} text-xl {{ $noticia->es_destacado_portada ? 'text-emerald-600' : 'text-gray-300' }} hover:opacity-75 transition">
                                    D
                                </button>
                            </td>
                            
                            {{-- Activa (U) --}}
                            <td class="px-5 py-4 text-center">
                                <button onclick="toggleEstado({{ $noticia->id }}, 'activa', this)"
                                        class="toggle-activa-{{ $noticia->id }} text-xl {{ $noticia->activa ? 'text-emerald-600' : 'text-gray-300' }} hover:opacity-75 transition">
                                    U
                                </button>
                            </td>
                            
                            {{-- Acciones --}}
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.noticias.edit', $noticia) }}" 
                                       class="p-2 rounded-lg text-gray-400 hover:text-emerald-700 hover:bg-emerald-50 transition"
                                       title="Editar">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button onclick="confirmarEliminar({{ $noticia->id }}, '{{ addslashes($noticia->titulo) }}')"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                                            title="Eliminar">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                    <p class="text-sm font-medium">No hay noticias para mostrar</p>
                                    <a href="{{ route('admin.noticias.create') }}" class="text-sm text-emerald-600 hover:underline">
                                        Crear la primera noticia
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.noticias.index') }}" id="form-por-pagina" class="flex items-center gap-2">
                <input type="hidden" name="buscar" value="{{ request('buscar') }}">
                <input type="hidden" name="seccion" value="{{ request('seccion') }}">
                <input type="hidden" name="modo" value="{{ request('modo') }}">
                <select name="por_pagina" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-2 py-1 bg-white">
                    @foreach([10, 25, 50] as $n)
                        <option value="{{ $n }}" {{ $porPagina == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <span class="text-sm text-gray-500">por página</span>
            </form>
        </div>
        
        <div class="flex items-center gap-1">
            {{ $noticias->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- Modal eliminar --}}
<div id="modal-eliminar"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);"
     onclick="if(event.target===this) cerrarModal('modal-eliminar')">
    
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 flex items-center justify-center w-12 h-12 rounded-full bg-red-50">
                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Eliminar noticia</h3>
            <p class="text-sm text-gray-500 mb-5">
                ¿Estás seguro de eliminar "<span id="noticia-titulo-eliminar" class="font-medium text-gray-700"></span>"?
                Esta acción no se puede deshacer.
            </p>
            <div class="flex justify-center gap-3">
                <button onclick="cerrarModal('modal-eliminar')"
                        class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition border border-gray-200">
                    Cancelar
                </button>
                <form id="form-eliminar" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                        Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function cerrarModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    function confirmarEliminar(id, titulo) {
        document.getElementById('noticia-titulo-eliminar').textContent = titulo;
        document.getElementById('form-eliminar').action = `/admin/noticias/${id}`;
        document.getElementById('modal-eliminar').classList.remove('hidden');
    }
    
    function toggleEstado(id, tipo, btn) {
        const urls = {
            visible: `/admin/noticias/${id}/toggle-visible`,
            destacado: `/admin/noticias/${id}/toggle-destacado`,
            activa: `/admin/noticias/${id}/toggle-activa`
        };
        
        fetch(urls[tipo], {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                btn.classList.toggle('text-emerald-600');
                btn.classList.toggle('text-gray-300');
            }
        })
        .catch(() => alert('Error al actualizar el estado'));
    }
</script>
@endpush