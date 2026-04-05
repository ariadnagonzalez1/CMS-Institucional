{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $config->titulo_sitio ?? 'Colegio Público de Ingenieros de Formosa')</title>
    <meta name="description" content="{{ $config->descripcion_sitio ?? '' }}">
    <meta name="keywords" content="{{ $config->palabras_clave ?? '' }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --verde-oscuro: #1a3b2e;
            --verde-medio: #2a5a45;
            --verde-claro: #e8f0ec;
            --verde-suave: #f2f7f4;
            --rojo: #b22222;
            --blanco: #ffffff;
            --negro: #111111;
            --gris-texto: #4a4a4a;
            --gris-borde: #e2e8e4;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            color: var(--negro);
            background: var(--blanco);
            overflow-x: hidden;
        }

        h1, h2, h3, .font-display {
            font-family: 'Playfair Display', Georgia, serif;
        }

        /* NAV */
        #nav-public {
            background: var(--verde-oscuro);
            position: sticky;
            top: 0;
            z-index: 100;
            transition: box-shadow .3s;
        }
        #nav-public.scrolled {
            box-shadow: 0 4px 24px rgba(0,0,0,.25);
        }
        .nav-topbar {
            background: rgba(255,255,255,.06);
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .nav-link {
            color: rgba(255,255,255,.82);
            font-size: .875rem;
            font-weight: 500;
            letter-spacing: .01em;
            padding: .5rem .25rem;
            border-bottom: 2px solid transparent;
            transition: color .2s, border-color .2s;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            border-bottom-color: rgba(255,255,255,.5);
        }

        /* HERO */
        .hero-slide {
            position: absolute; inset: 0;
            opacity: 0;
            transition: opacity 1s ease;
        }
        .hero-slide.active { opacity: 1; }
        .hero-overlay {
            background: linear-gradient(
                to right,
                rgba(10,28,20,.82) 0%,
                rgba(10,28,20,.45) 55%,
                rgba(10,28,20,.18) 100%
            );
        }

        /* CARDS */
        .card-noticia {
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .card-noticia:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(26,59,46,.12);
        }

        /* BADGE categoría */
        .badge-categoria {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .2rem .55rem;
            border-radius: 3px;
            background: var(--verde-oscuro);
            color: #fff;
        }

        /* SECCIÓN separadora */
        .section-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: var(--verde-medio);
        }

        /* DESCARGABLES */
        .descargable-item {
            transition: background .18s, transform .18s;
        }
        .descargable-item:hover {
            background: var(--verde-claro);
            transform: translateX(4px);
        }

        /* VIDEO CARDS */
        .video-card .play-btn {
            transition: transform .2s, background .2s;
        }
        .video-card:hover .play-btn {
            transform: scale(1.12);
            background: var(--verde-oscuro);
        }

        /* AUTORIDADES */
        .autoridad-card {
            border-left: 3px solid var(--verde-medio);
        }

        /* REDES SOCIALES */
        .red-social-btn {
            transition: opacity .2s, transform .2s;
        }
        .red-social-btn:hover {
            opacity: .88;
            transform: translateY(-2px);
        }

        /* FOOTER */
        footer {
            background: var(--verde-oscuro);
            color: rgba(255,255,255,.75);
        }
        footer a { color: rgba(255,255,255,.65); transition: color .2s; }
        footer a:hover { color: #fff; }

        /* ANIMACIONES entrada */
        .fade-up {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mobile menu */
        #mobile-menu { display: none; }
        #mobile-menu.open { display: block; }

        @media (max-width: 768px) {
            .hero-text-wrap h1 { font-size: 2.25rem !important; }
        }
    </style>
</head>
<body>

{{-- ===================== NAVBAR ===================== --}}
<nav id="nav-public">

    {{-- FILA 1: nombre institución | links utilitarios --}}
    <div style="background:rgba(0,0,0,.15); border-bottom:1px solid rgba(255,255,255,.08);">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between" style="height:34px;">

            {{-- Nombre institución (izquierda) --}}
            <a href="{{ route('public.inicio') }}"
               class="text-white font-semibold leading-tight hover:opacity-80 transition-opacity"
               style="font-size:.82rem; line-height:1.25;">
                Colegio Público de<br>Ingenieros de Formosa
            </a>

            {{-- Links utilitarios (derecha) --}}
            <div class="hidden sm:flex items-center gap-5 text-xs font-medium">
                @if($config->google_maps_url ?? false)
                <a href="{{ $config->google_maps_url }}" target="_blank"
                   class="flex items-center gap-1.5 transition-colors"
                   style="color:#6ee7b7;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    IngeLink
                </a>
                @else
                <span class="flex items-center gap-1.5" style="color:#6ee7b7;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    IngeLink
                </span>
                @endif

                <a href="{{ route('public.servicios') }}"
                   class="flex items-center gap-1.5 text-white/75 hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                    </svg>
                    Plataforma Digital
                </a>

                @php
                    $wsp = $config->whatsapp ?? '3704043114';
                @endphp

                <a href="https://wa.me/{{ preg_replace('/\D/', '', $wsp) }}"
                   target="_blank"
                   class="flex items-center gap-1.5 text-white/75 hover:text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12 0C5.373 0 0 5.373 0 12c0 2.115.553 4.1 1.522 5.825L.055 23.454a.5.5 0 00.491.61.497.497 0 00.139-.02l5.787-1.494A11.937 11.937 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.882a9.87 9.87 0 01-5.022-1.37l-.36-.213-3.735.963.99-3.636-.234-.373A9.837 9.837 0 012.118 12C2.118 6.54 6.54 2.118 12 2.118S21.882 6.54 21.882 12 17.46 21.882 12 21.882z"/>
                    </svg>
                    {{ $wsp }}
                </a>
            </div>
        </div>
    </div>

    {{-- FILA 2: Logo centrado --}}
    <div class="flex justify-center py-2" style="border-bottom:1px solid rgba(255,255,255,.08);">
        <a href="{{ route('public.inicio') }}" class="flex items-center justify-center">
            @php
                $logoPath = public_path('storage/logo.png');
                $logoSvg  = public_path('storage/logo.svg');
            @endphp
            @if(file_exists($logoPath) || file_exists($logoSvg))
            <img src="{{ asset(file_exists($logoPath) ? 'storage/logo.png' : 'storage/logo.svg') }}"
                 alt="Logo Colegio"
                 class="object-contain rounded-full"
                 style="width:46px;height:46px;background:#fff;padding:3px;">
            @else
            <div style="width:46px;height:46px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <span style="font-family:'Playfair Display',serif;font-size:1.35rem;font-weight:900;color:#1a3b2e;line-height:1;">C</span>
            </div>
            @endif
        </a>
    </div>

    {{-- FILA 3: Menú de navegación centrado --}}
    <div class="hidden md:block">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-center gap-10" style="height:36px;">
                <a href="{{ route('public.institucional') }}"
                   class="nav-link {{ request()->routeIs('public.institucional') ? 'active' : '' }}">
                    Institucionales
                </a>
                <a href="{{ route('public.servicios') }}"
                   class="nav-link {{ request()->routeIs('public.servicios') ? 'active' : '' }}">
                    Servicios Online
                </a>
                <a href="{{ route('public.novedades') }}"
                   class="nav-link {{ request()->routeIs('public.novedades*') ? 'active' : '' }}">
                    Novedades
                </a>
                <a href="{{ route('public.contacto') }}"
                   class="nav-link {{ request()->routeIs('public.contacto') ? 'active' : '' }}">
                    Contactos y Redes
                </a>
            </div>
        </div>
    </div>

    {{-- Mobile: burger en fila 2 --}}
    <div class="md:hidden flex items-center justify-between px-4 py-2">
        <span></span>{{-- spacer --}}
        <button id="burger" class="text-white p-2" aria-label="Menú">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path id="burger-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path id="burger-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Mobile menu desplegable --}}
    <div id="mobile-menu" class="md:hidden border-t border-white/10">
        <div class="px-4 py-3 flex flex-col">
            <a href="{{ route('public.institucional') }}" class="nav-link py-3 border-b border-white/10">Institucionales</a>
            <a href="{{ route('public.servicios') }}"     class="nav-link py-3 border-b border-white/10">Servicios Online</a>
            <a href="{{ route('public.novedades') }}"     class="nav-link py-3 border-b border-white/10">Novedades</a>
            <a href="{{ route('public.contacto') }}"      class="nav-link py-3">Contactos y Redes</a>
        </div>
    </div>

</nav>

{{-- ===================== MAIN CONTENT ===================== --}}
<main>
    @yield('content')
</main>

{{-- ===================== FOOTER ===================== --}}
<footer class="pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-12 border-b border-white/10">

            {{-- Col 1 --}}
            <div>
                <p class="font-display text-white text-xl font-bold mb-3">
                    Colegio Público de Ingenieros de Formosa
                </p>
                <p class="text-sm leading-relaxed opacity-70">
                    {{ $config->descripcion_sitio ?? 'Regulamos y promovemos el ejercicio profesional de la ingeniería en la provincia de Formosa, garantizando calidad, ética y compromiso con la sociedad.' }}
                </p>
            </div>

            {{-- Col 2: Enlaces --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-green-300 mb-4">Enlaces</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('public.institucional') }}">Institucionales</a></li>
                    <li><a href="{{ route('public.servicios') }}">Servicios</a></li>
                    <li><a href="{{ route('public.novedades') }}">Novedades</a></li>
                    <li><a href="{{ route('public.contacto') }}">Contacto</a></li>
                </ul>
            </div>

            {{-- Col 3: Legal --}}
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-green-300 mb-4">Legal</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="#">Ley 1446</a></li>
                    <li><a href="#">Ley 443</a></li>
                    <li><a href="#">Código de Ética</a></li>
                </ul>
            </div>
        </div>

        <div class="pt-8 text-center text-xs opacity-50">
            © {{ date('Y') }} Colegio Público de Ingenieros de Formosa. Todos los derechos reservados.
        </div>
    </div>
</footer>

<script>
    // Sticky nav shadow
    window.addEventListener('scroll', () => {
        document.getElementById('nav-public').classList.toggle('scrolled', window.scrollY > 10);
    });

    // Mobile menu toggle
    const burger = document.getElementById('burger');
    const mobileMenu = document.getElementById('mobile-menu');
    const burgerOpen = document.getElementById('burger-open');
    const burgerClose = document.getElementById('burger-close');
    burger.addEventListener('click', () => {
        mobileMenu.classList.toggle('open');
        burgerOpen.classList.toggle('hidden');
        burgerClose.classList.toggle('hidden');
    });

    // Fade-up on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>

@stack('scripts')
</body>
</html>