{{-- resources/views/public/inicio.blade.php --}}
@extends('layouts.public')

@section('title', $config->titulo_sitio ?? 'Inicio')

@section('content')

{{-- ===================== HERO SLIDER ===================== --}}
<section class="relative h-[88vh] min-h-[520px] overflow-hidden bg-gray-900">

    @php
        $slides = collect();
        foreach($banners as $banner) {
            $slides->push(['tipo' => 'banner', 'data' => $banner]);
        }
        foreach($noticiasHero as $noticia) {
            $slides->push(['tipo' => 'noticia', 'data' => $noticia]);
        }
    @endphp

    @forelse($slides as $i => $slide)
        <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}">

            @if($slide['tipo'] === 'banner')
                @php $banner = $slide['data']; @endphp
                <img src="{{ asset('storage/' . $banner->ruta_imagen) }}"
                     alt="{{ $banner->titulo_epigrafe }}"
                     class="absolute inset-0 w-full h-full object-cover">
                <div class="hero-overlay absolute inset-0"></div>
                <div class="absolute inset-0 flex items-center">
                    <div class="max-w-7xl mx-auto px-6 hero-text-wrap">
                        @if($banner->titulo_epigrafe)
                            <h1 class="font-display text-white text-5xl md:text-6xl lg:text-7xl font-bold leading-tight max-w-2xl mb-4"
                                style="text-shadow:0 2px 32px rgba(0,0,0,.4)">
                                {{ $banner->titulo_epigrafe }}
                            </h1>
                        @endif
                        @if($banner->comentario)
                            <p class="text-white/80 text-lg md:text-xl max-w-xl mt-2">{{ $banner->comentario }}</p>
                        @endif
                        @if($banner->url_destino)
                            <a href="{{ $banner->url_destino }}"
                               {{ $banner->tipo_ventana === '_blank' ? 'target=_blank' : '' }}
                               class="inline-block mt-6 px-7 py-3 bg-white text-green-900 font-semibold rounded-sm text-sm tracking-wide hover:bg-green-50 transition-colors">
                                Ver más
                            </a>
                        @endif
                    </div>
                </div>

            @else
                @php
                    $noticia = $slide['data'];
                    $img = $noticia->imagenes->where('es_principal', 1)->first() ?? $noticia->imagenes->first();
                @endphp

                @if($img)
                    <img src="{{ asset('storage/' . $img->archivo) }}"
                         alt="{{ $noticia->titulo }}"
                         class="absolute inset-0 w-full h-full object-cover">
                @else
                    <div class="absolute inset-0" style="background:linear-gradient(135deg,#1a3b2e 0%,#2a5a45 100%)"></div>
                @endif

                <div class="hero-overlay absolute inset-0"></div>
                <div class="absolute inset-0 flex items-center">
                    <div class="max-w-7xl mx-auto px-6 hero-text-wrap">
                        @if($noticia->seccion)
                            <span class="inline-block text-xs font-bold uppercase tracking-widest mb-3 px-3 py-1 rounded-sm"
                                  style="background:{{ $noticia->seccion->color_fondo ?: 'rgba(255,255,255,0.15)' }};
                                         color:{{ $noticia->seccion->color_texto ?: '#fff' }}">
                                {{ $noticia->seccion->nombre }}
                            </span>
                        @endif
                        @if($noticia->volanta)
                            <p class="text-white/70 text-sm uppercase tracking-widest mb-2">{{ $noticia->volanta }}</p>
                        @endif
                        <h1 class="font-display text-white text-4xl md:text-5xl lg:text-6xl font-bold leading-tight max-w-3xl mb-4"
                            style="text-shadow:0 2px 32px rgba(0,0,0,.4)">
                            {{ $noticia->titulo }}
                        </h1>
                        @if($noticia->bajada)
                            <p class="text-white/80 text-lg max-w-xl mt-2">{{ Str::limit($noticia->bajada, 120) }}</p>
                        @endif
                        <a href="{{ route('public.noticia', $noticia->slug) }}"
                           class="inline-block mt-6 px-7 py-3 bg-white text-green-900 font-semibold rounded-sm text-sm tracking-wide hover:bg-green-50 transition-colors">
                            Leer nota
                        </a>
                    </div>
                </div>
            @endif
        </div>
   @empty
    <div class="hero-slide active">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,#1a3b2e 0%,#2a5a45 100%)"></div>
        <div class="absolute inset-0 flex items-center">
            <div class="max-w-7xl mx-auto px-6">
                <h1 class="font-display text-white text-4xl md:text-6xl font-bold leading-tight max-w-2xl mb-4">
                    Colegio Público de Ingenieros de Formosa
                </h1>
                <p class="text-white/80 text-lg max-w-xl">
                    Regulamos y promovemos el ejercicio profesional de la ingeniería en la provincia de Formosa
                </p>
            </div>
        </div>
    </div>
@endempty

    {{-- Controles --}}
    @if($slides->count() > 1)
    <button id="hero-prev"
        class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/15 hover:bg-white/30 backdrop-blur-sm flex items-center justify-center transition-colors">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button id="hero-next"
        class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/15 hover:bg-white/30 backdrop-blur-sm flex items-center justify-center transition-colors">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    {{-- Dots --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        @foreach($slides as $i => $slide)
            <button class="hero-dot w-8 h-1.5 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-white' : 'bg-white/35' }}"
                    data-index="{{ $i }}"></button>
        @endforeach
    </div>
    @endif
</section>

{{-- ===================== NOTICIAS ===================== --}}
@if($noticias->count())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex items-end justify-between mb-10 fade-up">
            <div>
                <p class="section-label mb-2">Actualidad</p>
                <h2 class="font-display text-4xl text-gray-900">Noticias y Novedades</h2>
            </div>
            <a href="{{ route('public.novedades') }}"
               class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-green-800 hover:text-green-600 transition-colors">
                Ver todas las novedades
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($noticias as $i => $noticia)
            <article class="card-noticia bg-white border border-gray-100 rounded-sm overflow-hidden fade-up"
                     style="transition-delay: {{ $i * 80 }}ms">

                @php $imgPrincipal = $noticia->imagenes->where('es_principal', 1)->first() ?? $noticia->imagenes->first(); @endphp
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
                    <h3 class="font-display text-lg text-gray-900 mb-2 leading-snug">
                        <a href="{{ route('public.noticia', $noticia->slug) }}"
                           class="hover:text-green-800 transition-colors">
                            {{ $noticia->titulo }}
                        </a>
                    </h3>
                    @if($noticia->bajada)
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">{{ $noticia->bajada }}</p>
                    @endif
                </div>
            </article>
            @endforeach
        </div>

        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('public.novedades') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-800">
                Ver todas las novedades →
            </a>
        </div>
    </div>
</section>
@endif

{{-- ===================== INSTITUCIONAL ===================== --}}
<section class="py-20" style="background:var(--verde-suave)">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
            <div class="fade-up">
                <p class="section-label mb-2">Institucional</p>
                <h2 class="font-display text-3xl text-gray-900 mb-6">Colegio Público de Ingenieros</h2>
                <p class="text-sm font-semibold text-gray-700 mb-3">Accesos Rápidos</p>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('public.institucional') }}#autoridades"
                           class="flex items-center gap-3 px-4 py-3 bg-white border border-gray-100 text-sm text-gray-700 hover:border-green-200 hover:bg-white transition-colors rounded-sm">
                            <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="3" width="20" height="14" rx="2" stroke-width="2"/>
                                <path stroke-linecap="round" stroke-width="2" d="M8 21h8M12 17v4"/>
                            </svg>
                            Autoridades
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('public.institucional') }}#etica"
                           class="flex items-center gap-3 px-4 py-3 bg-white border border-gray-100 text-sm text-gray-700 hover:border-green-200 hover:bg-white transition-colors rounded-sm">
                            <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l9-3 9 3v6c0 5-4 9-9 10-5-1-9-5-9-10V6z"/>
                            </svg>
                            Código de Ética Profesional
                        </a>
                    </li>
                    <li>
                        <a href="#"
                           class="flex items-center gap-3 px-4 py-3 bg-white border border-gray-100 text-sm text-gray-700 hover:border-green-200 transition-colors rounded-sm">
                            <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Ley 1446
                        </a>
                    </li>
                    <li>
                        <a href="#"
                           class="flex items-center gap-3 px-4 py-3 bg-white border border-gray-100 text-sm text-gray-700 hover:border-green-200 transition-colors rounded-sm">
                            <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Ley 443
                        </a>
                    </li>
                </ul>
            </div>

            @if(isset($consejoDirectivo) && $consejoDirectivo->count())
            <div class="fade-up" style="transition-delay:80ms">
                <div class="bg-white border border-gray-100 rounded-sm p-6 h-full">
                    <p class="section-label mb-4">Consejo Directivo</p>
                    @foreach($consejoDirectivo as $miembro)
                    <div class="mb-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $miembro->cargo ?? '' }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $miembro->nombre }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(isset($tribunalFiscalizador) && $tribunalFiscalizador->count())
            <div class="fade-up" style="transition-delay:160ms">
                <div class="bg-white border border-gray-100 rounded-sm p-6 h-full">
                    <p class="section-label mb-4" style="color:#b56000">Tribunal Fiscalizador de Cuentas</p>
                    @foreach($tribunalFiscalizador as $miembro)
                    <div class="mb-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $miembro->cargo ?? '' }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $miembro->nombre }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(isset($tribunalEtica) && $tribunalEtica->count())
            <div class="fade-up" style="transition-delay:240ms">
                <div class="bg-white border border-gray-100 rounded-sm p-6 h-full">
                    <p class="section-label mb-4">Tribunal de Ética Profesional</p>
                    @foreach($tribunalEtica as $miembro)
                    <div class="mb-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $miembro->cargo ?? '' }}</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $miembro->nombre }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- ===================== DESCARGABLES ===================== --}}
@if($descargables->count())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-10 fade-up">
            <p class="section-label mb-2">Servicios</p>
            <h2 class="font-display text-4xl text-gray-900">Descargables y Documentación</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($descargables as $i => $desc)
            <a href="{{ route('public.descargable.download', $desc->id) }}"
               class="descargable-item flex items-center gap-4 p-5 border border-gray-100 rounded-sm bg-white fade-up"
               style="transition-delay: {{ $i * 60 }}ms">
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
</section>
@endif

{{-- ===================== MULTIMEDIA / VIDEOS ===================== --}}
@if($videos->count())
<section class="py-20" style="background:var(--verde-suave)">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-2 fade-up">
            <p class="section-label mb-2">Capacitación</p>
            <h2 class="font-display text-4xl text-gray-900">Videos Instructivos</h2>
            <p class="text-gray-500 mt-2 text-sm">Tutoriales y guías en video para facilitar tus trámites y gestiones ante el Colegio.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
            @foreach($videos as $i => $video)
            <div class="video-card group relative overflow-hidden rounded-sm cursor-pointer fade-up"
                 style="transition-delay: {{ $i * 80 }}ms"
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
</section>

<div id="video-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden" style="background:rgba(0,0,0,.75)">
    <div class="relative w-full max-w-3xl mx-4">
        <button onclick="closeVideoModal()" class="absolute -top-10 right-0 text-white text-sm font-medium">✕ Cerrar</button>
        <div id="video-embed" class="aspect-video bg-black rounded overflow-hidden"></div>
    </div>
</div>
@endif

{{-- ===================== CONTACTO ===================== --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-10 fade-up">
            <p class="section-label mb-2">Contacto</p>
            <h2 class="font-display text-4xl text-gray-900">Vías de Contacto y Redes Sociales</h2>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="space-y-3 fade-up">
                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white text-sm font-bold" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Sede Administrativa</p>
                        <p class="text-sm text-gray-800 font-medium">Av. 9 de Julio N 498</p>
                        <p class="text-xs text-gray-500">Lunes a Viernes 8:00 a 12:30hs</p>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Sede Social</p>
                        <p class="text-sm text-gray-800 font-medium">Av. Gutnisky N 1870</p>
                    </div>
                </div>
                @if($config->email_principal ?? false)
                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Correo Electrónico</p>
                        <a href="mailto:{{ $config->email_principal }}" class="text-sm text-green-800 font-medium hover:underline">{{ $config->email_principal }}</a>
                    </div>
                </div>
                @endif
                @if($config->whatsapp ?? false)
                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.115.553 4.1 1.522 5.825L.055 23.454a.5.5 0 00.491.61.497.497 0 00.139-.02l5.787-1.494A11.937 11.937 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.882a9.87 9.87 0 01-5.022-1.37l-.36-.213-3.735.963.99-3.636-.234-.373A9.837 9.837 0 012.118 12C2.118 6.54 6.54 2.118 12 2.118S21.882 6.54 21.882 12 17.46 21.882 12 21.882z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">WhatsApp</p>
                        <a href="https://wa.me/{{ preg_replace('/\D/','',$config->whatsapp) }}"
                           target="_blank" class="text-sm text-green-800 font-medium hover:underline">{{ $config->whatsapp }}</a>
                    </div>
                </div>
                @endif
                @if($config->telefono ?? false)
                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Tel. Fijo</p>
                        <a href="tel:{{ $config->telefono }}" class="text-sm text-gray-800 font-medium">{{ $config->telefono }}</a>
                    </div>
                </div>
                @endif
            </div>

            <div class="fade-up" style="transition-delay:100ms">
                <div class="border border-gray-100 rounded-sm p-8">
                    <h3 class="font-display text-xl text-gray-900 mb-2">Buzón para empresas que requieran Ingenieros</h3>
                    <p class="text-sm text-gray-500 mb-6">Dejá tu mensaje aquí para solicitar ingenieros. Te acompañamos a buscar el indicado.</p>
                    <form action="{{ route('public.contacto.enviar') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="nombre" placeholder="Nombre"
                                   class="col-span-1 px-4 py-3 text-sm border border-gray-200 rounded-sm focus:outline-none focus:border-green-400 focus:ring-1 focus:ring-green-100 w-full" required>
                            <input type="email" name="email" placeholder="Correo electrónico"
                                   class="col-span-1 px-4 py-3 text-sm border border-gray-200 rounded-sm focus:outline-none focus:border-green-400 focus:ring-1 focus:ring-green-100 w-full" required>
                        </div>
                        <input type="text" name="telefono" placeholder="Teléfono"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-sm focus:outline-none focus:border-green-400 focus:ring-1 focus:ring-green-100">
                        <textarea name="mensaje" placeholder="Mensaje..." rows="5"
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-sm focus:outline-none focus:border-green-400 focus:ring-1 focus:ring-green-100 resize-none" required></textarea>
                        <button type="submit"
                                class="w-full py-3 text-sm font-semibold text-white rounded-sm flex items-center justify-center gap-2 hover:opacity-90 transition-opacity"
                                style="background:var(--negro)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Enviar mensaje
                        </button>
                        @if(session('success'))
                        <p class="text-green-700 text-sm text-center">{{ session('success') }}</p>
                        @endif
                        @if($errors->any())
                        <p class="text-red-600 text-sm text-center">{{ $errors->first() }}</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        @if($config->instagram_url || $config->youtube_url || $config->twitter_url || $config->facebook_url)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-10 fade-up">
            @if($config->instagram_url)
            <a href="{{ $config->instagram_url }}" target="_blank" rel="noopener"
               class="red-social-btn flex items-center justify-center py-5 rounded-sm text-white" style="background:#e1306c">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                </svg>
            </a>
            @endif
            @if($config->youtube_url)
            <a href="{{ $config->youtube_url }}" target="_blank" rel="noopener"
               class="red-social-btn flex items-center justify-center py-5 rounded-sm text-white" style="background:#ff0000">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/>
                </svg>
            </a>
            @endif
            @if($config->twitter_url)
            <a href="{{ $config->twitter_url }}" target="_blank" rel="noopener"
               class="red-social-btn flex items-center justify-center py-5 rounded-sm text-white" style="background:#1da1f2">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
            </a>
            @endif
            @if($config->facebook_url)
            <a href="{{ $config->facebook_url }}" target="_blank" rel="noopener"
               class="red-social-btn flex items-center justify-center py-5 rounded-sm text-white" style="background:#1877f2">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </a>
            @endif
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
// Hero slider
(function() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    if (slides.length <= 1) return;

    let current = 0;
    let timer;

    function goTo(index) {
        slides[current].classList.remove('active');
        dots[current]?.classList.replace('bg-white', 'bg-white/35');
        current = (index + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current]?.classList.replace('bg-white/35', 'bg-white');
    }

    function startTimer() {
        clearInterval(timer);
        timer = setInterval(() => goTo(current + 1), 5500);
    }

    document.getElementById('hero-prev')?.addEventListener('click', () => { goTo(current - 1); startTimer(); });
    document.getElementById('hero-next')?.addEventListener('click', () => { goTo(current + 1); startTimer(); });
    dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); startTimer(); }));

    startTimer();
})();

// Video modal
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