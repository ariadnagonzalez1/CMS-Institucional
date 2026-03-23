{{-- resources/views/components/sidebar.blade.php --}}
@props(['nombreUsuario', 'modulosPrincipales', 'modulosSecundarios'])

@php
    $iconos = [
        'Root'                   => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
        'Admin y Usuarios'       => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />',
        'Novedades y Noticias'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2" />',
        'Publicidad y Banners'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-2.236 9.168-5.5" />',
        'Audio/Video'            => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />',
        'Álbum de Fotos'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />',
        'Calendario Agenda'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
        'Mi Perfil'              => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />',
        'Contadores Web'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />',
        'Trámites y Formularios' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
    ];
@endphp

<aside class="fixed left-0 top-0 h-full w-64 flex flex-col shadow-xl" style="background-color: #196B4A;">

    <!-- Logo y título -->
    <div class="p-5 border-b" style="border-color: #2a6b4a;">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 hover:opacity-90 transition-opacity">
    <div class="h-10 w-10 rounded-xl overflow-hidden flex-shrink-0">
        <img src="{{ asset('images/colegios.jpg') }}" alt="Logo" class="h-full w-full object-contain">
    </div>
    <div>
        <h2 class="text-sm font-bold text-white leading-tight">Ingenieros de Formosa</h2>
        <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.5);">Panel de Administración</p>
    </div>
</a>
        </div>
    </div>

    <!-- Navegación -->
    <nav class="flex-1 overflow-y-auto p-3">
        <p class="text-xs font-semibold uppercase tracking-wider px-3 mb-3" style="color: rgba(255,255,255,0.35);">Módulos</p>

        <div class="space-y-0.5">

            @foreach($modulosPrincipales as $modulo)
                @php
                    $nombre = is_array($modulo) ? $modulo['nombre'] : $modulo->nombre;
                    $path   = is_array($modulo) ? ($modulo['path_home'] ?? '#') : ($modulo->path_home ?? '#');
                    $icono  = $iconos[$nombre] ?? '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />';
                    $isActive = request()->is('*' . $path . '*') && $path !== '#';
                @endphp
                <a href="{{ $path }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-150 sidebar-item {{ $isActive ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        {!! $icono !!}
                    </svg>
                    <span class="text-sm font-medium">{{ $nombre }}</span>
                    @if($isActive)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif
                </a>
            @endforeach

            @foreach($modulosSecundarios as $modulo)
                @php
                    $nombre = is_array($modulo) ? $modulo['nombre'] : $modulo->nombre;
                    $path   = is_array($modulo) ? ($modulo['path_home'] ?? '#') : ($modulo->path_home ?? '#');
                    $icono  = $iconos[$nombre] ?? '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />';
                    $isActive = request()->is('*' . $path . '*') && $path !== '#';
                @endphp
                <a href="{{ $path }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition-all duration-150 sidebar-item {{ $isActive ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        {!! $icono !!}
                    </svg>
                    <span class="text-sm font-medium">{{ $nombre }}</span>
                    @if($isActive)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif
                </a>
            @endforeach

        </div>
    </nav>

    <!-- Usuario al fondo -->
    <div class="p-4 border-t" style="border-color: #2a6b4a;">
        <div class="flex items-center space-x-3">
            <div class="h-9 w-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                 style="background-color: #B72033;">
                {{ substr($nombreUsuario, 0, 2) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ $nombreUsuario }}</p>
                <p class="text-xs" style="color: rgba(255,255,255,0.45);">Root</p>
            </div>
        </div>
    </div>

</aside>

<style>
    .sidebar-item {
        color: rgba(255, 255, 255, 0.65);
    }
    .sidebar-item svg {
        color: rgba(255, 255, 255, 0.55);
    }
    .sidebar-item:hover {
        background-color: rgba(255, 255, 255, 0.08);
        color: white;
    }
    .sidebar-item:hover svg {
        color: white;
    }
    .sidebar-item.active {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
    }
    .sidebar-item.active svg {
        color: white;
    }
</style>