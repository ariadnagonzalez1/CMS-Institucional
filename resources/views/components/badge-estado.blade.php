{{-- resources/views/components/badge-estado.blade.php --}}
{{--
    Uso:
      <x-badge-estado :activo="$modulo->estado" />
      <x-badge-estado :activo="$modulo->estado" label-on="activo" label-off="inactivo" />
--}}
@props([
    'activo'   => true,
    'labelOn'  => 'on',
    'labelOff' => 'off',
])

@if($activo)
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                 bg-emerald-100 text-emerald-700">
        {{ $labelOn }}
    </span>
@else
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                 bg-gray-100 text-gray-500">
        {{ $labelOff }}
    </span>
@endif