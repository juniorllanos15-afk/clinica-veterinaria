<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Consulta;
use App\Models\Mascota;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Usuario;
use Illuminate\Database\QueryException;
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
        $productos = Producto::where('estado', 'activo')->get();
        $servicios = Servicio::where('estado', 'activo')->get();
        return Inertia::render('Admin/Consultas/Create', compact('mascotas', 'veterinarios', 'productos', 'servicios'));
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
            'servicios' => 'nullable|array',
            'servicios.*.id' => 'required|exists:servicio,id',
            'servicios.*.cantidad' => 'required|integer|min:1',
            'servicios.*.precio' => 'required|numeric|min:0',
            'productos' => 'nullable|array',
            'productos.*.id' => 'required|exists:producto,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ], [
            'fecha_consulta.required' => 'La fecha de consulta es obligatoria',
            'fecha_consulta.date' => 'La fecha de consulta debe ser una fecha válida',
            'mascota_id.required' => 'La mascota es obligatoria',
            'mascota_id.exists' => 'La mascota seleccionada no es válida',
            'veterinario_id.required' => 'El veterinario es obligatorio',
            'veterinario_id.exists' => 'El veterinario seleccionado no es válido',
        ]);

        $consulta = Consulta::create($validated);

        if ($request->has('servicios')) {
            $serviciosData = collect($request->servicios)->mapWithKeys(fn($s) => [
                $s['id'] => [
                    'cantidad' => $s['cantidad'],
                    'precio' => $s['precio'],
                    'subtotal' => $s['cantidad'] * $s['precio'],
                ]
            ]);
            $consulta->servicios()->attach($serviciosData);
        }

        if ($request->has('productos')) {
            $productosData = collect($request->productos)->mapWithKeys(fn($p) => [
                $p['id'] => [
                    'cantidad' => $p['cantidad'],
                    'precio' => $p['precio'],
                    'subtotal' => $p['cantidad'] * $p['precio'],
                ]
            ]);
            $consulta->productos()->attach($productosData);
        }

        return redirect()->route('admin.consultas.index')->with('success', 'Consulta creada exitosamente');
    }

    public function edit(Consulta $consulta)
    {
        $consulta->load('mascota', 'veterinario', 'servicios', 'productos');
        $mascotas = Mascota::all();
        $veterinarios = Usuario::where('rol_id', 2)->get();
        $productos = Producto::where('estado', 'activo')->get();
        $servicios = Servicio::where('estado', 'activo')->get();
        return Inertia::render('Admin/Consultas/Edit', compact('consulta', 'mascotas', 'veterinarios', 'productos', 'servicios'));
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
            'servicios' => 'nullable|array',
            'servicios.*.id' => 'required|exists:servicio,id',
            'servicios.*.cantidad' => 'required|integer|min:1',
            'servicios.*.precio' => 'required|numeric|min:0',
            'productos' => 'nullable|array',
            'productos.*.id' => 'required|exists:producto,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
        ], [
            'fecha_consulta.required' => 'La fecha de consulta es obligatoria',
            'fecha_consulta.date' => 'La fecha de consulta debe ser una fecha válida',
            'mascota_id.required' => 'La mascota es obligatoria',
            'mascota_id.exists' => 'La mascota seleccionada no es válida',
            'veterinario_id.required' => 'El veterinario es obligatorio',
            'veterinario_id.exists' => 'El veterinario seleccionado no es válido',
        ]);

        $consulta->update($validated);

        if ($request->has('servicios')) {
            $serviciosData = collect($request->servicios)->mapWithKeys(fn($s) => [
                $s['id'] => [
                    'cantidad' => $s['cantidad'],
                    'precio' => $s['precio'],
                    'subtotal' => $s['cantidad'] * $s['precio'],
                ]
            ]);
            $consulta->servicios()->sync($serviciosData);
        } else {
            $consulta->servicios()->detach();
        }

        if ($request->has('productos')) {
            $productosData = collect($request->productos)->mapWithKeys(fn($p) => [
                $p['id'] => [
                    'cantidad' => $p['cantidad'],
                    'precio' => $p['precio'],
                    'subtotal' => $p['cantidad'] * $p['precio'],
                ]
            ]);
            $consulta->productos()->sync($productosData);
        } else {
            $consulta->productos()->detach();
        }

        return redirect()->route('admin.consultas.index')->with('success', 'Consulta actualizada');
    }

    public function destroy(Consulta $consulta)
    {
        try {
            // Desvincular relaciones pivot primero
            $consulta->servicios()->detach();
            $consulta->productos()->detach();

            // Si tiene pago asociado, no permitir eliminar
            if ($consulta->pago()->exists()) {
                return redirect()->route('admin.consultas.index')
                    ->with('error', 'No se puede eliminar la consulta porque tiene un pago registrado. Anule el pago primero.');
            }

            $consulta->delete();
            return redirect()->route('admin.consultas.index')->with('success', 'Consulta eliminada');
        } catch (QueryException $e) {
            $message = 'No se puede eliminar la consulta porque está siendo referenciada en otros registros del sistema.';
            if (str_contains($e->getMessage(), 'consulta_servicio')) {
                $message = 'No se puede eliminar la consulta porque tiene servicios asociados.';
            } elseif (str_contains($e->getMessage(), 'consulta_producto')) {
                $message = 'No se puede eliminar la consulta porque tiene productos asociados.';
            } elseif (str_contains($e->getMessage(), 'pago')) {
                $message = 'No se puede eliminar la consulta porque tiene un pago registrado.';
            }
            return redirect()->route('admin.consultas.index')->with('error', $message);
        }
    }
}
