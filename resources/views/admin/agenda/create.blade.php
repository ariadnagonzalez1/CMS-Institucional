{{-- resources/views/admin/agenda/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Agregar Evento')

@section('header-title', 'Agregar Evento')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <form action="{{ route('admin.agenda.store') }}" method="POST">
            @csrf

            <div class="p-6 space-y-6">
                {{-- Datos del evento --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Agendar en <span class="text-red-500">*</span>
                        </label>
                        <select name="seccion_agenda_id" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('seccion_agenda_id') border-red-500 @enderror">
                            <option value="">Seleccionar sección</option>
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->id }}" {{ old('seccion_agenda_id') == $seccion->id ? 'selected' : '' }}>
                                    {{ $seccion->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('seccion_agenda_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha del evento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="fecha_evento" 
                               value="{{ old('fecha_evento', date('Y-m-d')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('fecha_evento') border-red-500 @enderror">
                        @error('fecha_evento')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Hora
                        </label>
                        <input type="time" 
                               name="hora_evento" 
                               value="{{ old('hora_evento') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent">
                        @error('hora_evento')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fijar evento <span class="text-red-500">*</span>
                        </label>
                        <select name="tipo_fijacion" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('tipo_fijacion') border-red-500 @enderror">
                            @foreach($tiposFijacion as $key => $tipo)
                                <option value="{{ $key }}" {{ old('tipo_fijacion', 'ninguno') == $key ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_fijacion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Ventana
                        </label>
                        <select name="tipo_ventana" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent">
                            <option value="">Seleccionar ventana</option>
                            @foreach($tiposVentana as $key => $ventana)
                                <option value="{{ $key }}" {{ old('tipo_ventana') == $key ? 'selected' : '' }}>
                                    {{ $ventana }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tema/Evento/Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="titulo" 
                               value="{{ old('titulo') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('titulo') border-red-500 @enderror"
                               placeholder="Nombre del evento">
                        @error('titulo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Lugar
                        </label>
                        <input type="text" 
                               name="lugar" 
                               value="{{ old('lugar') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent"
                               placeholder="Lugar del evento">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Programa/Detalles/Salutación/Mensaje
                        </label>
                        <textarea name="descripcion" 
                                  rows="6"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent"
                                  placeholder="Escriba los detalles del evento...">{{ old('descripcion') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Estado
                        </label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       name="estado" 
                                       value="1"
                                       class="rounded border-gray-300 text-[#1a3b2e] focus:ring-[#1a3b2e]"
                                       {{ old('estado', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Evento activo</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
                <a href="{{ route('admin.agenda.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection