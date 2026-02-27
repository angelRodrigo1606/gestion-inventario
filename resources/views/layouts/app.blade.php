<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestión de Inventario')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-system-background min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-secondary-system-background flex flex-col min-h-screen">
        <div class="px-6 py-5">
            <span class="text-lg font-bold text-label">Inventario</span>
        </div>

        <div class="h-px bg-separator mx-4"></div>

        @php
            $grupos = [
                'General' => [
                    ['label' => 'Dashboard',    'route' => 'dashboard',          'match' => 'dashboard'],
                    ['label' => 'Estadísticas', 'route' => 'estadisticas.index', 'match' => 'estadisticas*'],
                ],
                'Inventario' => [
                    ['label' => 'Productos', 'route' => 'productos.index', 'match' => 'productos*'],
                    ['label' => 'Ingresos',  'route' => 'ingresos.index',  'match' => 'ingresos*'],
                    ['label' => 'Salidas',   'route' => 'salidas.index',   'match' => 'salidas*'],
                ],
                'Planificación' => [
                    ['label' => 'Agenda', 'route' => 'agenda.index', 'match' => 'agenda*'],
                ],
            ];
        @endphp

        <nav class="flex-1 px-4 py-6 space-y-6">
            @foreach ($grupos as $titulo => $links)
                <div>
                    <p class="text-xs font-semibold text-system-gray uppercase tracking-wider px-3 mb-1">
                        {{ $titulo }}
                    </p>
                    @foreach ($links as $link)
                        <a href="{{ route($link['route']) }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                               {{ request()->routeIs($link['match'])
                                   ? 'bg-system-blue/20 text-system-blue'
                                   : 'text-secondary-label hover:bg-white/5 hover:text-label' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>

        <div class="h-px bg-separator mx-4"></div>

        <div class="px-4 py-4">
            <p class="text-xs font-medium text-label px-3 mb-0.5">{{ Auth::user()->name }}</p>
            <p class="text-xs text-secondary-label px-3 mb-3">{{ Auth::user()->email }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-system-red hover:bg-system-red/10 transition">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenido principal --}}
    <div class="flex-1 flex flex-col">
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>
