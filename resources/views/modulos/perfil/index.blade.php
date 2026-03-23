{{-- resources/views/modulos/perfil/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Mi Perfil')
@section('header-title', 'Mi Perfil')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- ══════════════════════════════════════════════════════
         TARJETA: Avatar + datos de solo lectura
    ══════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        <div class="h-20 w-full" style="background: linear-gradient(135deg, #196B4A 0%, #1a3b2e 100%);"></div>

        <div class="px-6 pb-6">
            <div class="flex items-end justify-between -mt-10 mb-4">

                {{-- Avatar con botón de cambio --}}
                <div class="relative">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}"
                             alt="{{ $user->name }}"
                             class="h-20 w-20 rounded-2xl object-cover border-4 border-white shadow-md" />
                    @else
                        <div class="h-20 w-20 rounded-2xl border-4 border-white shadow-md
                                    flex items-center justify-center text-xl font-bold text-white"
                             style="background-color: #196B4A;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->apellido ?? '', 0, 1)) }}
                        </div>
                    @endif

                    <form id="form-avatar"
                          action="{{ route('profile.avatar') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="file" id="avatar-input" name="avatar"
                               accept="image/*" class="hidden"
                               onchange="this.form.submit()" />
                    </form>

                    <label for="avatar-input"
                           title="Cambiar foto"
                           class="absolute -bottom-2 -right-2 h-7 w-7 rounded-full flex items-center
                                  justify-center cursor-pointer border-2 border-white shadow-sm
                                  text-white transition-all hover:brightness-110"
                           style="background-color: #196B4A;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86
                                     a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2
                                     2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                </div>

                {{-- Fechas --}}
                <div class="mb-1 text-right">
                    <p class="text-xs text-gray-400">
                        @if($user->created_at)
    Miembro desde {{ $user->created_at->format('d/m/Y') }}
@endif
                    </p>
                    @if($user->ultimo_login)
                        <p class="text-xs text-gray-400">
                            Último acceso: {{ \Carbon\Carbon::parse($user->ultimo_login)->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <h2 class="text-lg font-semibold text-gray-800">
                {{ $user->name }} {{ $user->apellido }}
            </h2>
           <p class="text-sm text-gray-500 mb-3">&#64;{{ $user->username }}</p>

            {{-- Badges sala + modo grupo --}}
            <div class="flex flex-wrap gap-2">
                @if($user->salaRedaccion)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium"
                          style="background-color: #e8f5ef; color: #196B4A;">
                        {{ $user->salaRedaccion->nombre }}
                    </span>
                @endif
                @if($user->modoGrupo)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                 text-xs font-medium bg-gray-100 text-gray-600">
                        {{ $user->modoGrupo->nombre }}
                    </span>
                @endif
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════
         SECCIÓN: Datos personales
         Mismo patrón que los modales: encabezado con ícono + form
    ══════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">

        {{-- Encabezado — mismo estilo que modal-crud --}}
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-white"
                  style="background-color: #196B4A;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </span>
            <h3 class="text-base font-semibold text-gray-800">Datos Personales</h3>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-form-label for="name" required>Nombre</x-form-label>
                    <x-form-input id="name" name="name"
                                  value="{{ old('name', $user->name) }}" required />
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <x-form-label for="apellido">Apellido</x-form-label>
                    <x-form-input id="apellido" name="apellido"
                                  value="{{ old('apellido', $user->apellido) }}" />
                </div>
            </div>

            <div>
                <x-form-label for="username">Usuario</x-form-label>
                <x-form-input id="username" name="username"
                              value="{{ old('username', $user->username) }}" maxlength="50" />
                @error('username')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-form-label for="email" required>Email</x-form-label>
                <x-form-input id="email" name="email" type="email"
                              value="{{ old('email', $user->email) }}" required />
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Footer — mismo estilo que modal-crud --}}
            <div class="flex justify-end pt-2 border-t border-gray-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm
                               font-semibold text-white transition-all hover:brightness-110 shadow-sm"
                        style="background-color: #196B4A;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 3v4H7V3M12 12v6m-3-3h6"/>
                    </svg>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>


    {{-- ══════════════════════════════════════════════════════
         SECCIÓN: Cambiar contraseña
    ══════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">

        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl text-white"
                  style="background-color: #1a3b2e;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2
                             0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </span>
            <h3 class="text-base font-semibold text-gray-800">Cambiar Contraseña</h3>
        </div>

        <form action="{{ route('profile.password') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <x-form-label for="current_password" required>Contraseña Actual</x-form-label>
                <x-form-input id="current_password" name="current_password"
                              type="password" placeholder="••••••••" />
                @error('current_password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-form-label for="password" required>Nueva Contraseña</x-form-label>
                    <x-form-input id="password" name="password"
                                  type="password" placeholder="••••••••" />
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <x-form-label for="password_confirmation" required>Confirmar</x-form-label>
                    <x-form-input id="password_confirmation" name="password_confirmation"
                                  type="password" placeholder="••••••••" />
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t border-gray-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm
                               font-semibold text-white transition-all hover:brightness-110 shadow-sm"
                        style="background-color: #1a3b2e;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2
                                 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Actualizar Contraseña
                </button>
            </div>
        </form>
    </div>

</div>

@endsection