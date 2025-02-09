<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 🔹 Mostrar todos los productos
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // 🔹 Mostrar el formulario de creación
    public function create()
    {
        return view('products.create');
    }

    // 🔹 Guardar un nuevo producto
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'No tienes permisos para registrar productos.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    // 🔹 Mostrar el formulario de edición
    public function edit(Product $product)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'No tienes permisos para editar productos.');
        }

        return view('products.edit', compact('product'));
    }

    // 🔹 Actualizar un producto
    public function update(Request $request, Product $product)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'No tienes permisos para actualizar productos.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    // 🔹 Eliminar un producto
    public function destroy(Product $product)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('products.index')->with('error', 'No tienes permisos para eliminar productos.');
        }
        
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente.');
    }
}
