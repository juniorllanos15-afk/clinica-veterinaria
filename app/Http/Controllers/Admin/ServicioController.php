<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::paginate(15);
        return Inertia::render('Admin/Servicios/Index', compact('servicios'));
    }

    public function create()
    {
        return Inertia::render('Admin/Servicios/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo_servicio' => 'required|string|unique:servicio,codigo_servicio',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_estimada' => 'nullable|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'codigo_servicio.required' => 'El código del servicio es obligatorio',
            'codigo_servicio.unique' => 'Este código de servicio ya está registrado',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'duracion_estimada.integer' => 'La duración estimada debe ser un número entero',
            'duracion_estimada.min' => 'La duración estimada debe ser al menos 1 minuto',
            'precio.required' => 'El precio es obligatorio',
            'precio.numeric' => 'El precio debe ser un valor numérico',
            'precio.min' => 'El precio debe ser mayor o igual a 0',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser: activo o inactivo',
        ]);

        Servicio::create($validated);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado exitosamente');
    }

    public function edit(Servicio $servicio)
    {
        return Inertia::render('Admin/Servicios/Edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $validated = $request->validate([
            'codigo_servicio' => 'required|string|unique:servicio,codigo_servicio,' . $servicio->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_estimada' => 'nullable|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'codigo_servicio.required' => 'El código del servicio es obligatorio',
            'codigo_servicio.unique' => 'Este código de servicio ya está registrado',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'duracion_estimada.integer' => 'La duración estimada debe ser un número entero',
            'duracion_estimada.min' => 'La duración estimada debe ser al menos 1 minuto',
            'precio.required' => 'El precio es obligatorio',
            'precio.numeric' => 'El precio debe ser un valor numérico',
            'precio.min' => 'El precio debe ser mayor o igual a 0',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser: activo o inactivo',
        ]);

        $servicio->update($validated);
        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('admin.servicios.index')->with('success', 'Servicio eliminado');
    }
}
