<?php
namespace App\Http\Controllers\Veterinario;
use App\Http\Controllers\Controller;
use App\Models\Consulta;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $vet = Auth::user();
        $stats = [
            'total_consultas' => Consulta::where('veterinario_id', $vet->id)->count(),
            'total_mascotas' => Consulta::where('veterinario_id', $vet->id)->distinct('mascota_id')->count('mascota_id'),
            'consultas_hoy' => Consulta::where('veterinario_id', $vet->id)->whereDate('fecha_consulta', today())->count(),
        ];

        $consultas_recientes = Consulta::where('veterinario_id', $vet->id)
            ->with('mascota')
            ->latest('fecha_consulta')
            ->limit(5)
            ->get();

        return Inertia::render('Veterinario/Dashboard', compact('stats', 'consultas_recientes'));
    }
}
