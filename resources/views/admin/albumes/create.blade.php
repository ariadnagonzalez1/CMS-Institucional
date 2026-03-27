{{-- resources/views/admin/albumes/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Crear Álbum')

@section('header-title', 'Crear Nuevo Álbum')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <form action="{{ route('admin.albumes.store') }}" method="POST">
            @csrf

            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del Álbum <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nombre" 
                           value="{{ old('nombre') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('nombre') border-red-500 @enderror"
                           placeholder="Ej: Expo Ingeniería 2025">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Descripción
                    </label>
                    <textarea name="descripcion" 
                              rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent"
                              placeholder="Describa el contenido del álbum...">{{ old('descripcion') }}</textarea>
                </div>

                <div class="flex gap-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="visible" value="1" class="rounded border-gray-300 text-[#1a3b2e]" {{ old('visible', true) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Visible en el sitio</span>
                    </label>
                    
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="estado" value="1" class="rounded border-gray-300 text-[#1a3b2e]" {{ old('estado', true) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Activo</span>
                    </label>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
                <a href="{{ route('admin.albumes.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">
                    Crear Álbum
                </button>
            </div>
        </form>
    </div>
</div>
@endsection