{{-- resources/views/admin/albumes/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Álbumes de Fotos')

@section('header-title', 'Álbumes de Fotos')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    {{-- Header con acciones --}}
    <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Buscador --}}
            <form method="GET" action="{{ route('admin.albumes.index') }}" class="flex">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por nombre..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-80 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent">
                </div>
                <button type="submit" class="ml-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Buscar
                </button>
            </form>

            {{-- Filtro por estado --}}
            <form method="GET" action="{{ route('admin.albumes.index') }}" class="flex items-center gap-2">
                <select name="estado" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </form>

            {{-- Filtro por visibilidad --}}
            <form method="GET" action="{{ route('admin.albumes.index') }}" class="flex items-center gap-2">
                <select name="visible" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <option value="">Todos</option>
                    <option value="1" {{ request('visible') == '1' ? 'selected' : '' }}>Visibles</option>
                    <option value="0" {{ request('visible') == '0' ? 'selected' : '' }}>Ocultos</option>
                </select>
            </form>

            @if(request('search') || request('estado') || request('visible'))
                <a href="{{ route('admin.albumes.index') }}" class="text-red-500 hover:text-red-700 text-sm">
                    Limpiar filtros
                </a>
            @endif
        </div>

        {{-- Botón para abrir modal --}}
        <button type="button" 
                onclick="abrirModalCrearAlbum()"
                class="bg-[#1a3b2e] text-white px-4 py-2 rounded-lg hover:bg-[#2a5a45] transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Crear Álbum
        </button>
    </div>

    {{-- Grid de álbumes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
        @forelse($albumes as $album)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                {{-- Portada del álbum (clickeable también) --}}
                <a href="{{ route('admin.albumes.show', $album) }}" class="block relative h-48 bg-gray-100 overflow-hidden group">
                    @php
                        $portada = $album->fotos()->where('es_portada', true)->first();
                    @endphp
                    
                    @if($portada && $portada->archivo && Storage::disk('public')->exists($portada->archivo))
                        <img src="{{ asset('storage/' . $portada->archivo) }}" 
                             alt="{{ $album->nombre }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                    @else
                        @php
                            $primeraFoto = $album->fotos()->first();
                        @endphp
                        @if($primeraFoto && $primeraFoto->archivo && Storage::disk('public')->exists($primeraFoto->archivo))
                            <img src="{{ asset('storage/' . $primeraFoto->archivo) }}" 
                                 alt="{{ $album->nombre }}"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    @endif
                    
                    {{-- Badge de cantidad de fotos --}}
                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded-full">
                        {{ $album->fotos()->count() }} {{ $album->fotos()->count() == 1 ? 'foto' : 'fotos' }}
                    </div>
                    
                    {{-- Overlay de hover --}}
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                        <span class="opacity-0 group-hover:opacity-100 text-white text-sm font-medium bg-black bg-opacity-50 px-3 py-1 rounded-full transition-all duration-200">
                            Ver todas las fotos
                        </span>
                    </div>
                </a>
                
                {{-- Información del álbum --}}
                <div class="p-4">
                    {{-- Título clickeable --}}
                    <a href="{{ route('admin.albumes.show', $album) }}" 
                       class="block group">
                        <h3 class="font-semibold text-gray-900 text-lg mb-1 truncate hover:text-[#1a3b2e] transition-colors">
                            {{ $album->nombre }}
                        </h3>
                    </a>
                    
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $album->descripcion ?? 'Sin descripción' }}</p>
                    
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span>Creado: {{ $album->created_at->format('d/m/Y') }}</span>
                        <div class="flex gap-2">
                            @if($album->visible)
                                <span class="text-green-600">✓ Visible</span>
                            @else
                                <span class="text-gray-400">⊙ Oculto</span>
                            @endif
                            @if($album->estado)
                                <span class="text-green-600">● Activo</span>
                            @else
                                <span class="text-red-500">○ Inactivo</span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Acciones --}}
                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                        {{-- Toggle Estado --}}
                        <form action="{{ route('admin.albumes.toggle-estado', $album) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-white hover:bg-yellow-600 transition-all duration-150"
                                    title="{{ $album->estado ? 'Desactivar' : 'Activar' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </form>
                        
                        {{-- Toggle Visibilidad --}}
                        <form action="{{ route('admin.albumes.toggle-visible', $album) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-white hover:bg-blue-600 transition-all duration-150"
                                    title="{{ $album->visible ? 'Ocultar' : 'Mostrar' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </form>
                        
                        {{-- Botón para agregar fotos --}}
                        <button type="button" 
                                onclick="abrirModalAgregarFotos({{ $album->id }}, '{{ addslashes($album->nombre) }}')"
                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-white hover:bg-green-600 transition-all duration-150"
                                title="Agregar fotos">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                        
                        {{-- Editar --}}
                        <button type="button" 
                                onclick="abrirModalEditarAlbum({{ $album->id }}, '{{ addslashes($album->nombre) }}', '{{ addslashes($album->descripcion ?? '') }}', {{ $album->visible ? 'true' : 'false' }}, {{ $album->estado ? 'true' : 'false' }})"
                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-white hover:bg-[#1a3b2e] transition-all duration-150"
                                title="Editar álbum">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        
                        {{-- Eliminar --}}
                        <form action="{{ route('admin.albumes.destroy', $album) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar el álbum {{ $album->nombre }} y todas sus fotos? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-white hover:bg-red-600 transition-all duration-150"
                                    title="Eliminar álbum">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                No se encontraron álbumes.
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $albumes->withQueryString()->links() }}
    </div>
</div>

{{-- Modal Crear Álbum --}}
<div id="modalCrearAlbum" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Crear Nuevo Álbum</h3>
            <button onclick="cerrarModalCrearAlbum()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="formCrearAlbum" action="{{ route('admin.albumes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Álbum *</label>
                    <input type="text" name="nombre" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fotos del Álbum</label>
                    <input type="file" name="fotos[]" multiple accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <p class="text-xs text-gray-500 mt-1">Puedes seleccionar múltiples fotos. Formatos: JPG, PNG, GIF (Max 5MB cada una)</p>
                </div>
                
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="visible" value="1" checked class="rounded border-gray-300 text-[#1a3b2e]">
                        <span class="ml-2 text-sm text-gray-700">Visible en el sitio</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="estado" value="1" checked class="rounded border-gray-300 text-[#1a3b2e]">
                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-lg">
                <button type="button" onclick="cerrarModalCrearAlbum()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">
                    Crear Álbum
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar Álbum --}}
<div id="modalEditarAlbum" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Editar Álbum</h3>
            <button onclick="cerrarModalEditarAlbum()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="formEditarAlbum" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Álbum *</label>
                    <input type="text" name="nombre" id="edit_nombre" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" id="edit_descripcion" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agregar más fotos</label>
                    <input type="file" name="fotos[]" multiple accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <p class="text-xs text-gray-500 mt-1">Puedes seleccionar múltiples fotos para agregar al álbum</p>
                </div>
                
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="visible" id="edit_visible" value="1" class="rounded border-gray-300 text-[#1a3b2e]">
                        <span class="ml-2 text-sm text-gray-700">Visible en el sitio</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="estado" id="edit_estado" value="1" class="rounded border-gray-300 text-[#1a3b2e]">
                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-lg">
                <button type="button" onclick="cerrarModalEditarAlbum()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Agregar Fotos --}}
<div id="modalAgregarFotos" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Agregar Fotos al Álbum</h3>
            <button onclick="cerrarModalAgregarFotos()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="formAgregarFotos" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-4 space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Álbum: <strong id="album_nombre"></strong></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Seleccionar fotos</label>
                    <input type="file" name="fotos[]" multiple accept="image/*" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF (Max 5MB cada una)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Epígrafe general (opcional)</label>
                    <input type="text" name="epigrafe_general" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]"
                           placeholder="Este texto se aplicará a todas las fotos">
                </div>
            </div>
            
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-lg">
                <button type="button" onclick="cerrarModalAgregarFotos()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">
                    Subir Fotos
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Modal Crear Álbum
    function abrirModalCrearAlbum() {
        document.getElementById('modalCrearAlbum').classList.remove('hidden');
    }
    
    function cerrarModalCrearAlbum() {
        document.getElementById('modalCrearAlbum').classList.add('hidden');
        document.getElementById('formCrearAlbum').reset();
    }
    
    // Modal Editar Álbum
    function abrirModalEditarAlbum(id, nombre, descripcion, visible, estado) {
        const form = document.getElementById('formEditarAlbum');
        form.action = `/admin/albumes/${id}`;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_descripcion').value = descripcion;
        document.getElementById('edit_visible').checked = visible;
        document.getElementById('edit_estado').checked = estado;
        document.getElementById('modalEditarAlbum').classList.remove('hidden');
    }
    
    function cerrarModalEditarAlbum() {
        document.getElementById('modalEditarAlbum').classList.add('hidden');
        document.getElementById('formEditarAlbum').reset();
    }
    
    // Modal Agregar Fotos
    function abrirModalAgregarFotos(albumId, albumNombre) {
        const form = document.getElementById('formAgregarFotos');
        form.action = `/admin/albumes/${albumId}/fotos`;
        document.getElementById('album_nombre').textContent = albumNombre;
        document.getElementById('modalAgregarFotos').classList.remove('hidden');
    }
    
    function cerrarModalAgregarFotos() {
        document.getElementById('modalAgregarFotos').classList.add('hidden');
        document.getElementById('formAgregarFotos').reset();
    }
    
    // Cerrar modales al hacer click fuera
    window.onclick = function(event) {
        const modalCrear = document.getElementById('modalCrearAlbum');
        const modalEditar = document.getElementById('modalEditarAlbum');
        const modalFotos = document.getElementById('modalAgregarFotos');
        
        if (event.target === modalCrear) cerrarModalCrearAlbum();
        if (event.target === modalEditar) cerrarModalEditarAlbum();
        if (event.target === modalFotos) cerrarModalAgregarFotos();
    }
</script>
@endpush
@endsection