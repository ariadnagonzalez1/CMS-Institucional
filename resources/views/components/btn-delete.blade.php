{{-- resources/views/components/btn-delete.blade.php --}}
{{--
    Uso:
      <x-btn-delete
          :action="route('root.modulos.destroy', $modulo->id)"
          :confirm="'¿Eliminar el módulo ' . $modulo->nombre . '?'"
      />
--}}
@props([
    'action'  => '#',
    'confirm' => '¿Estás seguro de que querés eliminar este registro?',
    'title'   => 'Eliminar',
])

<form action="{{ $action }}" method="POST"
      onsubmit="return confirm('{{ addslashes($confirm) }}')"
      class="inline-block">
    @csrf
    @method('DELETE')
    <button
        type="submit"
        title="{{ $title }}"
        class="inline-flex items-center justify-center h-8 w-8 rounded-lg
               text-gray-400 hover:text-white hover:bg-red-600
               transition-all duration-150 focus:outline-none
               focus:ring-2 focus:ring-red-400 focus:ring-offset-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</form>