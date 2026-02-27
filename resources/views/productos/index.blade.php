@extends('layouts.app')

@section('title', 'Productos')

@section('content')
    {{-- Cabecera --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-label">Productos</h1>
        <a href="#modal-crear"
           class="bg-system-blue hover:bg-system-blue/80 text-label text-sm font-medium px-4 py-2 rounded-lg transition">
            + Nuevo producto
        </a>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-system-green/15 text-system-green text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Buscador --}}
    <form method="GET" action="{{ route('productos.index') }}" class="mb-4">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Buscar por nombre o código..."
            class="w-full max-w-sm bg-secondary-system-background border border-separator text-label text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-system-blue"
        >
    </form>

    {{-- Tabla --}}
    <div class="bg-secondary-system-background rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-separator text-secondary-label text-left">
                    <th class="px-4 py-3 font-medium">Código</th>
                    <th class="px-4 py-3 font-medium">Nombre</th>
                    <th class="px-4 py-3 font-medium">P. Compra</th>
                    <th class="px-4 py-3 font-medium">P. Venta</th>
                    <th class="px-4 py-3 font-medium">Stock</th>
                    <th class="px-4 py-3 font-medium">Unidad</th>
                    <th class="px-4 py-3 font-medium">Estado</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                    <tr class="border-b border-separator last:border-0 hover:bg-white/3 transition">
                        <td class="px-4 py-3 text-secondary-label">{{ $producto->codigo ?? '—' }}</td>
                        <td class="px-4 py-3 text-label font-medium">{{ $producto->nombre }}</td>
                        <td class="px-4 py-3 text-secondary-label">S/ {{ number_format($producto->precio_compra, 2) }}</td>
                        <td class="px-4 py-3 text-secondary-label">S/ {{ number_format($producto->precio_venta, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $producto->stock <= $producto->stock_minimo ? 'text-system-red' : 'text-system-green' }} font-medium">
                                {{ $producto->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-secondary-label">{{ $producto->unidad }}</td>
                        <td class="px-4 py-3">
                            @if ($producto->activo)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-system-green/15 text-system-green">
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-system-gray/15 text-system-gray">
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3 justify-end">
                                <a href="#modal-editar-{{ $producto->id }}"
                                   class="text-system-blue hover:underline text-xs font-medium">
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('productos.destroy', $producto) }}"
                                      onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-system-red hover:underline text-xs font-medium">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-secondary-label">
                            No hay productos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if ($productos->hasPages())
        <div class="mt-4 text-secondary-label">
            {{ $productos->links() }}
        </div>
    @endif

    {{-- Modal: Nuevo producto --}}
    <div id="modal-crear"
         class="fixed inset-0 hidden items-center justify-center z-50
                {{ $errors->any() && !old('_editing_id') ? 'open' : '' }}">

        {{-- Backdrop --}}
        <a href="#" class="fixed inset-0 bg-black/60"></a>

        {{-- Contenido --}}
        <div class="relative bg-secondary-system-background rounded-2xl p-8 max-w-2xl w-full mx-4 overflow-y-auto max-h-[90vh]">

            {{-- Cabecera --}}
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-label">Nuevo producto</h2>
                <a href="#" class="text-secondary-label hover:text-label text-2xl leading-none transition">&times;</a>
            </div>

            <div class="h-px bg-separator mb-6"></div>

            {{-- Formulario --}}
            <form method="POST" action="{{ route('productos.store') }}" novalidate>
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    {{-- Código --}}
                    <div>
                        @include('productos._field', [
                            'name'  => 'codigo',
                            'label' => 'Código (opcional)',
                            'type'  => 'text',
                            'value' => old('codigo'),
                        ])
                    </div>

                    {{-- Unidad --}}
                    <div>
                        <label for="unidad" class="block text-sm font-medium text-secondary-label mb-1">Unidad</label>
                        <select id="unidad" name="unidad"
                            class="w-full bg-system-background border rounded-lg px-3 py-2 text-sm text-label focus:outline-none focus:ring-2 focus:ring-system-blue
                                {{ $errors->has('unidad') ? 'border-system-red' : 'border-separator' }}">
                            @foreach (['unidad', 'kg', 'g', 'litro', 'ml', 'caja', 'paquete', 'par'] as $u)
                                <option value="{{ $u }}" {{ old('unidad', 'unidad') === $u ? 'selected' : '' }}>
                                    {{ ucfirst($u) }}
                                </option>
                            @endforeach
                        </select>
                        @error('unidad') <p class="text-system-red text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nombre --}}
                    <div class="col-span-2">
                        @include('productos._field', [
                            'name'  => 'nombre',
                            'label' => 'Nombre',
                            'type'  => 'text',
                            'value' => old('nombre'),
                        ])
                    </div>

                    {{-- Descripción --}}
                    <div class="col-span-2">
                        <label for="descripcion" class="block text-sm font-medium text-secondary-label mb-1">
                            Descripción (opcional)
                        </label>
                        <textarea id="descripcion" name="descripcion" rows="3"
                            class="w-full bg-system-background border rounded-lg px-3 py-2 text-sm text-label focus:outline-none focus:ring-2 focus:ring-system-blue
                                {{ $errors->has('descripcion') ? 'border-system-red' : 'border-separator' }}">{{ old('descripcion') }}</textarea>
                        @error('descripcion') <p class="text-system-red text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Precio compra --}}
                    <div>
                        @include('productos._field', [
                            'name'  => 'precio_compra',
                            'label' => 'Precio de compra',
                            'type'  => 'number',
                            'value' => old('precio_compra', '0.00'),
                            'attrs' => 'min="0" step="0.01"',
                        ])
                    </div>

                    {{-- Precio venta --}}
                    <div>
                        @include('productos._field', [
                            'name'  => 'precio_venta',
                            'label' => 'Precio de venta',
                            'type'  => 'number',
                            'value' => old('precio_venta', '0.00'),
                            'attrs' => 'min="0" step="0.01"',
                        ])
                    </div>

                    {{-- Stock inicial --}}
                    <div>
                        @include('productos._field', [
                            'name'  => 'stock',
                            'label' => 'Stock inicial',
                            'type'  => 'number',
                            'value' => old('stock', '0'),
                            'attrs' => 'min="0" step="1"',
                        ])
                    </div>

                    {{-- Stock mínimo --}}
                    <div>
                        @include('productos._field', [
                            'name'  => 'stock_minimo',
                            'label' => 'Stock mínimo',
                            'type'  => 'number',
                            'value' => old('stock_minimo', '0'),
                            'attrs' => 'min="0" step="1"',
                        ])
                    </div>

                    {{-- Activo --}}
                    <div class="col-span-2 flex items-center gap-2 pt-1">
                        <input type="checkbox" id="activo" name="activo" value="1"
                            class="rounded border-separator bg-system-background accent-system-blue"
                            {{ old('activo', '1') ? 'checked' : '' }}>
                        <label for="activo" class="text-sm text-secondary-label">Producto activo</label>
                    </div>
                </div>

                <div class="h-px bg-separator my-6"></div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-system-blue hover:bg-system-blue/80 text-label text-sm font-medium px-5 py-2 rounded-lg transition">
                        Guardar producto
                    </button>
                    <a href="#"
                       class="text-secondary-label hover:text-label text-sm font-medium px-5 py-2 rounded-lg hover:bg-white/5 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Modales: Editar producto (uno por producto) --}}
    @foreach ($productos as $producto)
        <div id="modal-editar-{{ $producto->id }}"
             class="fixed inset-0 hidden items-center justify-center z-50
                    {{ old('_editing_id') == $producto->id && $errors->any() ? 'open' : '' }}">

            {{-- Backdrop --}}
            <a href="#" class="fixed inset-0 bg-black/60"></a>

            {{-- Contenido --}}
            <div class="relative bg-secondary-system-background rounded-2xl p-8 max-w-2xl w-full mx-4 overflow-y-auto max-h-[90vh]">

                {{-- Cabecera --}}
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-label">Editar producto</h2>
                    <a href="#" class="text-secondary-label hover:text-label text-2xl leading-none transition">&times;</a>
                </div>

                <div class="h-px bg-separator mb-6"></div>

                {{-- Formulario --}}
                <form method="POST" action="{{ route('productos.update', $producto) }}" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_editing_id" value="{{ $producto->id }}">

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Código --}}
                        <div>
                            @include('productos._field', [
                                'name'  => 'codigo',
                                'label' => 'Código (opcional)',
                                'type'  => 'text',
                                'value' => old('_editing_id') == $producto->id ? old('codigo') : $producto->codigo,
                            ])
                        </div>

                        {{-- Unidad --}}
                        <div>
                            <label for="unidad-{{ $producto->id }}" class="block text-sm font-medium text-secondary-label mb-1">Unidad</label>
                            <select id="unidad-{{ $producto->id }}" name="unidad"
                                class="w-full bg-system-background border rounded-lg px-3 py-2 text-sm text-label focus:outline-none focus:ring-2 focus:ring-system-blue
                                    {{ old('_editing_id') == $producto->id && $errors->has('unidad') ? 'border-system-red' : 'border-separator' }}">
                                @php
                                    $selectedUnidad = old('_editing_id') == $producto->id ? old('unidad', $producto->unidad) : $producto->unidad;
                                @endphp
                                @foreach (['unidad', 'kg', 'g', 'litro', 'ml', 'caja', 'paquete', 'par'] as $u)
                                    <option value="{{ $u }}" {{ $selectedUnidad === $u ? 'selected' : '' }}>
                                        {{ ucfirst($u) }}
                                    </option>
                                @endforeach
                            </select>
                            @if (old('_editing_id') == $producto->id)
                                @error('unidad') <p class="text-system-red text-xs mt-1">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        {{-- Nombre --}}
                        <div class="col-span-2">
                            @include('productos._field', [
                                'name'  => 'nombre',
                                'label' => 'Nombre',
                                'type'  => 'text',
                                'value' => old('_editing_id') == $producto->id ? old('nombre') : $producto->nombre,
                            ])
                        </div>

                        {{-- Descripción --}}
                        <div class="col-span-2">
                            <label for="descripcion-{{ $producto->id }}" class="block text-sm font-medium text-secondary-label mb-1">
                                Descripción (opcional)
                            </label>
                            <textarea id="descripcion-{{ $producto->id }}" name="descripcion" rows="3"
                                class="w-full bg-system-background border rounded-lg px-3 py-2 text-sm text-label focus:outline-none focus:ring-2 focus:ring-system-blue
                                    {{ old('_editing_id') == $producto->id && $errors->has('descripcion') ? 'border-system-red' : 'border-separator' }}">{{ old('_editing_id') == $producto->id ? old('descripcion') : $producto->descripcion }}</textarea>
                            @if (old('_editing_id') == $producto->id)
                                @error('descripcion') <p class="text-system-red text-xs mt-1">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        {{-- Precio compra --}}
                        <div>
                            @include('productos._field', [
                                'name'  => 'precio_compra',
                                'label' => 'Precio de compra',
                                'type'  => 'number',
                                'value' => old('_editing_id') == $producto->id ? old('precio_compra') : $producto->precio_compra,
                                'attrs' => 'min="0" step="0.01"',
                            ])
                        </div>

                        {{-- Precio venta --}}
                        <div>
                            @include('productos._field', [
                                'name'  => 'precio_venta',
                                'label' => 'Precio de venta',
                                'type'  => 'number',
                                'value' => old('_editing_id') == $producto->id ? old('precio_venta') : $producto->precio_venta,
                                'attrs' => 'min="0" step="0.01"',
                            ])
                        </div>

                        {{-- Stock --}}
                        <div>
                            @include('productos._field', [
                                'name'  => 'stock',
                                'label' => 'Stock',
                                'type'  => 'number',
                                'value' => old('_editing_id') == $producto->id ? old('stock') : $producto->stock,
                                'attrs' => 'min="0" step="1"',
                            ])
                        </div>

                        {{-- Stock mínimo --}}
                        <div>
                            @include('productos._field', [
                                'name'  => 'stock_minimo',
                                'label' => 'Stock mínimo',
                                'type'  => 'number',
                                'value' => old('_editing_id') == $producto->id ? old('stock_minimo') : $producto->stock_minimo,
                                'attrs' => 'min="0" step="1"',
                            ])
                        </div>

                        {{-- Activo --}}
                        <div class="col-span-2 flex items-center gap-2 pt-1">
                            @php
                                $activoChecked = old('_editing_id') == $producto->id
                                    ? old('activo')
                                    : $producto->activo;
                            @endphp
                            <input type="checkbox" id="activo-{{ $producto->id }}" name="activo" value="1"
                                class="rounded border-separator bg-system-background accent-system-blue"
                                {{ $activoChecked ? 'checked' : '' }}>
                            <label for="activo-{{ $producto->id }}" class="text-sm text-secondary-label">Producto activo</label>
                        </div>
                    </div>

                    <div class="h-px bg-separator my-6"></div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="bg-system-blue hover:bg-system-blue/80 text-label text-sm font-medium px-5 py-2 rounded-lg transition">
                            Guardar cambios
                        </button>
                        <a href="#"
                           class="text-secondary-label hover:text-label text-sm font-medium px-5 py-2 rounded-lg hover:bg-white/5 transition">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
