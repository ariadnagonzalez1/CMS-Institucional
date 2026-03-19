{{-- resources/views/components/modal.blade.php --}}
{{--
    Uso:
      <x-modal id="modal-crear" title="Agregar módulo">
          ... contenido del form ...
      </x-modal>

    Abrir con JS:  document.getElementById('modal-crear').classList.remove('hidden')
    o con el helper:  abrirModal('modal-crear')
--}}
@props([
    'id'    => 'modal',
    'title' => 'Modal',
    'size'  => 'md',   // sm | md | lg | xl
])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
    $maxW = $sizes[$size] ?? $sizes['md'];
@endphp

<div id="{{ $id }}"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"
         onclick="cerrarModal('{{ $id }}')"></div>

    {{-- Panel --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full {{ $maxW }}
                flex flex-col max-h-[90vh] animate-modal">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4
                    border-b border-gray-100">
            <h2 id="{{ $id }}-title"
                class="text-base font-semibold text-gray-800">
                {{ $title }}
            </h2>
            <button type="button"
                    onclick="cerrarModal('{{ $id }}')"
                    class="h-8 w-8 flex items-center justify-center rounded-lg
                           text-gray-400 hover:text-gray-600 hover:bg-gray-100
                           transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Body (scrollable) --}}
        <div class="overflow-y-auto flex-1 px-6 py-5">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(.97) translateY(6px); }
        to   { opacity: 1; transform: scale(1)  translateY(0); }
    }
    .animate-modal { animation: modalIn .18s ease both; }
</style>