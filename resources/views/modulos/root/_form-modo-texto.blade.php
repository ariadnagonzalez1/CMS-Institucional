{{-- resources/views/modulos/root/_form-modo-texto.blade.php --}}
@php $m = $modo; @endphp

<div class="space-y-4">
    {{-- Nombre --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">
            Nombre <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $m->nombre ?? '') }}"
               required maxlength="100"
               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400
                      placeholder-gray-300"
               placeholder="ej: Nota estándar">
    </div>

    {{-- Descripción --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción</label>
        <input type="text" name="descripcion"
               value="{{ old('descripcion', $m->descripcion ?? '') }}"
               maxlength="255"
               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400
                      placeholder-gray-300"
               placeholder="Breve descripción (opcional)">
    </div>

    {{-- Cantidad de cajas --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Cantidad de cajas</label>
        <input type="number" name="cantidad_cajas" min="1" max="255"
               value="{{ old('cantidad_cajas', $m->cantidad_cajas ?? '') }}"
               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400"
               placeholder="Ej: 3">
    </div>

    {{-- Estado --}}
    <div class="flex items-center gap-3">
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="hidden"  name="estado" value="0">
            <input type="checkbox" name="estado" value="1" class="sr-only peer"
                   {{ old('estado', $m->estado ?? true) ? 'checked' : '' }}>
            <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-green-300
                        rounded-full peer peer-checked:bg-[#196B4A] transition-colors
                        after:content-[''] after:absolute after:top-0.5 after:left-0.5
                        after:bg-white after:rounded-full after:h-4 after:w-4
                        after:transition-transform peer-checked:after:translate-x-5"></div>
        </label>
        <span class="text-sm text-gray-600">Activo</span>
    </div>
</div>