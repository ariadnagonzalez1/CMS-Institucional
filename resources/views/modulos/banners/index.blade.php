{{-- resources/views/admin/banners/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Gestión de Banners')
@section('header-title', 'Gestión de Banners')

@section('content')

{{-- Flash messages --}}
@if(session('success'))
    <div id="flash-success"
         class="flex items-center gap-3 mb-5 px-4 py-3 rounded-xl text-sm font-medium text-emerald-800 bg-emerald-50 border border-emerald-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 shrink-0" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
        <button onclick="document.getElementById('flash-success').remove()"
                class="ml-auto text-emerald-400 hover:text-emerald-600">✕</button>
    </div>
@endif

@if(session('error'))
    <div id="flash-error"
         class="flex items-center gap-3 mb-5 px-4 py-3 rounded-xl text-sm font-medium text-red-800 bg-red-50 border border-red-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 shrink-0" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ session('error') }}
        <button onclick="document.getElementById('flash-error').remove()"
                class="ml-auto text-red-400 hover:text-red-600">✕</button>
    </div>
@endif

{{-- Toolbar: filtro + botón agregar --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">

    <div class="flex flex-wrap items-center gap-2">

        {{-- Filtro por sección --}}
        <form method="GET" action="{{ route('admin.banners.index') }}" id="form-filtros">
            <input type="hidden" name="buscar"   value="{{ request('buscar') }}">
            <input type="hidden" name="estado"   value="{{ request('estado') }}">
            <input type="hidden" name="por_pagina" value="{{ $porPagina }}">

            <select name="seccion"
                    onchange="document.getElementById('form-filtros').submit()"
                    class="text-sm text-gray-700 border border-gray-200 rounded-xl bg-white
                           focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400
                           appearance-none pr-8 pl-3 py-2 cursor-pointer"
                    style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");
                           background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1rem;">
                <option value="">Todas las secciones</option>
                @foreach($secciones as $sec)
                    <option value="{{ $sec->id }}" {{ request('seccion') == $sec->id ? 'selected' : '' }}>
                        {{ $sec->nombre }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Filtro por estado --}}
        <form method="GET" action="{{ route('admin.banners.index') }}" id="form-estado">
            <input type="hidden" name="buscar"     value="{{ request('buscar') }}">
            <input type="hidden" name="seccion"    value="{{ request('seccion') }}">
            <input type="hidden" name="por_pagina" value="{{ $porPagina }}">

            <select name="estado"
                    onchange="document.getElementById('form-estado').submit()"
                    class="text-sm text-gray-700 border border-gray-200 rounded-xl bg-white
                           focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400
                           appearance-none pr-8 pl-3 py-2 cursor-pointer"
                    style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");
                           background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1rem;">
                <option value="">Todos los estados</option>
                <option value="activo"   {{ request('estado') === 'activo'   ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </form>

        {{-- Contador de resultados --}}
        <span class="text-sm text-gray-400 pl-1">
            {{ $banners->total() }} {{ $banners->total() === 1 ? 'resultado' : 'resultados' }}
        </span>

        {{-- Limpiar filtros --}}
        @if(request()->hasAny(['seccion', 'estado', 'buscar']))
            <a href="{{ route('admin.banners.index') }}"
               class="text-xs text-gray-400 hover:text-gray-600 underline underline-offset-2">
                Limpiar filtros
            </a>
        @endif
    </div>

    {{-- Botón agregar --}}
    <button type="button"
            onclick="abrirModal('modal-crear-banner')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                   text-white shadow-sm hover:brightness-110 transition-all"
            style="background-color: #196B4A;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Agregar Publicidad
    </button>
</div>

{{-- Tabla --}}
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/3">
                    Ubicación
                </th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Descripción
                </th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">
                    Estado
                </th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($banners as $banner)
                <tr class="hover:bg-gray-50 transition-colors group">

                    {{-- Ubicación --}}
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-800 text-sm leading-tight">
                            {{ $banner->seccionBanner->nombre ?? '—' }}
                        </p>
                        @if($banner->tipoBanner)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $banner->tipoBanner->nombre }}</p>
                        @endif
                    </td>

                    {{-- Descripción --}}
                    <td class="px-5 py-4">
                        <span class="text-gray-700">
                            {{ $banner->titulo_epigrafe ?: ($banner->comentario ? \Str::limit($banner->comentario, 60) : '—') }}
                        </span>
                        @if($banner->fecha_inicio || $banner->fecha_fin)
                            <p class="text-xs text-gray-400 mt-0.5">
                                @if($banner->fecha_inicio)
                                    Desde {{ \Carbon\Carbon::parse($banner->fecha_inicio)->format('d/m/Y') }}
                                @endif
                                @if($banner->fecha_fin)
                                    hasta {{ \Carbon\Carbon::parse($banner->fecha_fin)->format('d/m/Y') }}
                                @endif
                            </p>
                        @endif
                    </td>

                    {{-- Estado (toggle) --}}
                    <td class="px-5 py-4">
                        <button
                            type="button"
                            onclick="toggleEstado({{ $banner->id }}, this)"
                            data-estado="{{ $banner->estado }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                   transition-all cursor-pointer border
                                   {{ $banner->estado === 'activo'
                                       ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
                                       : 'bg-red-50 text-red-600 border-red-200 hover:bg-red-100' }}">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ $banner->estado === 'activo' ? 'bg-emerald-500' : 'bg-red-400' }}">
                            </span>
                            {{ $banner->estado === 'activo' ? 'Activo' : 'Inactivo' }}
                        </button>
                    </td>

                    {{-- Acciones --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-1">

                            {{-- Editar --}}
                            <button type="button"
                                    onclick="abrirModalEditar({{ $banner->id }})"
                                    data-banner="{{ json_encode([
                                        'id'                => $banner->id,
                                        'seccion_banner_id' => $banner->seccion_banner_id,
                                        'tipo_banner_id'    => $banner->tipo_banner_id,
                                        'titulo_epigrafe'   => $banner->titulo_epigrafe,
                                        'comentario'        => $banner->comentario,
                                        'ruta_imagen'       => $banner->ruta_imagen,
                                        'borde_px'          => $banner->borde_px,
                                        'color_borde'       => $banner->color_borde,
                                        'alineacion'        => $banner->alineacion,
                                        'ajuste_ancho'      => $banner->ajuste_ancho,
                                        'tipo_link'         => $banner->tipo_link,
                                        'url_destino'       => $banner->url_destino,
                                        'tipo_ventana'      => $banner->tipo_ventana,
                                        'estado'            => $banner->estado,
                                        'orden'             => $banner->orden,
                                        'fecha_inicio'      => $banner->fecha_inicio,
                                        'fecha_fin'         => $banner->fecha_fin,
                                    ]) }}"
                                    title="Editar"
                                    class="p-2 rounded-lg text-gray-400 hover:text-emerald-700
                                           hover:bg-emerald-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                             m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>

                            {{-- Eliminar --}}
                            <button type="button"
                                    onclick="confirmarEliminar({{ $banner->id }}, '{{ addslashes($banner->seccionBanner->nombre ?? 'este banner') }}')"
                                    title="Eliminar"
                                    class="p-2 rounded-lg text-gray-400 hover:text-red-600
                                           hover:bg-red-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                             01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                             00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2
                                         0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0
                                         00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-medium">No hay banners para mostrar</p>
                            @if(request()->hasAny(['seccion', 'estado', 'buscar']))
                                <a href="{{ route('admin.banners.index') }}"
                                   class="text-xs text-emerald-600 hover:underline">Limpiar filtros</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Footer: paginación + por página --}}
@if($banners->hasPages() || true)
<div class="flex flex-wrap items-center justify-between gap-4 mt-4">

    {{-- Por página --}}
    <form method="GET" action="{{ route('admin.banners.index') }}" id="form-por-pagina">
        <input type="hidden" name="seccion"  value="{{ request('seccion') }}">
        <input type="hidden" name="estado"   value="{{ request('estado') }}">
        <input type="hidden" name="buscar"   value="{{ request('buscar') }}">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <span>Mostrar</span>
            <select name="por_pagina"
                    onchange="document.getElementById('form-por-pagina').submit()"
                    class="border border-gray-200 rounded-lg px-2 py-1 text-sm bg-white
                           focus:outline-none focus:ring-2 focus:ring-emerald-200 cursor-pointer">
                @foreach([10, 25, 50] as $n)
                    <option value="{{ $n }}" {{ $porPagina == $n ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
            <span>por página</span>
        </div>
    </form>

    {{-- Paginación --}}
    <div class="flex items-center gap-1 text-sm text-gray-600">
        <span class="mr-2">Página {{ $banners->currentPage() }} de {{ $banners->lastPage() }}</span>

        @if($banners->onFirstPage())
            <span class="p-1.5 rounded-lg text-gray-300 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $banners->previousPageUrl() }}"
               class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        @if($banners->hasMorePages())
            <a href="{{ $banners->nextPageUrl() }}"
               class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="p-1.5 rounded-lg text-gray-300 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </div>
</div>
@endif


{{-- ═══════════════════════════════════════
     MODAL — CREAR BANNER
═══════════════════════════════════════ --}}
<x-modal-crud
    id="modal-crear-banner"
    title="Agregar Publicidad"
    :action="route('admin.banners.store')"
    size="lg"
    icon="plus"
    submit-label="Guardar">

    <div class="grid grid-cols-2 gap-3">
        <div class="col-span-2">
            <x-form-label for="cb-seccion" required>Sección</x-form-label>
            <x-form-select id="cb-seccion" name="seccion_banner_id" required>
                <option value="">— Seleccioná una sección —</option>
                @foreach($secciones as $sec)
                    <option value="{{ $sec->id }}">{{ $sec->nombre }}</option>
                @endforeach
            </x-form-select>
        </div>

        <div class="col-span-2">
            <x-form-label for="cb-tipo">Tipo de Banner</x-form-label>
            <x-form-select id="cb-tipo" name="tipo_banner_id">
                <option value="">— Sin tipo —</option>
                @foreach($tiposBanner as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                @endforeach
            </x-form-select>
        </div>

        <div class="col-span-2">
            <x-form-label for="cb-titulo">Título / Epígrafe</x-form-label>
            <x-form-input id="cb-titulo" name="titulo_epigrafe" maxlength="255"
                          placeholder="Ej: Expo Ingeniería 2025" />
        </div>

        <div class="col-span-2">
            <x-form-label for="cb-ruta" required>Ruta de Imagen</x-form-label>
            <x-form-input id="cb-ruta" name="ruta_imagen" required maxlength="255"
                          :mono="true" placeholder="banners/imagen.jpg" />
        </div>

        <div>
            <x-form-label for="cb-fecha-inicio">Fecha Inicio</x-form-label>
            <x-form-input id="cb-fecha-inicio" name="fecha_inicio" type="date" />
        </div>

        <div>
            <x-form-label for="cb-fecha-fin">Fecha Fin</x-form-label>
            <x-form-input id="cb-fecha-fin" name="fecha_fin" type="date" />
        </div>

        <div>
            <x-form-label for="cb-orden">Orden</x-form-label>
            <x-form-input id="cb-orden" name="orden" type="number" min="0" value="0" />
        </div>

        <div>
            <x-form-label>Estado</x-form-label>
            <x-form-toggle name="estado" :checked="true" label="Activo" />
        </div>

        <div class="col-span-2">
            <x-form-label for="cb-url">URL de Destino</x-form-label>
            <x-form-input id="cb-url" name="url_destino" type="url" maxlength="500"
                          placeholder="https://..." />
        </div>

        <div class="col-span-2">
            <x-form-label for="cb-comentario">Comentario interno</x-form-label>
            <textarea id="cb-comentario" name="comentario" rows="2" maxlength="1000"
                      placeholder="Nota interna (no se muestra en el sitio)..."
                      class="w-full text-sm text-gray-800 outline-none transition-all placeholder-gray-400 resize-none"
                      style="padding: 0.625rem 0.875rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; background-color: #f9fafb;"
                      onfocus="this.style.backgroundColor='#ffffff'; this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.15)'"
                      onblur="this.style.backgroundColor='#f9fafb'; this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
            </textarea>
        </div>
    </div>
</x-modal-crud>


{{-- ═══════════════════════════════════════
     MODAL — EDITAR BANNER
═══════════════════════════════════════ --}}
<x-modal-crud
    id="modal-editar-banner"
    title="Editar Banner"
    method="PUT"
    size="lg"
    icon="edit"
    submit-label="Actualizar">

    <div class="grid grid-cols-2 gap-3">
        <div class="col-span-2">
            <x-form-label for="eb-seccion" required>Sección</x-form-label>
            <x-form-select id="eb-seccion" name="seccion_banner_id" required>
                <option value="">— Seleccioná una sección —</option>
                @foreach($secciones as $sec)
                    <option value="{{ $sec->id }}">{{ $sec->nombre }}</option>
                @endforeach
            </x-form-select>
        </div>

        <div class="col-span-2">
            <x-form-label for="eb-tipo">Tipo de Banner</x-form-label>
            <x-form-select id="eb-tipo" name="tipo_banner_id">
                <option value="">— Sin tipo —</option>
                @foreach($tiposBanner as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                @endforeach
            </x-form-select>
        </div>

        <div class="col-span-2">
            <x-form-label for="eb-titulo">Título / Epígrafe</x-form-label>
            <x-form-input id="eb-titulo" name="titulo_epigrafe" maxlength="255" />
        </div>

        <div class="col-span-2">
            <x-form-label for="eb-ruta" required>Ruta de Imagen</x-form-label>
            <x-form-input id="eb-ruta" name="ruta_imagen" required maxlength="255" :mono="true" />
        </div>

        <div>
            <x-form-label for="eb-fecha-inicio">Fecha Inicio</x-form-label>
            <x-form-input id="eb-fecha-inicio" name="fecha_inicio" type="date" />
        </div>

        <div>
            <x-form-label for="eb-fecha-fin">Fecha Fin</x-form-label>
            <x-form-input id="eb-fecha-fin" name="fecha_fin" type="date" />
        </div>

        <div>
            <x-form-label for="eb-orden">Orden</x-form-label>
            <x-form-input id="eb-orden" name="orden" type="number" min="0" />
        </div>

        <div>
            <x-form-label>Estado</x-form-label>
            <x-form-toggle name="estado" label="Activo" />
        </div>

        <div class="col-span-2">
            <x-form-label for="eb-url">URL de Destino</x-form-label>
            <x-form-input id="eb-url" name="url_destino" type="url" maxlength="500" />
        </div>

        <div class="col-span-2">
            <x-form-label for="eb-comentario">Comentario interno</x-form-label>
            <textarea id="eb-comentario" name="comentario" rows="2" maxlength="1000"
                      class="w-full text-sm text-gray-800 outline-none transition-all placeholder-gray-400 resize-none"
                      style="padding: 0.625rem 0.875rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; background-color: #f9fafb;"
                      onfocus="this.style.backgroundColor='#ffffff'; this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.15)'"
                      onblur="this.style.backgroundColor='#f9fafb'; this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
            </textarea>
        </div>
    </div>
</x-modal-crud>


{{-- ═══════════════════════════════════════
     MODAL — CONFIRMAR ELIMINAR
═══════════════════════════════════════ --}}
<div id="modal-eliminar-banner"
     role="dialog" aria-modal="true"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);"
     onclick="if(event.target===this) cerrarModal('modal-eliminar-banner')">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm ring-1 ring-gray-200">
        <div class="px-6 py-5 text-center">
            <div class="mx-auto mb-4 flex items-center justify-center w-12 h-12 rounded-full bg-red-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                             01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                             00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-800 mb-1">Eliminar banner</h3>
            <p class="text-sm text-gray-500 mb-5">
                ¿Confirmás que querés eliminar <strong id="nombre-eliminar" class="text-gray-700"></strong>?
                Esta acción no se puede deshacer.
            </p>
            <div class="flex justify-center gap-3">
                <button type="button"
                        onclick="cerrarModal('modal-eliminar-banner')"
                        class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600
                               hover:bg-gray-100 transition-colors border border-gray-200">
                    Cancelar
                </button>
                <form id="form-eliminar" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2 rounded-xl text-sm font-semibold text-white
                                   bg-red-600 hover:bg-red-700 transition-colors shadow-sm">
                        Sí, eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── Helpers de modal ─────────────────────────────────────────────────────
    function abrirModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Cerrar con Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[role="dialog"]:not(.hidden)').forEach(m => {
                cerrarModal(m.id);
            });
        }
    });

    // ── Abrir modal editar y poblar datos ────────────────────────────────────
    function abrirModalEditar(id) {
        const btn    = document.querySelector(`[onclick="abrirModalEditar(${id})"]`);
        const banner = JSON.parse(btn.dataset.banner);
        const modal  = document.getElementById('modal-editar-banner');
        const form   = document.getElementById('modal-editar-banner-form');

        // Acción dinámica
        form.action = `/admin/banners/${banner.id}`;

        // Poblar campos
        setVal(modal, '#eb-seccion',      banner.seccion_banner_id);
        setVal(modal, '#eb-tipo',         banner.tipo_banner_id ?? '');
        setVal(modal, '#eb-titulo',       banner.titulo_epigrafe ?? '');
        setVal(modal, '#eb-ruta',         banner.ruta_imagen ?? '');
        setVal(modal, '#eb-fecha-inicio', banner.fecha_inicio ?? '');
        setVal(modal, '#eb-fecha-fin',    banner.fecha_fin ?? '');
        setVal(modal, '#eb-orden',        banner.orden ?? 0);
        setVal(modal, '#eb-url',          banner.url_destino ?? '');
        setVal(modal, '#eb-comentario',   banner.comentario ?? '');

        // Toggle estado
        const toggle = modal.querySelector('input[name="estado"][type="checkbox"]');
        if (toggle) toggle.checked = banner.estado === 'activo';

        abrirModal('modal-editar-banner');
    }

    function setVal(ctx, selector, value) {
        const el = ctx.querySelector(selector);
        if (el) el.value = value ?? '';
    }

    // ── Confirmar eliminar ───────────────────────────────────────────────────
    function confirmarEliminar(id, nombre) {
        document.getElementById('nombre-eliminar').textContent = nombre;
        document.getElementById('form-eliminar').action = `/admin/banners/${id}`;
        abrirModal('modal-eliminar-banner');
    }

    // ── Toggle estado vía fetch ──────────────────────────────────────────────
    function toggleEstado(id, btn) {
        fetch(`/admin/banners/${id}/toggle-estado`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            const activo = data.estado === 'activo';
            btn.dataset.estado = data.estado;

            // Texto
            btn.querySelector('span:last-child') && (
                btn.innerHTML = `
                    <span class="w-1.5 h-1.5 rounded-full ${activo ? 'bg-emerald-500' : 'bg-red-400'}"></span>
                    ${activo ? 'Activo' : 'Inactivo'}
                `
            );

            // Clases del badge
            btn.className = btn.className
                .replace(/bg-(emerald|red)-\d+/g, '')
                .replace(/text-(emerald|red)-\d+/g, '')
                .replace(/border-(emerald|red)-\d+/g, '')
                .replace(/hover:bg-(emerald|red)-\d+/g, '')
                .trim();

            if (activo) {
                btn.classList.add('bg-emerald-50','text-emerald-700','border-emerald-200','hover:bg-emerald-100');
            } else {
                btn.classList.add('bg-red-50','text-red-600','border-red-200','hover:bg-red-100');
            }

            // Reconstruir innerHTML limpio
            btn.innerHTML = `
                <span class="w-1.5 h-1.5 rounded-full ${activo ? 'bg-emerald-500' : 'bg-red-400'}"></span>
                ${activo ? 'Activo' : 'Inactivo'}
            `;
        })
        .catch(() => alert('No se pudo actualizar el estado.'));
    }
</script>
@endpush