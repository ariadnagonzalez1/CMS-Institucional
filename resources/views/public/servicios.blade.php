{{-- resources/views/public/servicios.blade.php --}}
@extends('layouts.public')

@section('title', 'Servicios Online')

@section('content')

<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">

        {{-- Header --}}
        <div class="mb-12 fade-up">
            <p class="section-label mb-2 text-sm text-[#1a3b2e] font-semibold uppercase tracking-wider">Servicios</p>
            <h1 class="font-display text-4xl md:text-5xl text-gray-900 font-bold">Descargables y Documentación</h1>
            <p class="text-gray-500 mt-3 text-sm max-w-2xl">Accedé a formularios, reglamentos, instructivos y toda la documentación necesaria para tus trámites profesionales.</p>
        </div>

        {{-- BUSCADOR --}}
        <div class="mb-10 fade-up">
            <div class="max-w-md">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" 
                           id="buscadorDocumentos"
                           placeholder="Buscar documento por nombre o sección..."
                           class="w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent text-sm">
                    <button id="limpiarBusqueda" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-2" id="resultadoBusqueda"></p>
            </div>
        </div>

        {{-- Descargables por sección --}}
        @if($descargables->count())
            @foreach($descargables as $seccionId => $items)
                @php $seccion = $items->first()->seccion; @endphp
                @if($seccion && $seccion->visible_en_sitio)
                <div class="mb-16 fade-up seccion-documentos" data-seccion="{{ Str::slug($seccion->nombre) }}">
                    {{-- Título de la sección --}}
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-7 bg-[#1a3b2e] rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $seccion->nombre }}</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($items as $desc)
                        <div class="group fade-up documento-item" data-titulo="{{ Str::lower($desc->tema) }}" data-seccion="{{ Str::lower($seccion->nombre) }}">
                            {{-- Tarjeta con borde y sombra --}}
                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg hover:border-[#1a3b2e] transition-all duration-300 h-full">
                                
                                {{-- SECCIÓN ARRIBA (header de la tarjeta) --}}
                                <div class="px-4 py-2 border-b border-gray-100" style="background-color: #f5f5f0;">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider" style="color: #1a3b2e;">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                        {{ $seccion->nombre }}
                                    </span>
                                </div>
                                
                                {{-- CUERPO DE LA TARJETA (documento) --}}
                                <a href="{{ route('public.descargable.download', $desc->id) }}" class="block p-4 group-hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        {{-- Icono --}}
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-gray-100 group-hover:bg-[#1a3b2e] transition-colors duration-300">
                                            @php
                                                $icono = match($desc->tipo_archivo) {
                                                    'pdf' => '📄',
                                                    'doc', 'docx' => '📝',
                                                    'xls', 'xlsx' => '📊',
                                                    default => '📁'
                                                };
                                            @endphp
                                            <span class="text-xl group-hover:text-white transition-colors duration-300">{{ $icono }}</span>
                                        </div>
                                        
                                        {{-- Título del documento --}}
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-[#1a3b2e] transition-colors leading-tight">
                                                {{ $desc->tema ?: ($desc->nombre_original_archivo ?: 'Documento sin título') }}
                                            </h3>
                                            @if($desc->comentario)
                                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ Str::limit($desc->comentario, 60) }}</p>
                                            @endif
                                        </div>
                                        
                                        {{-- Flecha de descarga --}}
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-[#1a3b2e] transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-3-3m3 3l3-3M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        @else
            <div class="text-center py-16">
                <div class="text-gray-400">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm">No hay documentos disponibles por el momento.</p>
                </div>
            </div>
        @endif

        {{-- Mensaje cuando no hay resultados --}}
        <div id="sinResultados" class="hidden text-center py-16">
            <div class="text-gray-400">
                <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">No se encontraron documentos que coincidan con tu búsqueda.</p>
                <button id="resetBusqueda" class="mt-4 text-[#1a3b2e] font-medium text-sm hover:underline">Limpiar búsqueda</button>
            </div>
        </div>

        {{-- Videos --}}
        @if(isset($videos) && $videos->count())
        <div class="mt-16 pt-8 border-t border-gray-100">
            <div class="mb-8 fade-up">
                <p class="section-label mb-2 text-sm text-[#1a3b2e] font-semibold uppercase tracking-wider">Capacitación</p>
                <h2 class="font-display text-3xl md:text-4xl text-gray-900 font-bold">Videos Instructivos</h2>
                <p class="text-gray-500 mt-2 text-sm">Tutoriales y guías en video para facilitar tus trámites y gestiones ante el Colegio.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($videos as $i => $video)
                <div class="group relative overflow-hidden rounded-xl cursor-pointer shadow-md hover:shadow-xl transition-all duration-300 bg-white border border-gray-100 fade-up"
                     style="transition-delay: {{ $i * 80 }}ms"
                     @if($video->url_externa) onclick="window.open('{{ $video->url_externa }}','_blank')"
                     @elseif($video->codigo_embed) onclick="openVideoModal('{{ addslashes($video->codigo_embed) }}')"
                     @endif>
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($video->archivo)
                        <img src="{{ asset('storage/' . $video->archivo) }}" alt="{{ $video->tema }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#1a3b2e] bg-opacity-90 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="font-semibold text-gray-900 text-sm line-clamp-2">{{ $video->tema }}</p>
                        @if($video->seccion)
                            <p class="text-xs text-[#1a3b2e] mt-1 font-medium">{{ $video->seccion->nombre }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

{{-- Modal para videos --}}
<div id="video-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-90 p-4">
    <div class="relative w-full max-w-4xl">
        <button onclick="closeVideoModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-sm font-medium transition-colors">
            ✕ Cerrar
        </button>
        <div id="video-embed" class="aspect-video bg-black rounded-lg overflow-hidden shadow-2xl"></div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
// Buscador de documentos
document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscadorDocumentos');
    const limpiarBtn = document.getElementById('limpiarBusqueda');
    const sinResultadosDiv = document.getElementById('sinResultados');
    const resultadoSpan = document.getElementById('resultadoBusqueda');
    const resetBtn = document.getElementById('resetBusqueda');
    
    const secciones = document.querySelectorAll('.seccion-documentos');
    const todosLosDocumentos = document.querySelectorAll('.documento-item');
    
    function filtrarDocumentos() {
        const busqueda = buscador.value.trim().toLowerCase();
        
        if (busqueda === '') {
            // Mostrar todo
            secciones.forEach(seccion => {
                seccion.style.display = 'block';
            });
            todosLosDocumentos.forEach(doc => {
                doc.style.display = 'block';
            });
            sinResultadosDiv.classList.add('hidden');
            limpiarBtn.classList.add('hidden');
            resultadoSpan.textContent = '';
            return;
        }
        
        limpiarBtn.classList.remove('hidden');
        
        let documentosVisibles = 0;
        
        // Filtrar documentos
        todosLosDocumentos.forEach(doc => {
            const titulo = doc.dataset.titulo || '';
            const seccion = doc.dataset.seccion || '';
            
            if (titulo.includes(busqueda) || seccion.includes(busqueda)) {
                doc.style.display = 'block';
                documentosVisibles++;
            } else {
                doc.style.display = 'none';
            }
        });
        
        // Ocultar secciones vacías
        secciones.forEach(seccion => {
            const documentosEnSeccion = seccion.querySelectorAll('.documento-item');
            let tieneVisibles = false;
            documentosEnSeccion.forEach(doc => {
                if (doc.style.display !== 'none') {
                    tieneVisibles = true;
                }
            });
            seccion.style.display = tieneVisibles ? 'block' : 'none';
        });
        
        // Mostrar mensaje de resultados
        if (documentosVisibles === 0) {
            sinResultadosDiv.classList.remove('hidden');
            resultadoSpan.textContent = 'No se encontraron resultados';
        } else {
            sinResultadosDiv.classList.add('hidden');
            resultadoSpan.textContent = `Se encontraron ${documentosVisibles} documento(s)`;
        }
    }
    
    function limpiarBusqueda() {
        buscador.value = '';
        filtrarDocumentos();
        buscador.focus();
    }
    
    buscador.addEventListener('input', filtrarDocumentos);
    limpiarBtn.addEventListener('click', limpiarBusqueda);
    
    if (resetBtn) {
        resetBtn.addEventListener('click', limpiarBusqueda);
    }
});
</script>

<script>
function openVideoModal(embed) {
    let cleanEmbed = embed;
    if (embed.includes('youtube.com') || embed.includes('youtu.be')) {
        const videoId = embed.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/);
        if (videoId) {
            cleanEmbed = `<iframe width="100%" height="100%" src="https://www.youtube.com/embed/${videoId[1]}" frameborder="0" allowfullscreen class="w-full h-full"></iframe>`;
        }
    }
    document.getElementById('video-embed').innerHTML = cleanEmbed;
    document.getElementById('video-modal').classList.remove('hidden');
    document.getElementById('video-modal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeVideoModal() {
    document.getElementById('video-embed').innerHTML = '';
    document.getElementById('video-modal').classList.add('hidden');
    document.getElementById('video-modal').classList.remove('flex');
    document.body.style.overflow = '';
}

document.getElementById('video-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeVideoModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeVideoModal();
});
</script>
@endpush