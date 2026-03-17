<!-- resources/views/components/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PRACTICA LIVEWIRE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow p-4 space-y-4">
        <h1 class="text-xl font-bold">Mi Panel</h1>
        <nav class="flex flex-col space-y-2">
            <a href="{{ route('clientes') }}" class="text-blue-600 hover:underline">Clientes</a>
            <a href="{{ route('productos') }}" class="text-blue-600 hover:underline">Productos</a>
   
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>