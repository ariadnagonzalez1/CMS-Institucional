{{-- resources/views/components/badge-tipo.blade.php --}}
{{--
    Uso:
      <x-badge-tipo :tipo="$modulo->tipo" />
--}}
@props(['tipo' => null])

@php
    $estilos = [
        'abm' => 'bg-teal-100 text-teal-700',
        'sys' => 'bg-purple-100 text-purple-700',
    ];
    $clase = $estilos[strtolower($tipo ?? '')] ?? 'bg-gray-100 text-gray-500';
@endphp

@if($tipo)
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $clase }}">
        {{ strtolower($tipo) }}
    </span>
@else
    <span class="text-gray-400 text-xs">—</span>
@endif