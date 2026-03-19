{{-- resources/views/components/btn-edit.blade.php --}}
{{--
    Uso:
      <x-btn-edit onclick="abrirModal('edit', {{ $item->id }})" />
      o con tooltip personalizado:
      <x-btn-edit title="Editar módulo" onclick="..." />
--}}
@props([
    'title' => 'Editar',
    'type'  => 'button',
])

<button {{ $attributes->merge([
    'type'  => $type,
    'title' => $title,
    'class' => 'inline-flex items-center justify-center h-8 w-8 rounded-lg
                text-gray-400 hover:text-white hover:bg-[#1a3b2e]
                transition-all duration-150 focus:outline-none
                focus:ring-2 focus:ring-[#196B4A] focus:ring-offset-1',
]) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                 m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>
</button>