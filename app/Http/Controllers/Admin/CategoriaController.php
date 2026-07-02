<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::paginate(15);
        return Inertia::render('Admin/Categorias/Index', compact('categorias'));
    }

    public function create()
    {
        return Inertia::render('Admin/Categorias/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categoria,nombre',
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'nombre.unique' => 'Este nombre de categoría ya está registrado',
        ]);

        Categoria::create($validated);

        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada exitosamente');
    }

    public function edit(Categoria $categoria)
    {
        return Inertia::render('Admin/Categorias/Edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categoria,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'nombre.unique' => 'Este nombre de categoría ya está registrado',
        ]);

        $categoria->update($validated);
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada');
    }
}
