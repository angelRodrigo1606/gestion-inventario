<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-system-background min-h-screen flex items-center justify-center">
    <div class="bg-secondary-system-background rounded-2xl w-full max-w-md p-8">
        <h1 class="text-2xl font-bold text-label mb-6 text-center">Crear cuenta</h1>

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            {{-- Nombre --}}
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-secondary-label mb-1">
                    Nombre
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    class="w-full bg-system-background border rounded-lg px-3 py-2 text-sm text-label focus:outline-none focus:ring-2 focus:ring-system-blue
                        {{ $errors->has('name') ? 'border-system-red' : 'border-separator' }}"
                >
                @error('name')
                    <p class="text-system-red text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-secondary-label mb-1">
                    Correo electrónico
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    class="w-full bg-system-background border rounded-lg px-3 py-2 text-sm text-label focus:outline-none focus:ring-2 focus:ring-system-blue
                        {{ $errors->has('email') ? 'border-system-red' : 'border-separator' }}"
                >
                @error('email')
                    <p class="text-system-red text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-secondary-label mb-1">
                    Contraseña
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    class="w-full bg-system-background border rounded-lg px-3 py-2 text-sm text-label focus:outline-none focus:ring-2 focus:ring-system-blue
                        {{ $errors->has('password') ? 'border-system-red' : 'border-separator' }}"
                >
                @error('password')
                    <p class="text-system-red text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirmación de contraseña --}}
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-secondary-label mb-1">
                    Confirmar contraseña
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    class="w-full bg-system-background border rounded-lg px-3 py-2 text-sm text-label focus:outline-none focus:ring-2 focus:ring-system-blue
                        {{ $errors->has('password_confirmation') ? 'border-system-red' : 'border-separator' }}"
                >
                @error('password_confirmation')
                    <p class="text-system-red text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-system-blue hover:bg-system-blue/80 text-label font-semibold py-2 rounded-lg text-sm transition">
                Registrarse
            </button>
        </form>

        <div class="h-px bg-separator mt-6 mb-4"></div>

        <p class="text-center text-sm text-secondary-label">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-link hover:underline font-medium">Inicia sesión</a>
        </p>
    </div>
</body>
</html>
