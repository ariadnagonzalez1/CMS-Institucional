{{-- resources/views/admin/descargables/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Trámites y Formularios')

@section('header-title', 'Trámites y Formularios')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    {{-- Header con acciones --}}
    <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Buscador --}}
            <form method="GET" action="{{ route('admin.descargables.index') }}" class="flex">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por tema..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-80 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent">
                </div>
                <button type="submit" class="ml-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Buscar
                </button>
            </form>

            {{-- Filtro por sección --}}
            <form method="GET" action="{{ route('admin.descargables.index') }}" class="flex items-center gap-2">
                <select name="seccion_id" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <option value="">Todas las secciones</option>
                    @foreach($secciones as $seccion)
                        <option value="{{ $seccion->id }}" {{ request('seccion_id') == $seccion->id ? 'selected' : '' }}>
                            {{ $seccion->nombre }}
                        </option>
                    @endforeach
                </select>
            </form>

            {{-- Filtro por estado --}}
            <form method="GET" action="{{ route('admin.descargables.index') }}" class="flex items-center gap-2">
                <select name="estado" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </form>

            @if(request('search') || request('seccion_id') || request('estado'))
                <a href="{{ route('admin.descargables.index') }}" class="text-red-500 hover:text-red-700 text-sm">
                    Limpiar filtros
                </a>
            @endif
        </div>

        {{-- Botón para abrir modal --}}
        <button type="button" 
                onclick="abrirModalCrear()"
                class="bg-[#1a3b2e] text-white px-4 py-2 rounded-lg hover:bg-[#2a5a45] transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Agregar Documento
        </button>
    </div>

    {{-- Lista de documentos --}}
    <div class="divide-y divide-gray-200">
        @forelse($descargables as $descargable)
            <div class="px-6 py-4 hover:bg-gray-50 transition flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        {{-- Ícono según tipo de archivo (clickeable) --}}
                        <a href="{{ route('admin.descargables.download', $descargable) }}" 
                           class="flex-shrink-0 hover:scale-110 transition-transform"
                           title="Descargar {{ $descargable->tema }}">
                            @php
                                $icon = match($descargable->tipo_archivo) {
                                    'pdf' => '📄',
                                    'doc', 'docx' => '📝',
                                    'xls', 'xlsx' => '📊',
                                    default => '📁'
                                };
                            @endphp
                            <span class="text-2xl">{{ $icon }}</span>
                        </a>
                        <div>
                            <a href="{{ route('admin.descargables.download', $descargable) }}" 
                               class="text-gray-900 font-medium hover:text-[#1a3b2e] transition"
                               title="Descargar">
                                {{ $descargable->tema }}
                            </a>
                            <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                <span>{{ $descargable->seccion->nombre ?? 'Sin sección' }}</span>
                                <span>📅 {{ \Carbon\Carbon::parse($descargable->fecha_publicacion)->format('d/m/Y') }}</span>
                                @if($descargable->comentario)
                                    <span>💬 {{ Str::limit($descargable->comentario, 50) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    {{-- Contador de descargas --}}
                    <div class="text-center">
                        <div class="text-lg font-semibold text-gray-700">{{ $descargable->total_descargas }}</div>
                        <div class="text-xs text-gray-400">descargas</div>
                    </div>
                    
                    {{-- Badge de estado --}}
                    <div>
                        @if($descargable->estado)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Activo</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactivo</span>
                        @endif
                    </div>
                    
                    {{-- Acciones --}}
                    <div class="flex items-center gap-1">
                        {{-- Toggle Estado --}}
                        <form action="{{ route('admin.descargables.toggle-estado', $descargable) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-white hover:bg-yellow-600 transition-all duration-150"
                                    title="{{ $descargable->estado ? 'Desactivar' : 'Activar' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </form>
                        
                        {{-- Editar --}}
                        <button type="button" 
                                onclick="abrirModalEditar({{ $descargable->id }}, '{{ addslashes($descargable->seccion_descargable_id) }}', '{{ addslashes($descargable->tema) }}', '{{ addslashes($descargable->comentario ?? '') }}', '{{ $descargable->fecha_publicacion->format('Y-m-d') }}', {{ $descargable->estado ? 'true' : 'false' }})"
                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-white hover:bg-[#1a3b2e] transition-all duration-150"
                                title="Editar documento">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        
                        {{-- Eliminar --}}
                        <form action="{{ route('admin.descargables.destroy', $descargable) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar el documento {{ $descargable->tema }}? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-gray-400 hover:text-white hover:bg-red-600 transition-all duration-150"
                                    title="Eliminar documento">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                No se encontraron documentos.
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $descargables->withQueryString()->links() }}
    </div>
</div>

{{-- Modal Crear Documento --}}
<div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b sticky top-0 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Agregar Documento</h3>
            <button onclick="cerrarModalCrear()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="formCrear" action="{{ route('admin.descargables.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sección *</label>
                    <select name="seccion_descargable_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                        <option value="">Seleccionar sección...</option>
                        @foreach($secciones as $seccion)
                            <option value="{{ $seccion->id }}">{{ $seccion->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de publicación *</label>
                    <input type="date" name="fecha_publicacion" value="{{ date('Y-m-d') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tema *</label>
                    <input type="text" name="tema" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]"
                           placeholder="Ingrese el tema del archivo...">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                    <textarea name="comentario" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]"
                              placeholder="Ingrese un comentario..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Archivo *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-[#1a3b2e] transition">
                        <input type="file" name="archivo" id="archivo_crear" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" required
                               class="hidden">
                        <label for="archivo_crear" class="cursor-pointer block">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-sm text-gray-600">Arrastra o haz clic para subir</p>
                            <p class="text-xs text-gray-400 mt-1">PDF, Word, Excel, ZIP (Max 10MB)</p>
                        </label>
                    </div>
                    <p id="nombre_archivo_crear" class="text-xs text-green-600 mt-1 hidden"></p>
                </div>
                
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="estado" value="1" checked class="rounded border-gray-300 text-[#1a3b2e]">
                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-lg sticky bottom-0">
                <button type="button" onclick="cerrarModalCrear()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">
                    Guardar Archivo
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Editar Documento --}}
<div id="modalEditar" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b sticky top-0 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Editar Documento</h3>
            <button onclick="cerrarModalEditar()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="formEditar" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sección *</label>
                    <select name="seccion_descargable_id" id="edit_seccion_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                        <option value="">Seleccionar sección...</option>
                        @foreach($secciones as $seccion)
                            <option value="{{ $seccion->id }}">{{ $seccion->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de publicación *</label>
                    <input type="date" name="fecha_publicacion" id="edit_fecha" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tema *</label>
                    <input type="text" name="tema" id="edit_tema" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]"
                           placeholder="Ingrese el tema del archivo...">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                    <textarea name="comentario" id="edit_comentario" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]"
                              placeholder="Ingrese un comentario..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reemplazar archivo (opcional)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-[#1a3b2e] transition">
                        <input type="file" name="archivo" id="archivo_editar" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                               class="hidden">
                        <label for="archivo_editar" class="cursor-pointer block">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="text-sm text-gray-600">Arrastra o haz clic para subir</p>
                            <p class="text-xs text-gray-400 mt-1">PDF, Word, Excel, ZIP (Max 10MB)</p>
                        </label>
                    </div>
                    <p id="nombre_archivo_editar" class="text-xs text-gray-500 mt-1"></p>
                </div>
                
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="estado" id="edit_estado" value="1" class="rounded border-gray-300 text-[#1a3b2e]">
                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-lg sticky bottom-0">
                <button type="button" onclick="cerrarModalEditar()" 
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

@push('scripts')
<script>
    let documentoIdActual = null;
    
    // Mostrar nombre del archivo seleccionado
    document.getElementById('archivo_crear')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const span = document.getElementById('nombre_archivo_crear');
        if (fileName) {
            span.textContent = '📄 ' + fileName;
            span.classList.remove('hidden');
        } else {
            span.classList.add('hidden');
        }
    });
    
    document.getElementById('archivo_editar')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const span = document.getElementById('nombre_archivo_editar');
        if (fileName) {
            span.textContent = '📄 Nuevo archivo: ' + fileName;
            span.classList.remove('text-gray-500');
            span.classList.add('text-green-600');
        } else {
            span.textContent = '';
        }
    });
    
    // Modal Crear
    function abrirModalCrear() {
        document.getElementById('modalCrear').classList.remove('hidden');
    }
    
    function cerrarModalCrear() {
        document.getElementById('modalCrear').classList.add('hidden');
        document.getElementById('formCrear').reset();
        document.getElementById('nombre_archivo_crear').classList.add('hidden');
    }
    
    // Modal Editar
    function abrirModalEditar(id, seccionId, tema, comentario, fecha, estado) {
        documentoIdActual = id;
        const form = document.getElementById('formEditar');
        form.action = `/admin/descargables/${id}`;
        
        document.getElementById('edit_seccion_id').value = seccionId;
        document.getElementById('edit_tema').value = tema;
        document.getElementById('edit_comentario').value = comentario;
        document.getElementById('edit_fecha').value = fecha;
        document.getElementById('edit_estado').checked = estado;
        document.getElementById('nombre_archivo_editar').textContent = '';
        
        document.getElementById('modalEditar').classList.remove('hidden');
    }
    
    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.add('hidden');
        document.getElementById('formEditar').reset();
        documentoIdActual = null;
    }
    
    // Cerrar modales al hacer click fuera
    window.onclick = function(event) {
        const modalCrear = document.getElementById('modalCrear');
        const modalEditar = document.getElementById('modalEditar');
        
        if (event.target === modalCrear) cerrarModalCrear();
        if (event.target === modalEditar) cerrarModalEditar();
    }
    
    // Cerrar con ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalCrear();
            cerrarModalEditar();
        }
    });
</script>
@endpush
@endsection