<div>
    <p class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Colores</p>
    <div class="grid grid-cols-3 gap-3">
        @foreach([
            ['name' => 'color_fondo', 'label' => 'Fondo', 'default' => '#ffffff'],
            ['name' => 'color_texto', 'label' => 'Texto', 'default' => '#000000'],
            ['name' => 'color_borde', 'label' => 'Borde', 'default' => '#e5e7eb'],
        ] as $color)
            <div class="color-pair">
                <p class="text-xs text-gray-500 mb-1.5">{{ $color['label'] }}</p>
                <div class="flex items-center gap-2 p-2 rounded-xl border border-gray-200 bg-gray-50">
                    <input type="color"
                           name="{{ $color['name'] }}"
                           value="{{ $color['default'] }}"
                           class="color-picker w-8 h-8 rounded-lg border-0 cursor-pointer
                                  bg-transparent flex-shrink-0 p-0.5" />
                    <input type="text"
                           value="{{ $color['default'] }}"
                           maxlength="7"
                           placeholder="{{ $color['default'] }}"
                           class="color-text w-full text-xs font-mono text-gray-700
                                  bg-transparent outline-none border-0 min-w-0" />
                </div>
            </div>
        @endforeach
    </div>
</div>