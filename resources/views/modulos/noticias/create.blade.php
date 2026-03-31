{{-- resources/views/modulos/noticias/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Crear Noticia')
@section('header-title', 'Crear Noticia')

@section('content')
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
    <div class="border-b border-gray-100 px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-800">Agregar Título</h3>
        <p class="text-sm text-gray-500 mt-1">Completa los datos para crear una nueva noticia</p>
    </div>
    
    <form method="POST" action="{{ route('admin.noticias.store') }}" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <div class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                {{-- Modo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">MODO <span class="text-red-500">*</span></label>
                    <select name="modo_texto_id" id="modo_texto_id" required
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 bg-white">
                        <option value="">Elija Modo</option>
                        @foreach($modosTexto as $modo)
                            <option value="{{ $modo->id }}" 
                                    data-nombre="{{ $modo->nombre }}"
                                    data-cantidad-cajas="{{ $modo->cantidad_cajas }}"
                                    {{ old('modo_texto_id') == $modo->id ? 'selected' : '' }}>
                                {{ $modo->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('modo_texto_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Sección (depende del modo) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SECCIÓN <span class="text-red-500">*</span></label>
                    <select name="seccion_noticia_id" id="seccion_noticia_id" required
                            class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 bg-white">
                        <option value="">Elija Sección</option>
                    </select>
                    @error('seccion_noticia_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Fecha (solo visible para Diario y Suplementos) --}}
                <div id="campo_fecha">
                    <label class="block text-sm font-medium text-gray-700 mb-1">FECHA <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha_publicacion" 
                           value="{{ old('fecha_publicacion', date('Y-m-d')) }}"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400">
                    @error('fecha_publicacion')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Volanta (solo visible para Diario y Suplementos) --}}
                <div id="campo_volanta" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">VOLANTA</label>
                    <input type="text" name="volanta" maxlength="255" 
                           value="{{ old('volanta') }}"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"
                           placeholder="Texto opcional sobre el título">
                    @error('volanta')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Título (siempre visible) --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">TÍTULO <span class="text-red-500">*</span></label>
                    <input type="text" name="titulo" required maxlength="255"
                           value="{{ old('titulo') }}"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"
                           placeholder="Título principal de la noticia">
                    @error('titulo')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Bajada (solo visible para Diario y Suplementos) --}}
                <div id="campo_bajada" class="col-span-2 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">BAJADA</label>
                    <textarea name="bajada" rows="2" maxlength="500"
                              class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 resize-none"
                              placeholder="Texto introductorio (opcional)">{{ old('bajada') }}</textarea>
                    @error('bajada')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Cuerpo (siempre visible) --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">TEXTO COMPLETO <span class="text-red-500">*</span></label>
                    <textarea name="cuerpo" id="cuerpo" rows="12" required
                              class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 font-mono text-sm"
                              placeholder="Contenido completo de la noticia...">{{ old('cuerpo') }}</textarea>
                    @error('cuerpo')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            {{-- Opciones adicionales (niveles de importancia) --}}
            <div class="border-t border-gray-100 pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-3">NIVEL DE IMPORTANCIA</label>
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="es_destacado_portada" value="1" id="check_destacado"
                               {{ old('es_destacado_portada') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold">D</span>
                            Destacado (normal)
                        </span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="es_superdestacado_portada" value="1" id="check_superdestacado"
                               {{ old('es_superdestacado_portada') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">
                            <span class="inline-flex items-center justify-center w-6 h-6 bg-emerald-200 text-emerald-800 rounded-lg text-xs font-bold">SD</span>
                            Superdestacado
                        </span>
                    </label>
                </div>
                <p class="text-xs text-gray-400 mt-3">
                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-500 rounded text-xs font-bold mr-1">☑</span> Visible en el sitio
                    <span class="ml-3 inline-flex items-center justify-center w-5 h-5 bg-emerald-100 text-emerald-700 rounded text-xs font-bold mr-1">U</span> Activa/Urgente
                </p>
            </div>
            
            <div class="border-t border-gray-100 pt-4">
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="visible" value="1" {{ old('visible', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Visible en el sitio (☑)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="activa" value="1" {{ old('activa', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Activa / Urgente (U)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="permite_comentarios" value="1" {{ old('permite_comentarios', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">Permitir comentarios</span>
                    </label>
                </div>
            </div>
            
            {{-- Imágenes --}}
            <div class="border-t border-gray-100 pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Imágenes</label>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-emerald-300 transition">
                    <input type="file" name="imagenes[]" multiple accept="image/*" class="hidden" id="imagenes-input">
                    <button type="button" onclick="document.getElementById('imagenes-input').click()" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm text-emerald-600 hover:text-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Seleccionar imágenes
                    </button>
                    <p class="text-xs text-gray-400 mt-1">Formatos: JPG, PNG, GIF. Máx 2MB por imagen.</p>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
            <a href="{{ route('admin.noticias.index') }}"
               class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-100 transition border border-gray-200">
                Cancelar
            </a>
            <button type="submit"
                    class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-sm">
                Guardar
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Datos de secciones por modo desde PHP
    const seccionesPorModo = @json($seccionesPorModo);
    
    // Configuración de campos por modo
    const configModos = {
        'Diario': {
            campos: ['volanta', 'bajada'],
            requiereFecha: true,
            secciones: []
        },
        'Institucional': {
            campos: [],
            requiereFecha: false,
            secciones: []
        },
        'Suplementos': {
            campos: ['volanta', 'bajada'],
            requiereFecha: true,
            secciones: []
        }
    };
    
    function actualizarFormulario() {
        const selectModo = document.getElementById('modo_texto_id');
        const selectedOption = selectModo.options[selectModo.selectedIndex];
        const modoNombre = selectedOption ? selectedOption.getAttribute('data-nombre') : '';
        const modoId = selectModo.value;
        
        // 1. Actualizar secciones
        const selectSeccion = document.getElementById('seccion_noticia_id');
        selectSeccion.innerHTML = '<option value="">Elija Sección</option>';
        
        if (modoId && seccionesPorModo[modoId]) {
            seccionesPorModo[modoId].forEach(seccion => {
                const option = document.createElement('option');
                option.value = seccion.id;
                option.textContent = seccion.nombre;
                if (seccion.color_fondo) {
                    option.style.backgroundColor = seccion.color_fondo;
                }
                if (seccion.color_texto) {
                    option.style.color = seccion.color_texto;
                }
                selectSeccion.appendChild(option);
            });
        }
        
        // 2. Mostrar/ocultar campos según el modo
        const config = configModos[modoNombre] || { campos: [], requiereFecha: true };
        
        // Campo volanta
        const campoVolanta = document.getElementById('campo_volanta');
        if (config.campos.includes('volanta')) {
            campoVolanta.classList.remove('hidden');
            campoVolanta.querySelector('input').required = true;
        } else {
            campoVolanta.classList.add('hidden');
            campoVolanta.querySelector('input').required = false;
        }
        
        // Campo bajada
        const campoBajada = document.getElementById('campo_bajada');
        if (config.campos.includes('bajada')) {
            campoBajada.classList.remove('hidden');
            campoBajada.querySelector('textarea').required = false;
        } else {
            campoBajada.classList.add('hidden');
            campoBajada.querySelector('textarea').required = false;
        }
        
        // Campo fecha
        const campoFecha = document.getElementById('campo_fecha');
        if (config.requiereFecha && modoNombre !== 'Institucional') {
            campoFecha.classList.remove('hidden');
            campoFecha.querySelector('input').required = true;
        } else {
            campoFecha.classList.add('hidden');
            campoFecha.querySelector('input').required = false;
        }
    }
    
    // Evento cuando cambia el modo
    const selectModo = document.getElementById('modo_texto_id');
    if (selectModo) {
        selectModo.addEventListener('change', actualizarFormulario);
        
        // Ejecutar al cargar para establecer el estado inicial
        if (selectModo.value) {
            actualizarFormulario();
        }
    }
    
    // Validar que no se puedan marcar los dos checkboxes de destacado
    const checkDestacado = document.getElementById('check_destacado');
    const checkSuperDestacado = document.getElementById('check_superdestacado');
    
    if (checkDestacado && checkSuperDestacado) {
        checkDestacado.addEventListener('change', function() {
            if (this.checked && checkSuperDestacado.checked) {
                checkSuperDestacado.checked = false;
            }
        });
        
        checkSuperDestacado.addEventListener('change', function() {
            if (this.checked && checkDestacado.checked) {
                checkDestacado.checked = false;
            }
        });
    }
</script>
@endpush
@endsection