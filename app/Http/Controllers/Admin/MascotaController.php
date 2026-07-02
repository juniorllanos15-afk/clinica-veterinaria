<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Mascota;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MascotaController extends Controller
{
    public function index()
    {
        $mascotas = Mascota::with('dueno')->paginate(15);
        return Inertia::render('Admin/Mascotas/Index', compact('mascotas'));
    }

    public function create()
    {
        $clientes = Usuario::where('rol_id', 3)->get();
        return Inertia::render('Admin/Mascotas/Create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'nullable|string|max:100',
            'raza' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'peso' => 'nullable|numeric|min:0',
            'dueno_id' => 'required|exists:usuario,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'especie.max' => 'La especie no puede tener más de 100 caracteres',
            'raza.max' => 'La raza no puede tener más de 100 caracteres',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'sexo.max' => 'El sexo no puede tener más de 20 caracteres',
            'color.max' => 'El color no puede tener más de 50 caracteres',
            'peso.numeric' => 'El peso debe ser un valor numérico',
            'peso.min' => 'El peso debe ser mayor o igual a 0',
            'dueno_id.required' => 'El dueño es obligatorio',
            'dueno_id.exists' => 'El dueño seleccionado no es válido',
        ]);

        Mascota::create($validated);

        return redirect()->route('admin.mascotas.index')->with('success', 'Mascota creada exitosamente');
    }

    public function edit(Mascota $mascota)
    {
        $mascota->load('dueno');
        $clientes = Usuario::where('rol_id', 3)->get();
        return Inertia::render('Admin/Mascotas/Edit', compact('mascota', 'clientes'));
    }

    public function update(Request $request, Mascota $mascota)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'nullable|string|max:100',
            'raza' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'peso' => 'nullable|numeric|min:0',
            'dueno_id' => 'required|exists:usuario,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'especie.max' => 'La especie no puede tener más de 100 caracteres',
            'raza.max' => 'La raza no puede tener más de 100 caracteres',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'sexo.max' => 'El sexo no puede tener más de 20 caracteres',
            'color.max' => 'El color no puede tener más de 50 caracteres',
            'peso.numeric' => 'El peso debe ser un valor numérico',
            'peso.min' => 'El peso debe ser mayor o igual a 0',
            'dueno_id.required' => 'El dueño es obligatorio',
            'dueno_id.exists' => 'El dueño seleccionado no es válido',
        ]);

        $mascota->update($validated);
        return redirect()->route('admin.mascotas.index')->with('success', 'Mascota actualizada');
    }

    public function destroy(Mascota $mascota)
    {
        $mascota->delete();
        return redirect()->route('admin.mascotas.index')->with('success', 'Mascota eliminada');
    }
}
