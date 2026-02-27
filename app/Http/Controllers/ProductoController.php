<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::query()
            ->when($request->search, fn($q, $s) => $q->where('nombre', 'ilike', "%$s%")
                                                      ->orWhere('codigo', 'ilike', "%$s%"))
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'        => ['nullable', 'string', 'max:100', 'unique:productos'],
            'nombre'        => ['required', 'string', 'max:255'],
            'descripcion'   => ['nullable', 'string'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'precio_venta'  => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'stock_minimo'  => ['required', 'integer', 'min:0'],
            'unidad'        => ['required', 'string', 'max:50'],
            'activo'        => ['boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo', true);

        Producto::create($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'codigo'        => ['nullable', 'string', 'max:100', 'unique:productos,codigo,' . $producto->id],
            'nombre'        => ['required', 'string', 'max:255'],
            'descripcion'   => ['nullable', 'string'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'precio_venta'  => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'stock_minimo'  => ['required', 'integer', 'min:0'],
            'unidad'        => ['required', 'string', 'max:50'],
            'activo'        => ['boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo');

        $producto->update($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
