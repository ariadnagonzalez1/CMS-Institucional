{{-- resources/views/admin/admin/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Editar Administrador')

@section('header-title', 'Editar Administrador')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <form action="{{ route('admin.admin.update', $admin) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                {{-- Datos de identificación --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            DNI <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="dni" 
                               value="{{ old('dni', $admin->dni) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('dni') border-red-500 @enderror"
                               placeholder="DNI como usuario">
                        @error('dni')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Clave
                        </label>
                        <input type="password" 
                               name="password" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent"
                               placeholder="Dejar vacío para no cambiar">
                        <p class="text-gray-400 text-xs mt-1">Máximo 8 caracteres. Dejar vacío para mantener la actual.</p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               value="{{ old('nombre', $admin->name) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('nombre') border-red-500 @enderror"
                               placeholder="Nombre">
                        @error('nombre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Apellido
                        </label>
                        <input type="text" 
                               name="apellido" 
                               value="{{ old('apellido', $admin->apellido) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent"
                               placeholder="Apellido">
                    </div>
                </div>

                {{-- Contacto --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $admin->email) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="correo@ejemplo.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Celular <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="celular" 
                               value="{{ old('celular', $admin->celular) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('celular') border-red-500 @enderror"
                               placeholder="+54 0000 000000">
                        @error('celular')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Teléfono Fijo
                        </label>
                        <input type="text" 
                               name="telefono_fijo" 
                               value="{{ old('telefono_fijo', $admin->telefono_fijo) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent"
                               placeholder="Teléfono fijo">
                    </div>
                </div>

                {{-- Configuración --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Privilegios <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2 border border-gray-300 rounded-lg p-3 max-h-40 overflow-y-auto">
                            @foreach($privilegios as $privilegio)
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="privilegios[]" 
                                           value="{{ $privilegio->id }}"
                                           class="rounded border-gray-300 text-[#1a3b2e] focus:ring-[#1a3b2e]"
                                           {{ in_array($privilegio->id, old('privilegios', $privilegiosSeleccionados)) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">{{ $privilegio->nombre }}</span>
                                    <span class="ml-2 text-xs text-gray-400">{{ $privilegio->descripcion }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('privilegios')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Sala de redacción <span class="text-red-500">*</span>
                        </label>
                        <select name="sala_redaccion_id" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('sala_redaccion_id') border-red-500 @enderror">
                            <option value="">Seleccionar sala de redacción</option>
                            @foreach($salasRedaccion as $sala)
                                <option value="{{ $sala->id }}" {{ old('sala_redaccion_id', $admin->sala_redaccion_id) == $sala->id ? 'selected' : '' }}>
                                    {{ $sala->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('sala_redaccion_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Modo de grupo <span class="text-red-500">*</span>
                        </label>
                        <select name="modo_grupo_id" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#1a3b2e] focus:border-transparent @error('modo_grupo_id') border-red-500 @enderror">
                            <option value="">Seleccionar modo de grupo</option>
                            @foreach($modosGrupo as $modo)
                                <option value="{{ $modo->id }}" {{ old('modo_grupo_id', $admin->modo_grupo_id) == $modo->id ? 'selected' : '' }}>
                                    {{ $modo->nombre }} - {{ $modo->descripcion }}
                                </option>
                            @endforeach
                        </select>
                        @error('modo_grupo_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Estado
                        </label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       name="activo" 
                                       value="1"
                                       class="rounded border-gray-300 text-[#1a3b2e] focus:ring-[#1a3b2e]"
                                       {{ old('activo', $admin->activo) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Usuario activo</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end gap-3">
                <a href="{{ route('admin.admin.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-[#1a3b2e] text-white rounded-lg hover:bg-[#2a5a45] transition">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection