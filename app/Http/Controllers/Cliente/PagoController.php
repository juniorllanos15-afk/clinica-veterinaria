<?php
namespace App\Http\Controllers\Cliente;
use App\Http\Controllers\Controller;
use App\Models\{Mascota, Pago};
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PagoController extends Controller
{
    public function index()
    {
        $mascotaIds = Mascota::where('dueno_id', Auth::id())->pluck('id');
        $pagos = Pago::whereHas('consulta', function ($q) use ($mascotaIds) {
                $q->whereIn('mascota_id', $mascotaIds);
            })
            ->with('consulta.mascota', 'cuotas')
            ->paginate(15);
        return Inertia::render('Cliente/Pagos/Index', compact('pagos'));
    }

    public function show(Pago $pago)
    {
        $mascotaIds = Mascota::where('dueno_id', Auth::id())->pluck('id');
        $pago->load('consulta.mascota', 'cuotas');
        if (!$pago->consulta || !in_array($pago->consulta->mascota_id, $mascotaIds->toArray())) {
            abort(404);
        }
        return Inertia::render('Cliente/Pagos/Show', compact('pago'));
    }
}
