{{-- resources/views/modulos/root/_form-footer.blade.php --}}
@props(['modalId' => 'modal', 'label' => 'Guardar'])

<div class="flex items-center justify-end gap-3 pt-4 mt-2 border-t border-gray-100">
    <button type="button"
            onclick="cerrarModal('{{ $modalId }}')"
            class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100
                   hover:bg-gray-200 rounded-lg transition-colors">
        Cancelar
    </button>
    <button type="submit"
            class="px-5 py-2 text-sm font-semibold text-white rounded-lg
                   transition-all duration-150 shadow-sm hover:shadow-md
                   hover:brightness-110"
            style="background-color: #196B4A;">
        {{ $label }}
    </button>
</div>