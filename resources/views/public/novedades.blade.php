{{-- resources/views/public/novedades.blade.php --}}
@extends('layouts.public')

@section('title', 'Novedades')

@section('content')

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">

        {{-- Header --}}
        <div class="mb-10">
            <p class="section-label mb-2">Actualidad</p>
            <h1 class="font-display text-5xl text-gray-900">Noticias y Novedades</h1>
        </div>

        {{-- Filtros por sección --}}
        @if($secciones->count())
        <div class="flex flex-wrap gap-2 mb-10">
            <a href="{{ route('public.novedades') }}"
               class="px-4 py-2 text-sm rounded-sm border transition-colors
                      {{ !request('seccion') ? 'border-green-800 bg-green-800 text-white' : 'border-gray-200 text-gray-600 hover:border-green-300' }}">
                Todas
            </a>
            @foreach($secciones as $sec)
            <a href="{{ route('public.novedades', ['seccion' => $sec->id]) }}"
               class="px-4 py-2 text-sm rounded-sm border transition-colors
                      {{ request('seccion') == $sec->id ? 'border-green-800 bg-green-800 text-white' : 'border-gray-200 text-gray-600 hover:border-green-300' }}"
               @if($sec->color_fondo && request('seccion') == $sec->id) style="background:{{ $sec->color_fondo }};border-color:{{ $sec->color_fondo }}" @endif>
                {{ $sec->nombre }}
            </a>
            @endforeach
        </div>
        @endif

        {{-- Grid de noticias --}}
        @if($noticias->count())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($noticias as $noticia)
            @php $imgPrincipal = $noticia->imagenes->where('es_principal', 1)->first() ?? $noticia->imagenes->first(); @endphp
            <article class="card-noticia bg-white border border-gray-100 rounded-sm overflow-hidden fade-up">
                <div class="relative h-52 bg-gray-100 overflow-hidden">
                    @if($imgPrincipal)
                    <img src="{{ asset('storage/' . $imgPrincipal->archivo) }}"
                         alt="{{ $imgPrincipal->alt_text ?? $noticia->titulo }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full" style="background:linear-gradient(135deg,#e8f0ec,#c8ddd2)"></div>
                    @endif
                    @if($noticia->seccion)
                    <span class="badge-categoria absolute top-3 left-3"
                          @if($noticia->seccion->color_fondo) style="background:{{ $noticia->seccion->color_fondo }};color:{{ $noticia->seccion->color_texto ?? '#fff' }}" @endif>
                        {{ $noticia->seccion->nombre }}
                    </span>
                    @endif
                </div>
                <div class="p-5">
                    <time class="text-xs text-gray-400 mb-2 block">
                        {{ \Carbon\Carbon::parse($noticia->fecha_publicacion)->locale('es')->isoFormat('D MMM YYYY') }}
                    </time>
                    <h2 class="font-display text-lg text-gray-900 mb-2 leading-snug">
                        <a href="{{ route('public.noticia', $noticia->slug) }}"
                           class="hover:text-green-800 transition-colors">
                            {{ $noticia->titulo }}
                        </a>
                    </h2>
                    @if($noticia->bajada)
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">{{ $noticia->bajada }}</p>
                    @endif
                    <a href="{{ route('public.noticia', $noticia->slug) }}"
                       class="inline-flex items-center gap-1 text-xs font-semibold text-green-800 mt-3 hover:text-green-600 transition-colors">
                        Leer más →
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Paginación --}}
        @if($noticias->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $noticias->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-20 text-gray-400">
            <p class="text-lg">No hay novedades publicadas por el momento.</p>
        </div>
        @endif
    </div>
</div>

@endsection