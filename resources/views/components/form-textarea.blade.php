@props(['rows' => 2])

<textarea
    rows="{{ $rows }}"
    {{ $attributes->merge(['class' => 'w-full text-sm text-gray-800 outline-none transition-all resize-none placeholder-gray-400']) }}
    style="padding: 0.625rem 0.875rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; background-color: #f9fafb;"
    onfocus="this.style.backgroundColor='#ffffff'; this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.15)'"
    onblur="this.style.backgroundColor='#f9fafb'; this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
>{{ $slot }}</textarea>