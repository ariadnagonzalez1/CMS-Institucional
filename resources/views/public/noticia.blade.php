{{-- resources/views/public/noticia.blade.php --}}
@extends('layouts.public')

@section('title', $noticia->titulo)

@section('content')

{{-- Breadcrumb --}}
<div style="background:var(--verde-suave); border-bottom:1px solid #dde8e2;">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <nav class="flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ url('/') }}" class="hover:text-green-800 transition-colors">Inicio</a>
            <span>/</span>
            <a href="{{ url('/novedades') }}" class="hover:text-green-800 transition-colors">Novedades</a>
            <span>/</span>
            <span class="text-gray-700 font-medium">{{ Str::limit($noticia->titulo, 50) }}</span>
        </nav>
    </div>
</div>

{{-- Contenido principal --}}
<div class="max-w-4xl mx-auto px-4 py-12">

    {{-- Header --}}
    <div class="mb-8">

        {{-- Badge sección --}}
        @if($noticia->seccion)
        <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-sm mb-4"
              style="background:{{ $noticia->seccion->color_fondo ?: 'var(--verde-oscuro)' }};
                     color:{{ $noticia->seccion->color_texto ?: '#fff' }}">
            {{ $noticia->seccion->nombre }}
        </span>
        @endif

        {{-- Volanta --}}
        @if($noticia->volanta)
        <p class="text-sm font-semibold text-green-700 uppercase tracking-widest mb-2">
            {{ $noticia->volanta }}
        </p>
        @endif

        {{-- Título --}}
        <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-4">
            {{ $noticia->titulo }}
        </h1>

        {{-- Bajada --}}
        @if($noticia->bajada)
        <p class="text-lg text-gray-600 leading-relaxed border-l-4 pl-4 mb-6"
           style="border-color:var(--verde-medio)">
            {{ $noticia->bajada }}
        </p>
        @endif

        {{-- Meta --}}
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400 pb-6 border-b border-gray-100">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ \Carbon\Carbon::parse($noticia->fecha_publicacion)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                {{ $noticia->visitas }} {{ $noticia->visitas === 1 ? 'vista' : 'vistas' }}
            </span>
        </div>
    </div>

    {{-- Imagen principal --}}
    @php
        $imgPrincipal = $noticia->imagenes->where('es_principal', 1)->first() ?? $noticia->imagenes->first();
    @endphp
    @if($imgPrincipal)
    <div class="mb-8 rounded-sm overflow-hidden shadow-md">
        <img src="{{ asset('storage/' . $imgPrincipal->archivo) }}"
             alt="{{ $imgPrincipal->alt_text ?? $noticia->titulo }}"
             class="w-full object-cover"
             style="max-height:500px;">
        @if($imgPrincipal->descripcion)
        <p class="text-xs text-gray-400 text-center py-2 bg-gray-50 border-t border-gray-100">
            {{ $imgPrincipal->descripcion }}
        </p>
        @endif
    </div>
    @endif

    {{-- Cuerpo de la noticia --}}
    <div class="prose-noticia text-gray-700 leading-relaxed text-base mb-10"
         style="font-size:1.05rem; line-height:1.85;">
        {!! nl2br(e($noticia->cuerpo)) !!}
    </div>

    {{-- Galería de imágenes adicionales --}}
    @if($noticia->imagenes->count() > 1)
    <div class="mb-10">
        <h3 class="font-display text-xl text-gray-800 mb-4">Galería</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($noticia->imagenes->skip(1) as $imagen)
            <div class="overflow-hidden rounded-sm bg-gray-100 aspect-video">
                <img src="{{ asset('storage/' . $imagen->archivo) }}"
                     alt="{{ $imagen->alt_text ?? $noticia->titulo }}"
                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Noticias relacionadas --}}
    @if($noticia->noticiasRelacionadas && $noticia->noticiasRelacionadas->count() > 0)
    <div class="border-t border-gray-100 pt-10 mb-10">
        <h3 class="font-display text-2xl text-gray-900 mb-6">Noticias relacionadas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($noticia->noticiasRelacionadas as $rel)
            <article class="bg-white border border-gray-100 rounded-sm overflow-hidden hover:shadow-md transition-shadow">
                @php $relImg = $rel->imagenes->where('es_principal', 1)->first() ?? $rel->imagenes->first(); @endphp
                <div class="h-36 bg-gray-100 overflow-hidden">
                    @if($relImg)
                    <img src="{{ asset('storage/' . $relImg->archivo) }}"
                         alt="{{ $rel->titulo }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full" style="background:linear-gradient(135deg,#e8f0ec,#c8ddd2)"></div>
                    @endif
                </div>
                <div class="p-4">
                    <h4 class="font-display text-sm font-bold text-gray-900 leading-snug mb-2">
                        {{ Str::limit($rel->titulo, 60) }}
                    </h4>
                    <a href="{{ route('public.noticia', $rel->slug) }}"
                       class="text-xs font-semibold text-green-800 hover:underline">
                        Leer más →
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Botón volver --}}
    <div class="pt-6 border-t border-gray-100">
        <a href="{{ url('/novedades') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-sm text-sm font-semibold text-white transition-opacity hover:opacity-90"
           style="background:var(--verde-oscuro)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a Novedades
        </a>
    </div>

</div>

@endsection