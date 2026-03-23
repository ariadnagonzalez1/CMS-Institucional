@props(['name', 'checked' => false, 'label' => ''])

<label class="inline-flex items-center gap-2.5 mt-1.5 cursor-pointer select-none">
    <input type="hidden" name="{{ $name }}" value="0">
    <input
        type="checkbox"
        name="{{ $name }}"
        value="1"
        @if($checked) checked @endif
        class="w-9 h-5 appearance-none bg-gray-200 rounded-full relative cursor-pointer
               transition-colors duration-200 checked:bg-emerald-600
               after:content-[''] after:absolute after:top-0.5 after:left-0.5
               after:w-4 after:h-4 after:bg-white after:rounded-full after:shadow
               after:transition-transform after:duration-200
               checked:after:translate-x-4"
    />
    @if($label)
        <span class="text-sm text-gray-600">{{ $label }}</span>
    @endif
</label>