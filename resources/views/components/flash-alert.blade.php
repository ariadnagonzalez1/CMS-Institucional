{{-- resources/views/components/flash-alert.blade.php --}}
{{--
    Coloca este componente en el layout o al inicio de cada vista.
    Lee automáticamente session('success') y session('error').
--}}

@if(session('success') || session('error'))
    @php
        $type    = session('success') ? 'success' : 'error';
        $message = session('success') ?? session('error');
        $estilos = [
            'success' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200',
                          'text' => 'text-emerald-800', 'icon-color' => 'text-emerald-500'],
            'error'   => ['bg' => 'bg-red-50',     'border' => 'border-red-200',
                          'text' => 'text-red-800',     'icon-color' => 'text-red-500'],
        ];
        $e = $estilos[$type];
    @endphp

    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 4000)"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="mb-4 flex items-start gap-3 p-4 rounded-xl border
                {{ $e['bg'] }} {{ $e['border'] }} {{ $e['text'] }}">

        {{-- ícono --}}
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5 flex-shrink-0 mt-0.5 {{ $e['icon-color'] }}"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            @if($type === 'success')
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            @else
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            @endif
        </svg>

        <p class="text-sm font-medium">{{ $message }}</p>

        <button onclick="this.parentElement.remove()"
                class="ml-auto flex-shrink-0 {{ $e['icon-color'] }} hover:opacity-70">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif