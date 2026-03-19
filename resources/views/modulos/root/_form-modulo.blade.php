{{-- resources/views/modulos/root/_form-modulo.blade.php --}}
@php $m = $modulo; @endphp

<div class="grid grid-cols-2 gap-4">
    {{-- Nombre --}}
    <div class="col-span-2">
        <label class="block text-xs font-semibold text-gray-600 mb-1">
            Nombre <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $m->nombre ?? '') }}"
               required maxlength="120"
               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400
                      placeholder-gray-300"
               placeholder="ej: Novedades y Noticias">
    </div>

    {{-- Tipo --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo</label>
        <select name="tipo"
                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400
                       bg-white">
            <option value="">— Sin tipo —</option>
            @foreach(['abm', 'sys'] as $t)
                <option value="{{ $t }}"
                    {{ old('tipo', $m->tipo ?? '') === $t ? 'selected' : '' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Orden --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Orden</label>
        <input type="number" name="orden" min="0"
               value="{{ old('orden', $m->orden ?? 0) }}"
               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">
    </div>

    {{-- Path Home --}}
    <div class="col-span-2">
        <label class="block text-xs font-semibold text-gray-600 mb-1">Path Home</label>
        <input type="text" name="path_home"
               value="{{ old('path_home', $m->path_home ?? '') }}"
               maxlength="255"
               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg font-mono
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400
                      placeholder-gray-300"
               placeholder="ej: templates/modulos/noticias">
    </div>

    {{-- Icono --}}
    <div class="col-span-2">
        <label class="block text-xs font-semibold text-gray-600 mb-1">Ícono (clase o clave)</label>
        <input type="text" name="icono"
               value="{{ old('icono', $m->icono ?? '') }}"
               maxlength="100"
               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400
                      placeholder-gray-300"
               placeholder="Opcional">
    </div>

    {{-- Estado --}}
    <div class="col-span-2 flex items-center gap-3">
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
        <span class="text-sm text-gray-600">Módulo activo</span>
    </div>
</div>