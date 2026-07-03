<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Mascota;
use Inertia\Inertia;

class HistorialController extends Controller
{
    public function index()
    {
        $mascotas = Mascota::with('dueno')->withCount('consultas')->paginate(15);
        return Inertia::render('Admin/Historial/Index', compact('mascotas'));
    }

    public function show(Mascota $mascota)
    {
        $mascota->load(['dueno', 'consultas' => function($q) {
            $q->with(['veterinario', 'servicios', 'productos', 'pago.cuotas'])
              ->latest('fecha_consulta');
        }]);

        return Inertia::render('Admin/Historial/Show', compact('mascota'));
    }
}
