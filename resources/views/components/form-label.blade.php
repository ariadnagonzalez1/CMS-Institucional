@props(['for' => null, 'required' => false])

<label
    @if($for) for="{{ $for }}" @endif
    class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide"
>
    {{ $slot }}
    @if($required)
        <span class="text-red-500 ml-0.5">*</span>
    @endif
</label>