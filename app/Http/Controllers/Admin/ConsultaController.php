<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\Mascota;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConsultaController extends Controller
{
    public function index()
    {
        $consultas = Consulta::with('mascota', 'veterinario')->paginate(15);
        return Inertia::render('Admin/Consultas/Index', compact('consultas'));
    }

    public function create()
    {
        $mascotas = Mascota::all();
        $veterinarios = Usuario::where('rol_id', 2)->get();
        return Inertia::render('Admin/Consultas/Create', compact('mascotas', 'veterinarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_consulta' => 'required|date',
            'motivo_consulta' => 'nullable|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'mascota_id' => 'required|exists:mascota,id',
            'veterinario_id' => 'required|exists:usuario,id',
        ], [
            'fecha_consulta.required' => 'La fecha de consulta es obligatoria',
            'fecha_consulta.date' => 'La fecha de consulta debe ser una fecha válida',
            'mascota_id.required' => 'La mascota es obligatoria',
            'mascota_id.exists' => 'La mascota seleccionada no es válida',
            'veterinario_id.required' => 'El veterinario es obligatorio',
            'veterinario_id.exists' => 'El veterinario seleccionado no es válido',
        ]);

        Consulta::create($validated);

        return redirect()->route('admin.consultas.index')->with('success', 'Consulta creada exitosamente');
    }

    public function edit(Consulta $consulta)
    {
        $consulta->load('mascota', 'veterinario');
        $mascotas = Mascota::all();
        $veterinarios = Usuario::where('rol_id', 2)->get();
        return Inertia::render('Admin/Consultas/Edit', compact('consulta', 'mascotas', 'veterinarios'));
    }

    public function update(Request $request, Consulta $consulta)
    {
        $validated = $request->validate([
            'fecha_consulta' => 'required|date',
            'motivo_consulta' => 'nullable|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'mascota_id' => 'required|exists:mascota,id',
            'veterinario_id' => 'required|exists:usuario,id',
        ], [
            'fecha_consulta.required' => 'La fecha de consulta es obligatoria',
            'fecha_consulta.date' => 'La fecha de consulta debe ser una fecha válida',
            'mascota_id.required' => 'La mascota es obligatoria',
            'mascota_id.exists' => 'La mascota seleccionada no es válida',
            'veterinario_id.required' => 'El veterinario es obligatorio',
            'veterinario_id.exists' => 'El veterinario seleccionado no es válido',
        ]);

        $consulta->update($validated);
        return redirect()->route('admin.consultas.index')->with('success', 'Consulta actualizada');
    }

    public function destroy(Consulta $consulta)
    {
        $consulta->delete();
        return redirect()->route('admin.consultas.index')->with('success', 'Consulta eliminada');
    }
}
