{{-- resources/views/public/servicios.blade.php --}}
@extends('layouts.public')

@section('title', 'Servicios Online')

@section('content')

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">

        {{-- Descargables por sección --}}
        @if($descargables->count())
        <div class="mb-16">
            <p class="section-label mb-2 text-sm text-[#1a3b2e] font-semibold uppercase tracking-wider">Servicios</p>
            <h1 class="font-display text-5xl text-gray-900 mb-10">Descargables y Documentación</h1>

            @foreach($descargables as $seccionId => $items)
            @php 
                $seccion = $items->first()->seccion; 
            @endphp
            @if($seccion && $seccion->visible_en_sitio)
            <div class="mb-12 fade-up">
                {{-- TÍTULO DE LA SECCIÓN - Aquí se muestra el nombre de la sección --}}
                <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-[#1a3b2e] pl-4 mb-6">
                    {{ $seccion->nombre }}
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($items as $desc)
                    <a href="{{ route('public.descargable.download', $desc->id) }}"
   class="descargable-item group flex items-start gap-4 p-5 border border-gray-200 rounded-lg hover:shadow-lg hover:border-[#1a3b2e] transition-all duration-300 bg-white">
    <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 bg-[#fff0f0] group-hover:bg-[#1a3b2e] transition-colors duration-300">
        @php
            $icono = match($desc->tipo_archivo) {
                'pdf' => '📄',
                'doc', 'docx' => '📝',
                'xls', 'xlsx' => '📊',
                default => '📁'
            };
        @endphp
        <span class="text-2xl">{{ $icono }}</span>
    </div>
    <div class="flex-1">
        <p class="text-gray-800 font-semibold leading-snug group-hover:text-[#1a3b2e] transition-colors">
            {{ $desc->tema }}
        </p>
        {{-- Badge sección --}}
        <span class="inline-block mt-2 text-xs font-bold uppercase tracking-widest px-2 py-0.5 rounded-sm"
              style="background:var(--verde-claro); color:var(--verde-oscuro)">
            {{ $seccion->nombre }}
        </span>
        @if($desc->comentario)
            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($desc->comentario, 80) }}</p>
        @endif
        <div class="flex items-center gap-3 mt-2">
            <span class="text-xs text-gray-400">
                📅 {{ \Carbon\Carbon::parse($desc->fecha_publicacion)->format('d/m/Y') }}
            </span>
            <span class="text-xs text-gray-400">
                ⬇️ {{ $desc->total_descargas }} descargas
            </span>
        </div>
    </div>
</a>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-500">No hay documentos disponibles por el momento.</p>
        </div>
        @endif

        {{-- Videos --}}
        @if($videos->count())
        <div class="mt-16">
            <p class="section-label mb-2 text-sm text-[#1a3b2e] font-semibold uppercase tracking-wider">Capacitación</p>
            <h2 class="font-display text-4xl text-gray-900 mb-3">Videos Instructivos</h2>
            <p class="text-gray-500 text-sm mb-10">Tutoriales y guías en video para facilitar tus trámites y gestiones ante el Colegio.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($videos as $video)
                <div class="video-card group relative overflow-hidden rounded-lg cursor-pointer fade-up shadow-md hover:shadow-xl transition-shadow"
                     @if($video->url_externa) onclick="window.open('{{ $video->url_externa }}','_blank')"
                     @elseif($video->codigo_embed) onclick="openVideoModal('{{ addslashes($video->codigo_embed) }}')"
                     @endif>
                    <div class="relative h-52 bg-gray-200 overflow-hidden">
                        @if($video->archivo)
                        <img src="{{ asset('storage/' . $video->archivo) }}" alt="{{ $video->tema }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors"></div>
                        <div class="play-btn absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center bg-[#1a3b2e] bg-opacity-90 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4">
                        <p class="font-semibold text-gray-900 text-sm">{{ $video->tema }}</p>
                        @if($video->seccion)
                            <p class="text-xs text-[#1a3b2e] mt-1">{{ $video->seccion->nombre }}</p>
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
<div id="video-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-90" style="background:rgba(0,0,0,0.9)">
    <div class="relative w-full max-w-4xl mx-4">
        <button onclick="closeVideoModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-sm font-medium transition-colors">
            ✕ Cerrar
        </button>
        <div id="video-embed" class="aspect-video bg-black rounded-lg overflow-hidden shadow-2xl"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openVideoModal(embed) {
    let cleanEmbed = embed;
    if (embed.includes('youtube.com') || embed.includes('youtu.be')) {
        const videoId = embed.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/);
        if (videoId) {
            cleanEmbed = `<iframe width="100%" height="100%" src="https://www.youtube.com/embed/${videoId[1]}" frameborder="0" allowfullscreen></iframe>`;
        }
    }
    document.getElementById('video-embed').innerHTML = cleanEmbed;
    document.getElementById('video-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeVideoModal() {
    document.getElementById('video-embed').innerHTML = '';
    document.getElementById('video-modal').classList.add('hidden');
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