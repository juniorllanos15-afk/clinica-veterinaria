<?php
namespace App\Http\Controllers\Veterinario;
use App\Http\Controllers\Controller;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConsultaController extends Controller
{
    public function index()
    {
        $consultas = Consulta::where('veterinario_id', Auth::id())
            ->with('mascota')
            ->latest('fecha_consulta')
            ->paginate(15);

        return Inertia::render('Veterinario/Consultas/Index', compact('consultas'));
    }

    public function show(Consulta $consulta)
    {
        if ($consulta->veterinario_id !== Auth::id()) {
            abort(403);
        }

        $consulta->load('mascota', 'servicios', 'productos', 'pago.cuotas');

        return Inertia::render('Veterinario/Consultas/Show', compact('consulta'));
    }
}
