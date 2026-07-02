<?php
namespace App\Http\Controllers\Cliente;
use App\Http\Controllers\Controller;
use App\Models\{Mascota, Consulta};
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConsultaController extends Controller
{
    public function index()
    {
        $mascotaIds = Mascota::where('dueno_id', Auth::id())->pluck('id');
        $consultas = Consulta::whereIn('mascota_id', $mascotaIds)
            ->with('mascota', 'veterinario')
            ->paginate(15);
        return Inertia::render('Cliente/Consultas/Index', compact('consultas'));
    }
}
