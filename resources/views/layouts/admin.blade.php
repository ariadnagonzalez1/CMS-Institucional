{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Administración') - Ingenieros de Formosa</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --verde-oscuro: #1a3b2e;
            --verde-medio: #2a5a45;
            --verde-claro: #e8f0ec;
            --rojo: #b22222;
            --blanco: #ffffff;
            --negro: #1a1a1a;
        }
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <x-sidebar
            :nombre-usuario="$nombreUsuario"
            :modulos-principales="$modulosPrincipales"
            :modulos-secundarios="$modulosSecundarios"
        />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-64">

            <!-- Header -->
            <header class="bg-white border-b-2 border-gray-200 h-16 flex items-center justify-between px-6 sticky top-0 z-10">
                <h1 class="text-lg font-semibold text-gray-800">@yield('header-title', 'Dashboard')</h1>

                <div class="flex items-center space-x-3">
                    <!-- Barra de búsqueda -->
                    <div class="relative hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z" />
                        </svg>
                        <input type="text"
                               placeholder="Buscar..."
                               class="pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg
                                      focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-300
                                      w-56 placeholder-gray-400 text-gray-700">
                    </div>

                    <!-- Avatar y nombre -->
                    <div class="flex items-center space-x-3 pl-2 border-l border-gray-100">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $nombreUsuario }}</p>
                            <p class="text-xs text-gray-400">Administrador</p>
                        </div>
                        <div class="h-9 w-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                             style="background-color: #1a3b2e;">
                            {{ substr($nombreUsuario, 0, 2) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>