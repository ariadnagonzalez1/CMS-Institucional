{{-- resources/views/public/contacto.blade.php --}}
@extends('layouts.public')

@section('title', 'Contactos y Redes')

@section('content')

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">

        <div class="mb-10 fade-up">
            <p class="section-label mb-2">Contacto</p>
            <h1 class="font-display text-5xl text-gray-900">Vías de Contacto y Redes Sociales</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Datos --}}
            <div class="space-y-3 fade-up">
                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Sede Administrativa</p>
                        <p class="text-sm text-gray-800 font-medium">Av. 9 de Julio N 498</p>
                        <p class="text-xs text-gray-500">Lunes a Viernes 8:00 a 12:30hs</p>
                    </div>
                </div>

                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Sede Social</p>
                        <p class="text-sm text-gray-800 font-medium">Av. Gutnisky N 1870</p>
                    </div>
                </div>

                @if($config->email_principal ?? false)
                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Correo Electrónico</p>
                        <a href="mailto:{{ $config->email_principal }}" class="text-sm text-green-800 font-medium hover:underline">{{ $config->email_principal }}</a>
                    </div>
                </div>
                @endif

                @if($config->whatsapp ?? false)
                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">WhatsApp</p>
                        <a href="https://wa.me/{{ preg_replace('/\D/','',$config->whatsapp) }}" target="_blank"
                           class="text-sm text-green-800 font-medium hover:underline">{{ $config->whatsapp }}</a>
                    </div>
                </div>
                @endif

                @if($config->telefono ?? false)
                <div class="flex items-start gap-4 p-5 border border-gray-100 rounded-sm">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center text-white" style="background:var(--verde-oscuro)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-0.5">Tel. Fijo</p>
                        <a href="tel:{{ $config->telefono }}" class="text-sm text-gray-800 font-medium">{{ $config->telefono }}</a>
                    </div>
                </div>
                @endif
            </div>

            {{-- Formulario --}}
            <div class="fade-up" style="transition-delay:100ms">
                <div class="border border-gray-100 rounded-sm p-8">
                    <h2 class="font-display text-xl text-gray-900 mb-2">Buzón para empresas que requieran Ingenieros</h2>
                    <p class="text-sm text-gray-500 mb-6">Dejá tu mensaje aquí para solicitar ingenieros. Te acompañamos a buscar el indicado.</p>

                    <form action="{{ route('public.contacto.enviar') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="nombre" placeholder="Nombre"
                                   value="{{ old('nombre') }}"
                                   class="px-4 py-3 text-sm border border-gray-200 rounded-sm focus:outline-none focus:border-green-400 w-full" required>
                            <input type="email" name="email" placeholder="Correo electrónico"
                                   value="{{ old('email') }}"
                                   class="px-4 py-3 text-sm border border-gray-200 rounded-sm focus:outline-none focus:border-green-400 w-full" required>
                        </div>
                        <input type="text" name="telefono" placeholder="Teléfono"
                               value="{{ old('telefono') }}"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-sm focus:outline-none focus:border-green-400">
                        <input type="text" name="empresa" placeholder="Empresa (opcional)"
                               value="{{ old('empresa') }}"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-sm focus:outline-none focus:border-green-400">
                        <textarea name="mensaje" placeholder="Mensaje..." rows="5"
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-sm focus:outline-none focus:border-green-400 resize-none" required>{{ old('mensaje') }}</textarea>
                        <button type="submit"
                                class="w-full py-3 text-sm font-semibold text-white rounded-sm flex items-center justify-center gap-2 hover:opacity-90 transition-opacity"
                                style="background:var(--negro)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Enviar mensaje
                        </button>

                        @if(session('success'))
                        <p class="text-green-700 text-sm text-center bg-green-50 p-3 rounded">{{ session('success') }}</p>
                        @endif
                        @if($errors->any())
                        <p class="text-red-600 text-sm text-center">{{ $errors->first() }}</p>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- Redes sociales --}}
        @if($config->instagram_url || $config->youtube_url || $config->twitter_url || $config->facebook_url)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-10 fade-up">
            @if($config->instagram_url)
            <a href="{{ $config->instagram_url }}" target="_blank" class="red-social-btn flex items-center justify-center py-6 rounded-sm text-white" style="background:#e1306c">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </a>
            @endif
            @if($config->youtube_url)
            <a href="{{ $config->youtube_url }}" target="_blank" class="red-social-btn flex items-center justify-center py-6 rounded-sm text-white" style="background:#ff0000">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
            </a>
            @endif
            @if($config->twitter_url)
            <a href="{{ $config->twitter_url }}" target="_blank" class="red-social-btn flex items-center justify-center py-6 rounded-sm text-white" style="background:#1da1f2">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
            </a>
            @endif
            @if($config->facebook_url)
            <a href="{{ $config->facebook_url }}" target="_blank" class="red-social-btn flex items-center justify-center py-6 rounded-sm text-white" style="background:#1877f2">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            @endif
        </div>
        @endif

    </div>
</div>

@endsection