<div class="max-w-xl w-full mx-auto mt-20 p-8 bg-white shadow-2xl rounded-xl sm:px-10">

    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Iniciar sesión</h2>

    {{-- Mensaje de error --}}
    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" wire:model.defer="email" placeholder="ejemplo@correo.com"
                   class="w-full border border-gray-300 p-3 rounded-lg focus:ring focus:ring-green-200 focus:outline-none shadow-sm">
            @error('email')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
            <input type="password" wire:model.defer="password" placeholder="••••••••"
                   class="w-full border border-gray-300 p-3 rounded-lg focus:ring focus:ring-green-200 focus:outline-none shadow-sm">
            @error('password')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg text-lg font-semibold transition duration-200 shadow-md">
                Ingresar
            </button>
        </div>
    </form>
</div>