{{-- resources/views/public/servicios.blade.php --}}
@extends('layouts.public')

@section('title', 'Servicios Online')

@section('content')

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">

        {{-- Descargables por sección --}}
        @if($descargables->count())
        <div class="mb-16">
            <p class="section-label mb-2">Servicios</p>
            <h1 class="font-display text-5xl text-gray-900 mb-10">Descargables y Documentación</h1>

            @foreach($descargables as $seccionId => $items)
            @php $seccion = $items->first()->seccion; @endphp
            @if($seccion && $seccion->visible_en_sitio)
            <div class="mb-8 fade-up">
                <h2 class="text-sm font-bold tracking-widest uppercase text-gray-400 mb-4">{{ $seccion->nombre }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($items as $desc)
                    <a href="{{ route('public.descargable.download', $desc->id) }}"
                       class="descargable-item flex items-center gap-4 p-5 border border-gray-100 rounded-sm bg-white">
                        <div class="w-10 h-10 rounded flex items-center justify-center flex-shrink-0" style="background:#fff0f0">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-800 font-medium leading-snug">{{ $desc->tema }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        {{-- Videos --}}
        @if($videos->count())
        <div>
            <p class="section-label mb-2">Capacitación</p>
            <h2 class="font-display text-4xl text-gray-900 mb-3">Videos Instructivos</h2>
            <p class="text-gray-500 text-sm mb-10">Tutoriales y guías en video para facilitar tus trámites y gestiones ante el Colegio.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($videos as $video)
                <div class="video-card group relative overflow-hidden rounded-sm cursor-pointer fade-up"
                     @if($video->url_externa) onclick="window.open('{{ $video->url_externa }}','_blank')"
                     @elseif($video->codigo_embed) onclick="openVideoModal('{{ addslashes($video->codigo_embed) }}')"
                     @endif>
                    <div class="relative h-52 bg-gray-200 overflow-hidden">
                        @if($video->archivo)
                        <img src="{{ asset('storage/' . $video->archivo) }}" alt="{{ $video->tema }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300"></div>
                        @endif
                        <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors"></div>
                        <div class="play-btn absolute inset-0 flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background:rgba(42,90,69,.9)">
                                <svg class="w-6 h-6 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4">
                        <p class="font-semibold text-gray-900 text-sm">{{ $video->tema }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<div id="video-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden" style="background:rgba(0,0,0,.75)">
    <div class="relative w-full max-w-3xl mx-4">
        <button onclick="closeVideoModal()" class="absolute -top-10 right-0 text-white text-sm font-medium">✕ Cerrar</button>
        <div id="video-embed" class="aspect-video bg-black rounded overflow-hidden"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openVideoModal(embed) {
    document.getElementById('video-embed').innerHTML = embed;
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
</script>
@endpush