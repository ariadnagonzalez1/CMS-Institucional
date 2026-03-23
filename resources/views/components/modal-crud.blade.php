@props([
    'id',
    'title',
    'action'      => '',
    'method'      => 'POST',
    'size'        => 'md',
    'icon'        => 'plus',
    'submitLabel' => 'Guardar',
    'multipart'   => false,
])

@php
    $sizes     = ['sm'=>'max-w-sm','md'=>'max-w-md','lg'=>'max-w-lg','xl'=>'max-w-xl'];
    $maxW      = $sizes[$size] ?? 'max-w-md';
    $isEdit    = $icon === 'edit';
    $isSpoofed = in_array(strtoupper($method), ['PUT','PATCH','DELETE']);
    $headerBg  = $isEdit ? '#1a3b2e' : '#196B4A';
    $btnBg     = $isEdit ? '#1a3b2e' : '#196B4A';
@endphp

<div id="{{ $id }}"
     role="dialog"
     aria-modal="true"
     aria-labelledby="{{ $id }}-title"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px);"
     onclick="if(event.target===this) cerrarModal('{{ $id }}')">

    <div class="bg-white rounded-2xl shadow-2xl w-full {{ $maxW }} ring-1 ring-gray-200">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-white"
                      style="background-color: {{ $headerBg }}">
                    @if ($isEdit)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                     m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    @endif
                </span>
                <h2 id="{{ $id }}-title" class="text-base font-semibold text-gray-800">
                    {{ $title }}
                </h2>
            </div>
            <button type="button"
                    onclick="cerrarModal('{{ $id }}')"
                    class="text-gray-400 hover:text-gray-600 transition-colors rounded-lg p-1 hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Formulario --}}
        <form id="{{ $id }}-form"
              action="{{ $action }}"
              method="POST"
              @if($multipart) enctype="multipart/form-data" @endif
              class="px-6 py-5 space-y-4"
              novalidate>
            @csrf
            @if ($isSpoofed)
                @method($method)
            @endif

            {{ $slot }}

            {{-- Footer --}}
            <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button"
                        onclick="cerrarModal('{{ $id }}')"
                        class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600
                               hover:bg-gray-100 transition-colors border border-gray-200">
                    Cancelar
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm
                               font-semibold text-white transition-all hover:brightness-110 shadow-sm"
                        style="background-color: {{ $btnBg }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 3v4H7V3M12 12v6m-3-3h6"/>
                    </svg>
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    </div>
</div>