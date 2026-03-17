{{-- resources/views/components/module-card.blade.php --}}
@props(['module'])

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
    $icono = $iconos[$module->nombre] ?? '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />';
@endphp

<a href="{{ $module->path_home ?? '#' }}"
   class="module-card bg-white rounded-xl border border-gray-200 transition-all duration-200 p-5 flex flex-col group">

    <!-- Fila superior: ícono + título + descripción -->
    <div class="flex items-start space-x-4">
        <div class="module-icon h-10 w-10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5 transition-all duration-200"
             style="background-color: #e6f0eb;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="#1e5c3a" stroke-width="1.8">
                {!! $icono !!}
            </svg>
        </div>
        <div class="min-w-0 flex-1">
            <h3 class="font-semibold text-gray-800 text-sm leading-snug">{{ $module->nombre }}</h3>
            <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $module->descripcion ?? '' }}</p>
        </div>
    </div>

    <!-- Acceder: oculto por defecto, visible en hover -->
    <div class="module-acceder mt-3 overflow-hidden" style="max-height: 0; opacity: 0; transition: max-height 0.2s ease, opacity 0.2s ease;">
        <span class="inline-flex items-center text-xs font-semibold" style="color: #1e5c3a;">
            Acceder
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </span>
    </div>

</a>

<style>
    .module-card:hover {
        border-color: #b6d4c2;
        box-shadow: 0 4px 12px rgba(30, 92, 58, 0.1);
    }
    .module-card:hover .module-icon {
        background-color: #1e5c3a !important;
    }
    .module-card:hover .module-icon svg {
        stroke: white !important;
    }
    .module-card:hover .module-acceder {
        max-height: 30px !important;
        opacity: 1 !important;
    }
</style>