<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->paginate(15);
        return Inertia::render('Admin/Productos/Index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return Inertia::render('Admin/Productos/Create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo_producto' => 'required|string|unique:producto,codigo_producto',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categoria,id',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'codigo_producto.required' => 'El código del producto es obligatorio',
            'codigo_producto.unique' => 'Este código de producto ya está registrado',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'stock.required' => 'El stock es obligatorio',
            'stock.integer' => 'El stock debe ser un número entero',
            'stock.min' => 'El stock debe ser mayor o igual a 0',
            'precio.required' => 'El precio es obligatorio',
            'precio.numeric' => 'El precio debe ser un valor numérico',
            'precio.min' => 'El precio debe ser mayor o igual a 0',
            'categoria_id.required' => 'La categoría es obligatoria',
            'categoria_id.exists' => 'La categoría seleccionada no es válida',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser: activo o inactivo',
        ]);

        Producto::create($validated);

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado exitosamente');
    }

    public function edit(Producto $producto)
    {
        $producto->load('categoria');
        $categorias = Categoria::all();
        return Inertia::render('Admin/Productos/Edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'codigo_producto' => 'required|string|unique:producto,codigo_producto,' . $producto->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categoria,id',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'codigo_producto.required' => 'El código del producto es obligatorio',
            'codigo_producto.unique' => 'Este código de producto ya está registrado',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'stock.required' => 'El stock es obligatorio',
            'stock.integer' => 'El stock debe ser un número entero',
            'stock.min' => 'El stock debe ser mayor o igual a 0',
            'precio.required' => 'El precio es obligatorio',
            'precio.numeric' => 'El precio debe ser un valor numérico',
            'precio.min' => 'El precio debe ser mayor o igual a 0',
            'categoria_id.required' => 'La categoría es obligatoria',
            'categoria_id.exists' => 'La categoría seleccionada no es válida',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser: activo o inactivo',
        ]);

        $producto->update($validated);
        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado');
    }
}
