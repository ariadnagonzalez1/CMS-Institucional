{{-- resources/views/modulos/multimedia/create.blade.php --}}
<div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800">Agregar Audio/Video</h3>
    <button onclick="cerrarModal('modal-crear')" class="text-gray-400 hover:text-gray-600 transition">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<form id="form-crear" method="POST" action="{{ route('admin.multimedia.store') }}" enctype="multipart/form-data" class="p-6 space-y-5">
    @csrf

    <div class="space-y-4">
        {{-- Sección --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">SECCIÓN <span class="text-red-500">*</span></label>
            <select name="seccion_multimedia_id" id="crear-seccion" required
                    class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 bg-white">
                <option value="">Elija Sección</option>
                @foreach($secciones as $seccion)
                    <option value="{{ $seccion->id }}">{{ $seccion->nombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tipo Multimedia --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">FORMATO <span class="text-red-500">*</span></label>
            <select name="tipo_multimedia_id" id="crear-tipo" required
                    class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 bg-white">
                <option value="">Elija Formato</option>
                @foreach($tiposMultimedia as $tipo)
                    <option value="{{ $tipo->id }}" data-es-embed="{{ $tipo->es_embed ? '1' : '0' }}">
                        {{ $tipo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Fecha --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">FECHA <span class="text-red-500">*</span></label>
            <input type="date" name="fecha_publicacion" value="{{ date('Y-m-d') }}" required
                   class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
        </div>

        {{-- Tema --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">TEMA <span class="text-red-500">*</span></label>
            <input type="text" name="tema" required maxlength="255"
                   class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"
                   placeholder="Título del contenido">
        </div>

        {{-- Campo Embed (YouTube) --}}
<div id="crear-campo-embed" class="hidden">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        URL DE YOUTUBE <span class="text-red-500">*</span>
    </label>
    <input type="text" 
           name="codigo_embed" 
           id="crear-embed"
           class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 text-sm"
           placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
</div>

        {{-- Campo Archivo MP3 --}}
        <div id="crear-campo-archivo" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-1">ARCHIVO MP3 <span class="text-red-500">*</span></label>
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-emerald-300 transition cursor-pointer"
                 onclick="document.getElementById('crear-archivo-input').click()">
                <input type="file" name="archivo" accept=".mp3" class="hidden" id="crear-archivo-input">
                <svg class="h-10 w-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                </svg>
                <p class="text-sm text-gray-600">Haga clic para seleccionar un archivo MP3</p>
                <p class="text-xs text-gray-400 mt-1">Formatos: MP3. Máx 10MB.</p>
            </div>
            <div id="crear-nombre-archivo" class="hidden text-xs text-gray-500 mt-2">
                <span class="font-medium">Archivo seleccionado:</span> <span id="crear-archivo-nombre"></span>
            </div>
        </div>
    </div>

    {{-- Estado --}}
    <div class="border-t border-gray-100 pt-4">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="estado" value="1" checked
                   class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
            <span class="text-sm text-gray-700">Activo</span>
        </label>
    </div>

    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
        <button type="button" onclick="cerrarModal('modal-crear')"
                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition border border-gray-200">
            Cancelar
        </button>
        <button type="submit"
                class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-sm">
            Guardar
        </button>
    </div>
</form>