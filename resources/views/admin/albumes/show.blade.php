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
                    <span>{{ $fotos->count() }} {{ $fotos->count() == 1 ? 'foto' : 'fotos' }}</span>
                    <span>Creado: {{ $album->created_at->format('d/m/Y') }}</span>
                    <span>Por: {{ $album->user->name ?? 'Desconocido' }}</span>
                </div>
            </div>
            <div class="flex gap-2">
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
                        <div class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden">
                            @if($foto->archivo && Storage::disk('public')->exists($foto->archivo))
                                <img src="{{ asset('storage/' . $foto->archivo) }}" 
                                     alt="{{ $foto->epigrafe ?? $album->nombre }}"
                                     class="w-full h-full object-cover cursor-pointer transition-transform duration-300 group-hover:scale-110"
                                     onclick="abrirModalImagen('{{ asset('storage/' . $foto->archivo) }}', '{{ addslashes($foto->epigrafe ?? '') }}')">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 cursor-pointer"
                                     onclick="abrirModalImagen('', '')">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="absolute top-2 left-2 flex gap-1">
                                @if($foto->es_portada)
                                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full shadow-md">Portada</span>
                                @endif
                                @if($foto->es_foto_epigrafe)
                                    <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full shadow-md">Epígrafe</span>
                                @endif
                            </div>
                            
                            {{-- Botones reorganizados --}}
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-200 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100">
                                {{-- Botones superiores (ampliar, recortar, portada) --}}
                                <div class="flex gap-2 mb-2">
                                    {{-- Botón Ampliar --}}
                                    <button onclick="abrirModalImagen('{{ asset('storage/' . $foto->archivo) }}', '{{ addslashes($foto->epigrafe ?? '') }}')" 
                                            class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition transform hover:scale-110"
                                            title="Ver en tamaño completo">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7H3" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h7M3 7l3-3M3 7l3 3" />
                                        </svg>
                                    </button>
                                    
                                    {{-- Botón Recortar --}}
                                    <button onclick="abrirModalRecortar({{ $foto->id }}, '{{ asset('storage/' . $foto->archivo) }}')" 
                                            class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition transform hover:scale-110"
                                            title="Recortar imagen">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                        </svg>
                                    </button>
                                    
                                    {{-- Botón Establecer como Portada --}}
                                    @if(!$foto->es_portada)
                                        <form action="{{ route('admin.albumes.fotos.portada', [$album, $foto]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition transform hover:scale-110"
                                                    title="Establecer como portada">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                {{-- Botones inferiores (editar epígrafe y eliminar) --}}
                                <div class="flex gap-2 mt-1">
                                    {{-- Botón Editar Epígrafe --}}
                                    <button onclick="abrirModalEditarEpigrafe({{ $foto->id }}, '{{ addslashes($foto->epigrafe ?? '') }}', {{ $foto->es_foto_epigrafe ? 'true' : 'false' }})" 
                                            class="bg-purple-500 hover:bg-purple-600 text-white p-2 rounded-lg transition transform hover:scale-110"
                                            title="Editar epígrafe">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    
                                    {{-- Botón Eliminar Foto --}}
                                    <form action="{{ route('admin.albumes.fotos.destroy', [$album, $foto]) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta foto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition transform hover:scale-110"
                                                title="Eliminar foto">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        @if($foto->epigrafe)
                            <div class="mt-2 text-center">
                                <p class="text-xs text-gray-600 truncate">{{ $foto->epigrafe }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
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

{{-- Modales (se mantienen igual) --}}
<div id="modalImagen" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-5xl max-h-full">
        <button onclick="cerrarModalImagen()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="imagenCompleta" src="" alt="" class="max-w-full max-h-screen object-contain">
        <p id="epigrafeImagen" class="text-center text-white mt-4 text-lg"></p>
    </div>
</div>

<div id="modalEditarEpigrafe" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Editar Epígrafe</h3>
            <button onclick="cerrarModalEditarEpigrafe()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="formEditarEpigrafe" method="POST">
            @csrf
            @method('PUT')
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Epígrafe</label>
                    <input type="text" name="epigrafe" id="epigrafe_input" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="es_foto_epigrafe" id="es_foto_epigrafe_checkbox" value="1" class="rounded border-gray-300 text-[#1a3b2e]">
                        <span class="ml-2 text-sm text-gray-700">Usar como foto de epígrafe</span>
                    </label>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-lg">
                <button type="button" onclick="cerrarModalEditarEpigrafe()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<div id="modalRecortar" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl mx-4">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Recortar Imagen</h3>
            <button onclick="cerrarModalRecortar()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-4">
            <div class="bg-gray-900 rounded-lg overflow-hidden flex justify-center items-center" style="max-height: 60vh;">
                <div class="relative inline-block">
                    <img id="imagenRecortar" src="" alt="Imagen a recortar" class="max-w-full max-h-[55vh] object-contain" style="cursor: crosshair;">
                    <div id="selectionOverlay" class="absolute border-2 border-blue-500 bg-blue-500 bg-opacity-20 hidden pointer-events-none" style="z-index: 10;"></div>
                </div>
            </div>
            <div class="mt-3 text-center text-sm text-gray-500">
                <p> Haz clic y arrastra para seleccionar el área que quieres recortar</p>
            </div>
            <div class="mt-4 flex flex-wrap gap-3 items-center justify-center">
                <button type="button" onclick="zoomIn()" class="px-3 py-1.5 bg-gray-200 rounded-lg hover:bg-gray-300 transition">🔍 Acercar</button>
                <button type="button" onclick="zoomOut()" class="px-3 py-1.5 bg-gray-200 rounded-lg hover:bg-gray-300 transition">🔍 Alejar</button>
                <button type="button" onclick="rotarIzquierda()" class="px-3 py-1.5 bg-gray-200 rounded-lg hover:bg-gray-300 transition">↻ Rotar Izquierda</button>
                <button type="button" onclick="rotarDerecha()" class="px-3 py-1.5 bg-gray-200 rounded-lg hover:bg-gray-300 transition">↺ Rotar Derecha</button>
                <button type="button" onclick="resetearSeleccion()" class="px-3 py-1.5 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Reiniciar Selección</button>
            </div>
        </div>
        <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-lg">
            <button type="button" onclick="cerrarModalRecortar()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">Cancelar</button>
            <button type="button" onclick="guardarRecorte()" class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">Guardar Recorte</button>
        </div>
    </div>
</div>

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
                <div><p class="text-sm text-gray-600 mb-2">Álbum: <strong id="album_nombre"></strong></p></div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Seleccionar fotos</label>
                    <input type="file" name="fotos[]" multiple accept="image/*" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Epígrafe general</label>
                    <input type="text" name="epigrafe_general" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e]" placeholder="Este texto se aplicará a todas las fotos">
                </div>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50 rounded-b-lg">
                <button type="button" onclick="cerrarModalAgregarFotos()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">Subir Fotos</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let fotoIdActual = null;
    let albumId = {{ $album->id }};
    
    // Variables para recorte
    let startX = 0, startY = 0;
    let isSelecting = false;
    let currentSelection = null;
    let currentZoom = 1;
    let rotationAngle = 0;
    let originalImageWidth = 0, originalImageHeight = 0;
    let displayWidth = 0, displayHeight = 0;
    let scaleX = 1, scaleY = 1;
    let fotoIdActualRecortar = null;
    let imgElement = null;
    
    // ==================== MODAL IMAGEN ====================
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
    
    // ==================== MODAL EPÍGRAFE ====================
    function abrirModalEditarEpigrafe(fotoId, epigrafeActual, esFotoEpigrafe) {
        fotoIdActual = fotoId;
        const form = document.getElementById('formEditarEpigrafe');
        form.action = `/admin/albumes/${albumId}/fotos/${fotoId}`;
        document.getElementById('epigrafe_input').value = epigrafeActual || '';
        document.getElementById('es_foto_epigrafe_checkbox').checked = esFotoEpigrafe;
        document.getElementById('modalEditarEpigrafe').classList.remove('hidden');
    }
    
    function cerrarModalEditarEpigrafe() {
        document.getElementById('modalEditarEpigrafe').classList.add('hidden');
        document.getElementById('epigrafe_input').value = '';
        document.getElementById('es_foto_epigrafe_checkbox').checked = false;
        fotoIdActual = null;
    }
    
    // ==================== MODAL AGREGAR FOTOS ====================
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
    
    // ==================== MODAL RECORTAR ====================
    function abrirModalRecortar(fotoId, imagenUrl) {
        fotoIdActualRecortar = fotoId;
        const img = document.getElementById('imagenRecortar');
        img.src = imagenUrl;
        currentZoom = 1;
        rotationAngle = 0;
        currentSelection = null;
        
        document.getElementById('modalRecortar').classList.remove('hidden');
        
        img.onload = function() {
            originalImageWidth = img.naturalWidth;
            originalImageHeight = img.naturalHeight;
            displayWidth = img.clientWidth;
            displayHeight = img.clientHeight;
            scaleX = originalImageWidth / displayWidth;
            scaleY = originalImageHeight / displayHeight;
            
            const overlay = document.getElementById('selectionOverlay');
            overlay.style.display = 'none';
            overlay.classList.add('hidden');
        };
        
        if (img.complete) img.onload();
        
        setTimeout(setupSelectionEvents, 100);
    }
    
    function setupSelectionEvents() {
        const img = document.getElementById('imagenRecortar');
        img.addEventListener('mousedown', onMouseDown);
        img.addEventListener('mousemove', onMouseMove);
        img.addEventListener('mouseup', onMouseUp);
        img.style.userSelect = 'none';
    }
    
    function onMouseDown(e) {
        const rect = document.getElementById('imagenRecortar').getBoundingClientRect();
        startX = e.clientX - rect.left;
        startY = e.clientY - rect.top;
        
        if (startX >= 0 && startX <= rect.width && startY >= 0 && startY <= rect.height) {
            isSelecting = true;
            const overlay = document.getElementById('selectionOverlay');
            overlay.style.display = 'none';
            overlay.classList.add('hidden');
        }
    }
    
    function onMouseMove(e) {
        if (!isSelecting) return;
        
        const rect = document.getElementById('imagenRecortar').getBoundingClientRect();
        const currentX = e.clientX - rect.left;
        const currentY = e.clientY - rect.top;
        
        let x = Math.min(startX, currentX);
        let y = Math.min(startY, currentY);
        let width = Math.abs(currentX - startX);
        let height = Math.abs(currentY - startY);
        
        x = Math.max(0, Math.min(x, rect.width));
        y = Math.max(0, Math.min(y, rect.height));
        width = Math.min(width, rect.width - x);
        height = Math.min(height, rect.height - y);
        
        if (width > 5 && height > 5) {
            const overlay = document.getElementById('selectionOverlay');
            overlay.style.left = x + 'px';
            overlay.style.top = y + 'px';
            overlay.style.width = width + 'px';
            overlay.style.height = height + 'px';
            overlay.style.display = 'block';
            overlay.classList.remove('hidden');
            currentSelection = { x, y, width, height };
        }
    }
    
    function onMouseUp() {
        isSelecting = false;
    }
    
    function resetearSeleccion() {
        const overlay = document.getElementById('selectionOverlay');
        overlay.style.display = 'none';
        overlay.classList.add('hidden');
        currentSelection = null;
    }
    
    function zoomIn() {
        const img = document.getElementById('imagenRecortar');
        currentZoom += 0.1;
        img.style.transform = `scale(${currentZoom}) rotate(${rotationAngle}deg)`;
        resetearSeleccion();
    }
    
    function zoomOut() {
        const img = document.getElementById('imagenRecortar');
        if (currentZoom > 0.2) {
            currentZoom -= 0.1;
            img.style.transform = `scale(${currentZoom}) rotate(${rotationAngle}deg)`;
            resetearSeleccion();
        }
    }
    
    function rotarIzquierda() {
        const img = document.getElementById('imagenRecortar');
        rotationAngle -= 90;
        img.style.transform = `scale(${currentZoom}) rotate(${rotationAngle}deg)`;
        resetearSeleccion();
    }
    
    function rotarDerecha() {
        const img = document.getElementById('imagenRecortar');
        rotationAngle += 90;
        img.style.transform = `scale(${currentZoom}) rotate(${rotationAngle}deg)`;
        resetearSeleccion();
    }
    
    function guardarRecorte() {
        if (!currentSelection) {
            alert('Por favor, selecciona un área para recortar haciendo clic y arrastrando sobre la imagen.');
            return;
        }
        
        const img = document.getElementById('imagenRecortar');
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        const cropX = currentSelection.x * scaleX;
        const cropY = currentSelection.y * scaleY;
        const cropWidth = currentSelection.width * scaleX;
        const cropHeight = currentSelection.height * scaleY;
        
        canvas.width = cropWidth;
        canvas.height = cropHeight;
        
        const tempImg = new Image();
        tempImg.src = img.src;
        
        tempImg.onload = function() {
            ctx.drawImage(tempImg, cropX, cropY, cropWidth, cropHeight, 0, 0, cropWidth, cropHeight);
            
            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('imagen', blob, 'recortada.jpg');
                
                fetch(`/admin/albumes/${albumId}/fotos/${fotoIdActualRecortar}/recortar`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cerrarModalRecortar();
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error al guardar el recorte');
                });
            }, 'image/jpeg', 0.9);
        };
    }
    
    function cerrarModalRecortar() {
        const img = document.getElementById('imagenRecortar');
        img.removeEventListener('mousedown', onMouseDown);
        img.removeEventListener('mousemove', onMouseMove);
        img.removeEventListener('mouseup', onMouseUp);
        img.style.transform = '';
        currentZoom = 1;
        rotationAngle = 0;
        document.getElementById('modalRecortar').classList.add('hidden');
        fotoIdActualRecortar = null;
        currentSelection = null;
        const overlay = document.getElementById('selectionOverlay');
        overlay.style.display = 'none';
        overlay.classList.add('hidden');
    }
    
    // ==================== CIERRE DE MODALES ====================
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarModalImagen();
            cerrarModalEditarEpigrafe();
            cerrarModalRecortar();
            cerrarModalAgregarFotos();
        }
    });
    
    window.onclick = function(event) {
        const modalImagen = document.getElementById('modalImagen');
        const modalEpigrafe = document.getElementById('modalEditarEpigrafe');
        const modalRecortar = document.getElementById('modalRecortar');
        const modalFotos = document.getElementById('modalAgregarFotos');
        
        if (event.target === modalImagen) cerrarModalImagen();
        if (event.target === modalEpigrafe) cerrarModalEditarEpigrafe();
        if (event.target === modalRecortar) cerrarModalRecortar();
        if (event.target === modalFotos) cerrarModalAgregarFotos();
    }
</script>
@endpush
@endsection