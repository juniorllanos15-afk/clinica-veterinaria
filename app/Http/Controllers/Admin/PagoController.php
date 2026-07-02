<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Consulta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with('consulta.mascota', 'consulta.veterinario')->paginate(15);
        return Inertia::render('Admin/Pagos/Index', compact('pagos'));
    }

    public function create()
    {
        $consultas = Consulta::all();
        return Inertia::render('Admin/Pagos/Create', compact('consultas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'consulta_id' => 'required|exists:consulta,id|unique:pago,consulta_id',
            'tipo_pago' => 'required|in:contado,credito',
            'cantidad_cuotas' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
        ], [
            'consulta_id.required' => 'La consulta es obligatoria',
            'consulta_id.exists' => 'La consulta seleccionada no es válida',
            'consulta_id.unique' => 'Esta consulta ya tiene un pago registrado',
            'tipo_pago.required' => 'El tipo de pago es obligatorio',
            'tipo_pago.in' => 'El tipo de pago debe ser: contado o crédito',
            'cantidad_cuotas.required' => 'La cantidad de cuotas es obligatoria',
            'cantidad_cuotas.integer' => 'La cantidad de cuotas debe ser un número entero',
            'cantidad_cuotas.min' => 'La cantidad de cuotas debe ser al menos 1',
            'total.required' => 'El total es obligatorio',
            'total.numeric' => 'El total debe ser un valor numérico',
            'total.min' => 'El total debe ser mayor o igual a 0',
            'fecha_pago.required' => 'La fecha de pago es obligatoria',
            'fecha_pago.date' => 'La fecha de pago debe ser una fecha válida',
        ]);

        Pago::create($validated);

        return redirect()->route('admin.pagos.index')->with('success', 'Pago creado exitosamente');
    }

    public function edit(Pago $pago)
    {
        $pago->load('consulta');
        $consultas = Consulta::all();
        return Inertia::render('Admin/Pagos/Edit', compact('pago', 'consultas'));
    }

    public function update(Request $request, Pago $pago)
    {
        $validated = $request->validate([
            'consulta_id' => 'required|exists:consulta,id|unique:pago,consulta_id,' . $pago->id,
            'tipo_pago' => 'required|in:contado,credito',
            'cantidad_cuotas' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
        ], [
            'consulta_id.required' => 'La consulta es obligatoria',
            'consulta_id.exists' => 'La consulta seleccionada no es válida',
            'consulta_id.unique' => 'Esta consulta ya tiene un pago registrado',
            'tipo_pago.required' => 'El tipo de pago es obligatorio',
            'tipo_pago.in' => 'El tipo de pago debe ser: contado o crédito',
            'cantidad_cuotas.required' => 'La cantidad de cuotas es obligatoria',
            'cantidad_cuotas.integer' => 'La cantidad de cuotas debe ser un número entero',
            'cantidad_cuotas.min' => 'La cantidad de cuotas debe ser al menos 1',
            'total.required' => 'El total es obligatorio',
            'total.numeric' => 'El total debe ser un valor numérico',
            'total.min' => 'El total debe ser mayor o igual a 0',
            'fecha_pago.required' => 'La fecha de pago es obligatoria',
            'fecha_pago.date' => 'La fecha de pago debe ser una fecha válida',
        ]);

        $pago->update($validated);
        return redirect()->route('admin.pagos.index')->with('success', 'Pago actualizado');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('admin.pagos.index')->with('success', 'Pago eliminado');
    }
}
