{{-- resources/views/admin/albumes/show.blade.php --}}
@extends('layouts.admin')

@section('title', $album->nombre)

@section('header-title', $album->nombre)

@section('content')
<div class="space-y-6">
    {{-- Información del álbum --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $album->nombre }}</h2>
                @if($album->descripcion)
                    <p class="text-gray-600 mb-3">{{ $album->descripcion }}</p>
                @endif
                <div class="flex gap-4 text-sm text-gray-500">
                    <span>📸 {{ $fotos->count() }} {{ $fotos->count() == 1 ? 'foto' : 'fotos' }}</span>
                    <span>📅 Creado: {{ $album->created_at->format('d/m/Y') }}</span>
                    <span>👤 Por: {{ $album->user->name ?? 'Desconocido' }}</span>
                </div>
            </div>
            <div class="flex gap-2">
                {{-- Botón para agregar más fotos --}}
                <button type="button" 
                        onclick="abrirModalAgregarFotos({{ $album->id }}, '{{ addslashes($album->nombre) }}')"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar Fotos
                </button>
                
                <a href="{{ route('admin.albumes.edit', $album) }}" 
                   class="bg-[#1a3b2e] text-white px-4 py-2 rounded-lg hover:bg-[#2a5a45] transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar Álbum
                </a>
                
                <a href="{{ route('admin.albumes.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                    Volver
                </a>
            </div>
        </div>
    </div>

    {{-- Galería de fotos --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        @if($fotos->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($fotos as $foto)
                    <div class="relative group">
                        {{-- Imagen --}}
                        <div class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden cursor-pointer"
                             onclick="abrirModalImagen('{{ asset('storage/' . $foto->archivo) }}', '{{ addslashes($foto->epigrafe ?? '') }}')">
                            @if($foto->archivo && Storage::disk('public')->exists($foto->archivo))
                                <img src="{{ asset('storage/' . $foto->archivo) }}" 
                                     alt="{{ $foto->epigrafe ?? $album->nombre }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            {{-- Badges --}}
                            <div class="absolute top-2 left-2 flex gap-1">
                                @if($foto->es_portada)
                                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full shadow-md">⭐ Portada</span>
                                @endif
                                @if($foto->es_foto_epigrafe)
                                    <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full shadow-md">📝 Epígrafe</span>
                                @endif
                            </div>
                            
                            {{-- Overlay de acciones --}}
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex gap-2">
                                    {{-- Establecer como portada --}}
                                    @if(!$foto->es_portada)
                                        <form action="{{ route('admin.albumes.fotos.portada', [$album, $foto]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition" title="Establecer como portada">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- Eliminar foto --}}
                                    <form action="{{ route('admin.albumes.fotos.destroy', [$album, $foto]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta foto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition" title="Eliminar foto">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Epígrafe --}}
                        @if($foto->epigrafe)
                            <p class="text-xs text-gray-600 mt-1 text-center truncate">{{ $foto->epigrafe }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
            
            {{-- Información adicional --}}
            <div class="mt-6 pt-4 border-t border-gray-200 text-center text-sm text-gray-500">
                <p>💡 Haz clic en cualquier foto para verla en tamaño completo</p>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="mt-4 text-gray-500">No hay fotos en este álbum.</p>
                <button type="button" 
                        onclick="abrirModalAgregarFotos({{ $album->id }}, '{{ addslashes($album->nombre) }}')"
                        class="mt-4 px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">
                    Agregar la primera foto
                </button>
            </div>
        @endif
    </div>
</div>

{{-- Modal para ver imagen en tamaño completo --}}
<div id="modalImagen" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-5xl max-h-full">
        <button onclick="cerrarModalImagen()" 
                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="imagenCompleta" src="" alt="" class="max-w-full max-h-screen object-contain">
        <p id="epigrafeImagen" class="text-center text-white mt-4"></p>
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
    // Modal para ver imagen completa
    function abrirModalImagen(url, epigrafe) {
        document.getElementById('imagenCompleta').src = url;
        document.getElementById('epigrafeImagen').textContent = epigrafe || '';
        document.getElementById('modalImagen').classList.remove('hidden');
    }
    
    function cerrarModalImagen() {
        document.getElementById('modalImagen').classList.add('hidden');
        document.getElementById('imagenCompleta').src = '';
        document.getElementById('epigrafeImagen').textContent = '';
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
    
    // Cerrar modal de imagen con ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalImagen();
            cerrarModalAgregarFotos();
        }
    });
    
    // Cerrar modal de imagen al hacer click fuera
    window.onclick = function(event) {
        const modalImagen = document.getElementById('modalImagen');
        const modalFotos = document.getElementById('modalAgregarFotos');
        
        if (event.target === modalImagen) cerrarModalImagen();
        if (event.target === modalFotos) cerrarModalAgregarFotos();
    }
</script>
@endpush
@endsection