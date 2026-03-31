{{-- resources/views/modulos/multimedia/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Audio/Video')
@section('header-title', 'Audio/Video')

@section('content')
<div class="space-y-5">
    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 text-emerald-800 flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-600">✕</button>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2">
            {{-- Filtro por búsqueda --}}
            <form method="GET" action="{{ route('admin.multimedia.index') }}" id="form-buscar" class="relative">
                <input type="text" 
                       name="buscar" 
                       value="{{ request('buscar') }}"
                       placeholder="Buscar por tema..."
                       class="pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl w-64 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                       onchange="this.form.submit()">
                <svg class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>

            {{-- Filtro por sección --}}
            <select name="seccion" 
                    onchange="window.location.href='{{ route('admin.multimedia.index') }}?seccion='+this.value+'&buscar={{ request('buscar') }}&tipo={{ request('tipo') }}&estado={{ request('estado') }}'"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white">
                <option value="">Todas las secciones</option>
                @foreach($secciones as $seccion)
                    <option value="{{ $seccion->id }}" {{ request('seccion') == $seccion->id ? 'selected' : '' }}>
                        {{ $seccion->nombre }}
                    </option>
                @endforeach
            </select>

            {{-- Filtro por tipo --}}
            <select name="tipo" 
                    onchange="window.location.href='{{ route('admin.multimedia.index') }}?tipo='+this.value+'&seccion={{ request('seccion') }}&buscar={{ request('buscar') }}&estado={{ request('estado') }}'"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white">
                <option value="">Todos los tipos</option>
                @foreach($tiposMultimedia as $tipo)
                    <option value="{{ $tipo->id }}" {{ request('tipo') == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                @endforeach
            </select>

            {{-- Filtro por estado --}}
            <select name="estado" 
                    onchange="window.location.href='{{ route('admin.multimedia.index') }}?estado='+this.value+'&seccion={{ request('seccion') }}&buscar={{ request('buscar') }}&tipo={{ request('tipo') }}'"
                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white">
                <option value="">Todos los estados</option>
                <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
            </select>

            {{-- Contador --}}
            <span class="text-sm text-gray-500 ml-2">
                {{ $multimedia->total() }} {{ $multimedia->total() === 1 ? 'elemento' : 'elementos' }}
            </span>
        </div>

        {{-- Botón agregar --}}
        <button type="button"
                onclick="abrirModalCrear()"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Agregar Audio/Video
        </button>
    </div>

    {{-- Tabla de multimedia --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Fecha</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Tema</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Sección</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Tipo</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 w-20">Estado</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 w-24">Acciones</th>
                     </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($multimedia as $item)
                        <tr class="hover:bg-gray-50 transition group">
                            <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->fecha_publicacion)->format('d-m-Y') }}
                             </td>
                            <td class="px-5 py-4">
    <div class="flex items-center gap-2">
        @if($item->tipo && !$item->tipo->es_embed && $item->archivo)
            <svg class="h-4 w-4 text-purple-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
            </svg>
        @elseif($item->tipo && $item->tipo->es_embed)
            <svg class="h-4 w-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
            </svg>
        @endif

        {{-- ← acá va el botón en lugar del <p> --}}
        <button onclick="verPreview(
                            '{{ $item->tipo && $item->tipo->es_embed ? 'embed' : 'mp3' }}',
                            '{{ addslashes($item->codigo_embed ?? '') }}',
                            '{{ $item->archivo ? Storage::url($item->archivo) : '' }}',
                            '{{ addslashes($item->tema) }}'
                        )"
                class="font-medium text-gray-800 hover:text-emerald-700 hover:underline text-left transition">
            {{ $item->tema }}
        </button>
    </div>
</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $item->seccion->nombre ?? '—' }}
                                </span>
                             </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $item->tipo && $item->tipo->es_embed ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $item->tipo->nombre ?? '—' }}
                                </span>
                             </td>
                            <td class="px-5 py-4 text-center">
                                <button onclick="toggleEstado({{ $item->id }}, this)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition-all cursor-pointer
                                               {{ $item->estado ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item->estado ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ $item->estado ? 'Activo' : 'Inactivo' }}
                                </button>
                             </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button onclick="abrirModalEditar({{ $item->id }})"
                                            data-id="{{ $item->id }}"
                                            class="p-2 rounded-lg text-gray-400 hover:text-emerald-700 hover:bg-emerald-50 transition"
                                            title="Editar">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="confirmarEliminar({{ $item->id }}, '{{ addslashes($item->tema) }}')"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                                            title="Eliminar">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                             </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                    </svg>
                                    <p class="text-sm font-medium">No hay contenido multimedia</p>
                                    <button onclick="abrirModalCrear()" class="text-sm text-emerald-600 hover:underline">
                                        Agregar el primer contenido
                                    </button>
                                </div>
                             </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.multimedia.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="buscar" value="{{ request('buscar') }}">
                <input type="hidden" name="seccion" value="{{ request('seccion') }}">
                <input type="hidden" name="tipo" value="{{ request('tipo') }}">
                <input type="hidden" name="estado" value="{{ request('estado') }}">
                <select name="por_pagina" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded-lg px-2 py-1 bg-white">
                    @foreach([10, 25, 50] as $n)
                        <option value="{{ $n }}" {{ $porPagina == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <span class="text-sm text-gray-500">por página</span>
            </form>
        </div>
        {{ $multimedia->appends(request()->query())->links() }}
    </div>
</div>

{{-- resources/views/modulos/multimedia/index.blade.php --}}
{{-- ... todo el código anterior se mantiene igual hasta los modales --}}

{{-- Modal Crear (carga create.blade.php) --}}
<div id="modal-crear"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);"
     onclick="if(event.target===this) cerrarModal('modal-crear')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" id="modal-crear-content">
        <div class="p-6 text-center text-gray-500">
            <svg class="animate-spin h-8 w-8 mx-auto mb-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Cargando formulario...
        </div>
    </div>
</div>

{{-- Modal Editar (carga edit.blade.php) --}}
<div id="modal-editar"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);"
     onclick="if(event.target===this) cerrarModal('modal-editar')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" id="modal-editar-content">
        <div class="p-6 text-center text-gray-500">
            <svg class="animate-spin h-8 w-8 mx-auto mb-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Cargando formulario...
        </div>
    </div>
</div>

{{-- Modal Preview --}}
<div id="modal-preview"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background-color: rgba(0,0,0,0.7); backdrop-filter: blur(3px);"
     onclick="if(event.target===this) cerrarPreview()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden">
        {{-- Header --}}
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h3 id="preview-titulo" class="text-base font-semibold text-gray-800 truncate pr-4"></h3>
            <button onclick="cerrarPreview()" class="text-gray-400 hover:text-gray-600 transition shrink-0">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        {{-- Contenido --}}
        <div id="preview-contenido" class="p-6">
            {{-- Se llena dinámicamente --}}
        </div>
    </div>
</div>

{{-- Modal Eliminar --}}
<div id="modal-eliminar"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);"
     onclick="if(event.target===this) cerrarModal('modal-eliminar')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
        <div class="p-6 text-center">
            <div class="mx-auto mb-4 flex items-center justify-center w-12 h-12 rounded-full bg-red-50">
                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Eliminar contenido</h3>
            <p class="text-sm text-gray-500 mb-5">
                ¿Estás seguro de eliminar "<span id="multimedia-tema-eliminar" class="font-medium text-gray-700"></span>"?
                Esta acción no se puede deshacer.
            </p>
            <div class="flex justify-center gap-3">
                <button onclick="cerrarModal('modal-eliminar')"
                        class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition border border-gray-200">
                    Cancelar
                </button>
                <form id="form-eliminar" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
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
    // ─── Utilidades ───────────────────────────────────────────────
    function cerrarModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Ejecuta los <script> que vienen dentro de HTML inyectado por fetch
    function ejecutarScripts(contenedor) {
        contenedor.querySelectorAll('script').forEach(function(scriptViejo) {
            const scriptNuevo = document.createElement('script');
            scriptNuevo.textContent = scriptViejo.textContent;
            scriptViejo.parentNode.replaceChild(scriptNuevo, scriptViejo);
        });
    }

    // ─── Lógica de campos compartida ──────────────────────────────
    // prefijo: 'crear' o 'edit'
    function inicializarCamposMultimedia(prefijo) {
        const selectTipo        = document.getElementById(prefijo + '-tipo');
        const campoEmbed        = document.getElementById(prefijo + '-campo-embed');
        const campoArchivo      = document.getElementById(prefijo + '-campo-archivo');
        const archivoInput      = document.getElementById(prefijo + '-archivo-input');
        const nombreArchivoSpan = document.getElementById(prefijo + '-nombre-archivo');
        const nombreArchivo     = document.getElementById(prefijo + '-archivo-nombre');
        const embedTextarea     = document.getElementById(prefijo + '-embed');

        if (!selectTipo) return;

        function actualizarCampos() {
            const opt        = selectTipo.options[selectTipo.selectedIndex];
            const esEmbed    = opt ? opt.getAttribute('data-es-embed') === '1' : false;
            const tieneValor = opt ? opt.value !== '' : false;

            if (campoEmbed)   campoEmbed.classList.toggle('hidden', !(esEmbed && tieneValor));
            if (campoArchivo) campoArchivo.classList.toggle('hidden', !(!esEmbed && tieneValor));

            if (embedTextarea) embedTextarea.required = esEmbed && tieneValor;
            if (archivoInput)  archivoInput.required  = !esEmbed && tieneValor;
        }

        selectTipo.addEventListener('change', actualizarCampos);
        actualizarCampos(); // estado inicial

        if (archivoInput) {
            archivoInput.addEventListener('change', function() {
                const tiene = this.files && this.files[0];
                if (nombreArchivo)     nombreArchivo.textContent = tiene ? this.files[0].name : '';
                if (nombreArchivoSpan) nombreArchivoSpan.classList.toggle('hidden', !tiene);
            });
        }
    }

    // ─── Submit AJAX compartido ────────────────────────────────────
    function inicializarSubmit(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al guardar');
                }
            })
            .catch(() => alert('Error al guardar'));
        });
    }

    // ─── Modal Crear ───────────────────────────────────────────────
    function abrirModalCrear() {
        const modal   = document.getElementById('modal-crear');
        const content = document.getElementById('modal-crear-content');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        fetch('{{ route('admin.multimedia.create') }}')
            .then(r => r.text())
            .then(html => {
                content.innerHTML = html;
                inicializarCamposMultimedia('crear');
                inicializarSubmit('form-crear');
            })
            .catch(() => {
                content.innerHTML = '<div class="p-6 text-center text-red-500">Error al cargar el formulario</div>';
            });
    }

    // ─── Modal Editar ──────────────────────────────────────────────
    function abrirModalEditar(id) {
        const modal   = document.getElementById('modal-editar');
        const content = document.getElementById('modal-editar-content');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        fetch(`/admin/multimedia/${id}/edit`)
            .then(r => r.text())
            .then(html => {
                content.innerHTML = html;
                inicializarCamposMultimedia('edit');
                inicializarSubmit('form-editar');
            })
            .catch(() => {
                content.innerHTML = '<div class="p-6 text-center text-red-500">Error al cargar el formulario</div>';
            });
    }

    // ─── Modal Eliminar ────────────────────────────────────────────
    function confirmarEliminar(id, tema) {
        document.getElementById('multimedia-tema-eliminar').textContent = tema;
        document.getElementById('form-eliminar').action = `/admin/multimedia/${id}`;
        document.getElementById('modal-eliminar').classList.remove('hidden');
    }

    // ─── Toggle Estado ─────────────────────────────────────────────
    function toggleEstado(id, btn) {
        fetch(`/admin/multimedia/${id}/toggle-estado`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (data.estado) {
                    btn.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo`;
                    btn.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200';
                } else {
                    btn.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo`;
                    btn.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200';
                }
            }
        })
        .catch(() => alert('Error al actualizar el estado'));
    }
    function verPreview(tipo, embed, archivoUrl, tema) {
    document.getElementById('preview-titulo').textContent = tema;
    const contenido = document.getElementById('preview-contenido');

    if (tipo === 'embed' && embed) {
        let videoId = null;

        const matchWatch = embed.match(/[?&]v=([\w-]{11})/);
        const matchShort = embed.match(/youtu\.be\/([\w-]{11})/);
        const matchEmbed = embed.match(/youtube\.com\/embed\/([\w-]{11})/);

        if (matchWatch)      videoId = matchWatch[1];
        else if (matchShort) videoId = matchShort[1];
        else if (matchEmbed) videoId = matchEmbed[1];

        if (videoId) {
            contenido.innerHTML = `
                <a href="https://www.youtube.com/watch?v=${videoId}"
                   target="_blank"
                   class="block relative w-full rounded-xl overflow-hidden group cursor-pointer"
                   style="padding-bottom: 56.25%;">
                    <img src="https://img.youtube.com/vi/${videoId}/maxresdefault.jpg"
                         onerror="this.src='https://img.youtube.com/vi/${videoId}/hqdefault.jpg'"
                         class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 group-hover:bg-opacity-50 transition">
                        <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="h-7 w-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                </a>
                <p class="text-center text-xs text-gray-400 mt-3">Haga clic para ver el video en YouTube</p>`;
        } else {
            contenido.innerHTML = `<p class="text-center text-gray-400 py-8">URL de YouTube no válida.</p>`;
        }

    } else if (tipo === 'mp3' && archivoUrl) {
        contenido.innerHTML = `
            <div class="flex flex-col items-center gap-4 py-4">
                <svg class="h-14 w-14 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                </svg>
                <audio controls class="w-full">
                    <source src="${archivoUrl}" type="audio/mpeg">
                    Tu navegador no soporta el reproductor de audio.
                </audio>
            </div>`;
    } else {
        contenido.innerHTML = `<p class="text-center text-gray-400 py-8">No hay contenido para previsualizar.</p>`;
    }

    document.getElementById('modal-preview').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function cerrarPreview() {
    document.getElementById('preview-contenido').innerHTML = '';
    document.getElementById('modal-preview').classList.add('hidden');
    document.body.style.overflow = '';
}

    // ─── Escape para cerrar modales ────────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="modal-"]:not(.hidden)').forEach(modal => {
                cerrarModal(modal.id);
            });
        }
    });
</script>
@endpush