<?php
namespace App\Http\Controllers\Cliente;
use App\Http\Controllers\Controller;
use App\Models\{Mascota, Consulta};
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $cliente = Auth::user();
        $mascotaIds = Mascota::where('dueno_id', $cliente->id)->pluck('id');

        $stats = [
            'total_mascotas' => $mascotaIds->count(),
            'total_consultas' => Consulta::whereIn('mascota_id', $mascotaIds)->count(),
        ];

        $mascotas = Mascota::where('dueno_id', $cliente->id)->get();
        $consultas_recientes = Consulta::whereIn('mascota_id', $mascotaIds)
            ->with('mascota', 'veterinario')
            ->latest('fecha_consulta')
            ->limit(5)
            ->get();

        return Inertia::render('Cliente/Dashboard', compact('stats', 'mascotas', 'consultas_recientes'));
    }
}
