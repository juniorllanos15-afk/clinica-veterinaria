<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Consulta;
use App\Models\PagoCuota;
use App\Services\PagoFacilService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $consultas = Consulta::with('mascota', 'servicios', 'productos')
            ->whereDoesntHave('pago', fn($q) => $q->where('estado', 'pagado'))
            ->get();
        return Inertia::render('Admin/Pagos/Create', compact('consultas'));
    }

    public function store(Request $request, PagoFacilService $pagoFacil)
    {
        $validated = $request->validate([
            'consulta_id' => 'required|exists:consulta,id|unique:pago,consulta_id',
            'tipo_pago' => 'required|in:contado,credito',
            'cantidad_cuotas' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'cuotas' => 'nullable|array',
            'cuotas.*.numero_cuota' => 'required|integer|min:1',
            'cuotas.*.monto' => 'required|numeric|min:0',
            'cuotas.*.fecha_vencimiento' => 'required|date',
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
            'cuotas.*.numero_cuota.required' => 'El número de cuota es obligatorio',
            'cuotas.*.monto.required' => 'El monto de cada cuota es obligatorio',
            'cuotas.*.fecha_vencimiento.required' => 'La fecha de vencimiento de cada cuota es obligatoria',
        ]);

        $pago = Pago::create($validated);

        $pago->load('consulta.mascota.dueno');
        $dueno = $pago->consulta?->mascota?->dueno;

        if ($request->has('cuotas')) {
            foreach ($request->cuotas as $cuotaData) {
                $cuota = $pago->cuotas()->create([
                    'numero_cuota' => $cuotaData['numero_cuota'],
                    'monto' => $cuotaData['monto'],
                    'fecha_vencimiento' => $cuotaData['fecha_vencimiento'],
                    'estado' => 'pendiente',
                ]);

                $qrResult = $pagoFacil->generateQr(
                    $cuota->id,
                    $dueno ? trim($dueno->nombre . ' ' . $dueno->apellido) : 'Cliente',
                    $dueno?->email ?? 'cliente@example.com',
                    $dueno?->telefono ?? '00000000',
                    (float) $cuota->monto
                );

                if ($qrResult['success']) {
                    $cuota->update([
                        'transaction_id' => $qrResult['transaction_id'],
                        'payment_number' => $qrResult['payment_number'],
                        'estado_pago' => $qrResult['status'] ?? 'pendiente',
                        'qr_base64' => $qrResult['qr_base64'],
                    ]);
                } else {
                    Log::warning('No se pudo generar QR para cuota #' . $cuota->id, [
                        'error' => $qrResult['error'] ?? 'Error desconocido',
                    ]);
                }
            }
        }

        return redirect()->route('admin.pagos.index')->with('success', 'Pago creado exitosamente');
    }

    public function edit(Pago $pago)
    {
        $pago->load('consulta', 'cuotas');
        $consultas = Consulta::with('mascota', 'servicios', 'productos')
            ->whereDoesntHave('pago', fn($q) => $q->where('estado', 'pagado'))
            ->orWhere('id', $pago->consulta_id)
            ->get();
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
            'cuotas' => 'nullable|array',
            'cuotas.*.id' => 'nullable|exists:pago_cuota,id',
            'cuotas.*.numero_cuota' => 'required|integer|min:1',
            'cuotas.*.monto' => 'required|numeric|min:0',
            'cuotas.*.fecha_vencimiento' => 'required|date',
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

        $sentIds = [];
        if ($request->has('cuotas')) {
            foreach ($request->cuotas as $cuota) {
                if (!empty($cuota['id'])) {
                    $pago->cuotas()->where('id', $cuota['id'])->update([
                        'numero_cuota' => $cuota['numero_cuota'],
                        'monto' => $cuota['monto'],
                        'fecha_vencimiento' => $cuota['fecha_vencimiento'],
                    ]);
                    $sentIds[] = $cuota['id'];
                } else {
                    $new = $pago->cuotas()->create([
                        'numero_cuota' => $cuota['numero_cuota'],
                        'monto' => $cuota['monto'],
                        'fecha_vencimiento' => $cuota['fecha_vencimiento'],
                        'estado' => 'pendiente',
                    ]);
                    $sentIds[] = $new->id;
                }
            }
            $pago->cuotas()->whereNotIn('id', $sentIds)->delete();
        }

        return redirect()->route('admin.pagos.index')->with('success', 'Pago actualizado');
    }

    public function show(Pago $pago)
    {
        $pago->load('consulta.mascota.dueno', 'cuotas');
        return Inertia::render('Admin/Pagos/Show', compact('pago'));
    }

    public function generateCuotaQr(Pago $pago, PagoCuota $cuota, PagoFacilService $pagoFacil)
    {
        if ($cuota->pago_id !== $pago->id) {
            abort(404);
        }

        if ($cuota->estado === 'pagado') {
            return response()->json(['success' => false, 'error' => 'La cuota ya fue pagada'], 422);
        }

        $pago->load('consulta.mascota.dueno');
        $amount = request('amount', $cuota->monto);
        $dueno = $pago->consulta?->mascota?->dueno;

        $qrResult = $pagoFacil->generateQr(
            $cuota->id,
            $dueno ? trim($dueno->nombre . ' ' . $dueno->apellido) : 'Cliente',
            $dueno?->email ?? 'cliente@example.com',
            $dueno?->telefono ?? '00000000',
            (float) $amount
        );

        if ($qrResult['success']) {
            $cuota->update([
                'transaction_id' => $qrResult['transaction_id'],
                'payment_number' => $qrResult['payment_number'],
                'estado_pago' => $qrResult['status'] ?? 'pendiente',
                'qr_base64' => $qrResult['qr_base64'],
            ]);

            return response()->json([
                'success' => true,
                'qr_base64' => $qrResult['qr_base64'],
                'payment_number' => $qrResult['payment_number'],
                'amount' => $amount,
            ]);
        }

        return response()->json(['success' => false, 'error' => $qrResult['error'] ?? 'Error generando QR'], 500);
    }

    public function status(Pago $pago)
    {
        return response()->json([
            'estado_pago' => $pago->estado_pago,
            'estado' => $pago->estado,
        ]);
    }

    public function destroy(Pago $pago)
    {
        try {
            $pago->cuotas()->delete();
            $pago->delete();
            return redirect()->route('admin.pagos.index')->with('success', 'Pago eliminado');
        } catch (\Exception $e) {
            Log::error('Error al eliminar pago', [
                'pago_id' => $pago->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('admin.pagos.index')->with('error', 'No se pudo eliminar el pago: ' . $e->getMessage());
        }
    }
}
