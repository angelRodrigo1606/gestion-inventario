@extends('layouts.app')

@section('title', 'Nuevo producto')

@section('content')
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('productos.index') }}" class="text-secondary-label hover:text-label transition text-sm">
            ← Productos
        </a>
        <span class="text-separator">/</span>
        <h1 class="text-2xl font-bold text-label">Nuevo producto</h1>
    </div>

    <div class="bg-secondary-system-background rounded-2xl p-8 max-w-2xl">
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
                <a href="{{ route('productos.index') }}"
                   class="text-secondary-label hover:text-label text-sm font-medium px-5 py-2 rounded-lg hover:bg-white/5 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
