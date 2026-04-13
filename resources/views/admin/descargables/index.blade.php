{{-- resources/views/admin/descargables/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Trámites y Formularios')
@section('header-title', 'Trámites y Formularios')

@section('content')

{{-- Flash messages --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
        <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-lg">
        <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

{{-- Barra de filtros + botón agregar --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" action="{{ route('admin.descargables.index') }}"
          class="flex flex-wrap gap-3 items-end">

        {{-- Búsqueda por tema --}}
        <div class="flex gap-2 flex-1 min-w-[220px]">
            <div class="relative flex-1">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                </svg>
                <input type="text" name="tema" value="{{ request('tema') }}"
                       placeholder="Buscar por tema..."
                       class="pl-9 pr-4 py-2 w-full text-sm border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-300
                              placeholder-gray-400 text-gray-700">
            </div>
            <button type="submit"
                    class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-200
                           bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                Buscar
            </button>
        </div>

        {{-- Filtro sección --}}
        <select name="seccion_id"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700
                       focus:outline-none focus:ring-2 focus:ring-green-100 bg-white min-w-[180px]">
            <option value="">Todas las secciones</option>
            @foreach($secciones as $seccion)
                <option value="{{ $seccion->id }}"
                    {{ request('seccion_id') == $seccion->id ? 'selected' : '' }}>
                    {{ $seccion->nombre }}
                </option>
            @endforeach
        </select>

        {{-- Filtro estado --}}
        <select name="estado"
                class="text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700
                       focus:outline-none focus:ring-2 focus:ring-green-100 bg-white min-w-[160px]">
            <option value="">Todos los estados</option>
            <option value="activo"   {{ request('estado') === 'activo'   ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>

        @if(request()->hasAny(['tema','seccion_id','estado']))
            <a href="{{ route('admin.descargables.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 underline py-2">
                Limpiar
            </a>
        @endif

    </form>
</div>

{{-- Botón Agregar Documento --}}
<div class="mb-4">
    <button onclick="abrirModalAgregar()"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-lg
                   transition-opacity hover:opacity-90"
            style="background-color: #1a3b2e;">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Agregar Documento
    </button>
</div>

{{-- Tabla de documentos --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    @forelse($descargables as $doc)
        <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-100 last:border-0 hover:bg-gray-50/60 transition-colors">

            {{-- Ícono documento --}}
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>

            {{-- Info principal --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $doc->tema }}</p>
                <div class="flex items-center gap-3 mt-0.5">
                    <span class="text-xs text-gray-500">{{ $doc->seccion->nombre ?? '—' }}</span>
                    <span class="text-gray-300 text-xs">•</span>
                    <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($doc->fecha_publicacion)->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            {{-- Descargas --}}
            <div class="text-center flex-shrink-0 w-16">
                <p class="text-base font-semibold text-gray-700">{{ $doc->total_descargas }}</p>
                <p class="text-xs text-gray-400">descargas</p>
            </div>

            {{-- Badge estado --}}
            <div class="flex-shrink-0">
                <span class="px-3 py-1 text-xs font-semibold rounded-full
                    {{ $doc->estado
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-600' }}">
                    {{ $doc->estado ? 'Activo' : 'Inactivo' }}
                </span>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center gap-1 flex-shrink-0">

                {{-- Toggle activo --}}
                <form method="POST" action="{{ route('admin.descargables.toggleActivo', $doc) }}">
                    @csrf @method('PATCH')
                    <button type="submit" title="{{ $doc->estado ? 'Desactivar' : 'Activar' }}"
                            class="p-1.5 rounded-md text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="{{ $doc->estado
                                        ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'
                                        : 'M12 4v16m8-8H4' }}"/>
                        </svg>
                    </button>
                </form>

                {{-- Editar --}}
                <button onclick="abrirModalEditar({{ $doc->id }}, {{ $doc->seccion_id ?? 'null' }}, '{{ addslashes($doc->tema) }}', '{{ \Carbon\Carbon::parse($doc->fecha_publicacion)->format('Y-m-d') }}', '{{ addslashes($doc->comentario ?? '') }}')"
                        title="Editar"
                        class="p-1.5 rounded-md text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>

                {{-- Eliminar --}}
                <form method="POST" action="{{ route('admin.descargables.destroy', $doc) }}"
                      onsubmit="return confirm('¿Eliminar este documento? Esta acción no se puede deshacer.')">
                    @csrf @method('DELETE')
                    <button type="submit" title="Eliminar"
                            class="p-1.5 rounded-md text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </form>

            </div>
        </div>
    @empty
        <div class="py-16 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm">No se encontraron documentos.</p>
        </div>
    @endforelse
</div>

{{-- Paginación --}}
@if($descargables->hasPages())
    <div class="mt-4">
        {{ $descargables->links() }}
    </div>
@endif


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- MODAL: AGREGAR DOCUMENTO                                    --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="modalAgregar"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        {{-- Header modal --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Agregar Documento</h2>
            <button onclick="cerrarModal('modalAgregar')"
                    class="text-gray-400 hover:text-gray-700 transition-colors rounded-lg p-1 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Formulario --}}
        <form id="formAgregar" method="POST" action="{{ route('admin.descargables.store') }}"
              enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            @csrf

            {{-- Sección --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Sección <span class="text-red-500">*</span>
                </label>
                <select name="seccion_descargable_id" required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-400 bg-white">
                    <option value="">Seleccionar sección...</option>
                    @foreach($secciones as $sec)
                        <option value="{{ $sec->id }}">{{ $sec->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Fecha --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Fecha de publicación <span class="text-red-500">*</span>
                </label>
                <input type="date" name="fecha_publicacion" required
                       value="{{ now()->format('Y-m-d') }}"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 text-gray-700
                              focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-400">
            </div>

            {{-- Tema --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tema <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tema" required placeholder="Ingrese el tema del archivo..."
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 text-gray-700
                              focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-400
                              placeholder-gray-400">
            </div>

            {{-- Comentario --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                <textarea name="comentario" rows="3" placeholder="Ingrese un comentario..."
                          class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 text-gray-700
                                 focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-400
                                 placeholder-gray-400 resize-y"></textarea>
            </div>

            {{-- Archivo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Archivo <span class="text-red-500">*</span>
                </label>
                <label for="archivoAgregar"
                       class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-gray-200
                              rounded-xl px-4 py-8 cursor-pointer hover:border-green-300 hover:bg-green-50/30
                              transition-colors group">
                    <svg class="w-8 h-8 text-gray-300 group-hover:text-green-400 transition-colors"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    <p id="labelArchivoAgregar" class="text-sm text-gray-400">Arrastra o haz clic para subir</p>
                    <p class="text-xs text-gray-300">PDF, Word, Excel, ZIP (Max 10MB)</p>
                    <input id="archivoAgregar" type="file" name="archivo" required class="hidden"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                           onchange="mostrarNombreArchivo(this, 'labelArchivoAgregar')">
                </label>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 mt-5">
                <button type="button" onclick="cerrarModal('modalAgregar')"
                        class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-200 rounded-lg
                               hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg transition-opacity hover:opacity-90"
                        style="background-color: #1a3b2e;">
                    Guardar Archivo
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- MODAL: EDITAR DOCUMENTO                                     --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div id="modalEditar"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/30 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        {{-- Header modal --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Editar Documento</h2>
            <button onclick="cerrarModal('modalEditar')"
                    class="text-gray-400 hover:text-gray-700 transition-colors rounded-lg p-1 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Formulario --}}
        <form id="formEditar" method="POST" action="" enctype="multipart/form-data"
              class="px-6 py-5 space-y-4">
            @csrf @method('PUT')

            {{-- Sección --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Sección <span class="text-red-500">*</span>
                </label>
                <select name="seccion_descargable_id" id="editarSeccion" required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-400 bg-white">
                    <option value="">Seleccionar sección...</option>
                    @foreach($secciones as $sec)
                        <option value="{{ $sec->id }}">{{ $sec->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Fecha --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Fecha de publicación <span class="text-red-500">*</span>
                </label>
                <input type="date" name="fecha_publicacion" id="editarFecha" required
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 text-gray-700
                              focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-400">
            </div>

            {{-- Tema --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tema <span class="text-red-500">*</span>
                </label>
                <input type="text" name="tema" id="editarTema" required
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 text-gray-700
                              focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-400">
            </div>

            {{-- Comentario --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                <textarea name="comentario" id="editarComentario" rows="3"
                          placeholder="Ingrese un comentario..."
                          class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 text-gray-700
                                 focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-400
                                 placeholder-gray-400 resize-y"></textarea>
            </div>

            {{-- Reemplazar archivo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reemplazar archivo (opcional)</label>
                <label for="archivoEditar"
                       class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-gray-200
                              rounded-xl px-4 py-8 cursor-pointer hover:border-green-300 hover:bg-green-50/30
                              transition-colors group">
                    <svg class="w-8 h-8 text-gray-300 group-hover:text-green-400 transition-colors"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    <p id="labelArchivoEditar" class="text-sm text-gray-400">Arrastra o haz clic para subir</p>
                    <p class="text-xs text-gray-300">PDF, Word, Excel, ZIP (Max 10MB)</p>
                    <input id="archivoEditar" type="file" name="archivo" class="hidden"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.zip"
                           onchange="mostrarNombreArchivo(this, 'labelArchivoEditar')">
                </label>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 mt-5">
                <button type="button" onclick="cerrarModal('modalEditar')"
                        class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-200 rounded-lg
                               hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white rounded-lg transition-opacity hover:opacity-90"
                        style="background-color: #1a3b2e;">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── Helpers modales ──────────────────────────────────────────────────────

    function abrirModal(id) {
        const el = document.getElementById(id);
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModal(id) {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Cerrar al clicar el fondo
    ['modalAgregar', 'modalEditar'].forEach(id => {
        document.getElementById(id).addEventListener('click', function (e) {
            if (e.target === this) cerrarModal(id);
        });
    });

    // Cerrar con Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            cerrarModal('modalAgregar');
            cerrarModal('modalEditar');
        }
    });

    // ── Abrir modal Agregar ──────────────────────────────────────────────────

    function abrirModalAgregar() {
        document.getElementById('formAgregar').reset();
        document.getElementById('labelArchivoAgregar').textContent = 'Arrastra o haz clic para subir';
        abrirModal('modalAgregar');
    }

    // ── Abrir modal Editar (pre-rellena campos) ──────────────────────────────

    function abrirModalEditar(id, seccionId, tema, fecha, comentario) {
        const baseUrl = "{{ url('admin/tramites-formularios') }}";
        document.getElementById('formEditar').action = `${baseUrl}/${id}`;

        document.getElementById('editarSeccion').value    = seccionId ?? '';
        document.getElementById('editarFecha').value      = fecha;
        document.getElementById('editarTema').value       = tema;
        document.getElementById('editarComentario').value = comentario;
        document.getElementById('labelArchivoEditar').textContent = 'Arrastra o haz clic para subir';
        document.getElementById('archivoEditar').value = '';

        abrirModal('modalEditar');
    }

    // ── Mostrar nombre de archivo seleccionado ───────────────────────────────

    function mostrarNombreArchivo(input, labelId) {
        const label = document.getElementById(labelId);
        label.textContent = input.files.length > 0
            ? input.files[0].name
            : 'Arrastra o haz clic para subir';
    }
</script>
@endpush