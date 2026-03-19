{{-- resources/views/modulos/root/_form-seccion.blade.php --}}
@php $s = $seccion; @endphp

<div class="space-y-4">
    {{-- Nombre --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">
            Nombre <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $s->nombre ?? '') }}"
               required maxlength="150"
               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400
                      placeholder-gray-300"
               placeholder="ej: Portada — Noticias generales">
    </div>

    {{-- Modo de texto --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">
            Modo de Texto <span class="text-red-500">*</span>
        </label>
        <select name="modo_texto_id" required
                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400
                       bg-white">
            <option value="">— Seleccioná un modo —</option>
            @foreach($modosTexto as $modo)
                <option value="{{ $modo->id }}"
                    {{ old('modo_texto_id', $s->modo_texto_id ?? '') == $modo->id ? 'selected' : '' }}>
                    {{ $modo->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Colores --}}
    <div class="grid grid-cols-3 gap-3">
        @foreach([
            ['field' => 'color_fondo',  'label' => 'Color Fondo',  'default' => '#ffffff'],
            ['field' => 'color_texto',  'label' => 'Color Texto',  'default' => '#000000'],
            ['field' => 'color_borde',  'label' => 'Color Borde',  'default' => '#cccccc'],
        ] as $color)
        @php $val = old($color['field'], $s->{$color['field']} ?? ''); @endphp
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">{{ $color['label'] }}</label>
            <div class="flex items-center gap-2 color-pair">
                <input type="color"
                       name="{{ $color['field'] }}"
                       value="{{ $val ?: $color['default'] }}"
                       class="color-picker h-9 w-12 rounded border border-gray-200 cursor-pointer p-0.5">
                <input type="text"
                       value="{{ $val }}"
                       maxlength="20"
                       class="color-text flex-1 px-2 py-2 text-xs border border-gray-200 rounded-lg
                              focus:outline-none focus:ring-1 focus:ring-green-300 font-mono"
                       placeholder="#rrggbb">
            </div>
        </div>
        @endforeach
    </div>

    {{-- Orden --}}
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1">Orden</label>
        <input type="number" name="orden" min="0"
               value="{{ old('orden', $s->orden ?? 0) }}"
               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-green-200 focus:border-green-400">
    </div>

    {{-- Visible en sitio --}}
    <div class="flex items-center gap-3">
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="hidden"  name="visible_en_sitio" value="0">
            <input type="checkbox" name="visible_en_sitio" value="1" class="sr-only peer"
                   {{ old('visible_en_sitio', $s->visible_en_sitio ?? true) ? 'checked' : '' }}>
            <div class="w-10 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-green-300
                        rounded-full peer peer-checked:bg-[#196B4A] transition-colors
                        after:content-[''] after:absolute after:top-0.5 after:left-0.5
                        after:bg-white after:rounded-full after:h-4 after:w-4
                        after:transition-transform peer-checked:after:translate-x-5"></div>
        </label>
        <span class="text-sm text-gray-600">Visible en el sitio</span>
    </div>
</div>