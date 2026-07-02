<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Mascota, Consulta, Pago, Usuario, RegistroEvento};
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_mascotas' => Mascota::count(),
            'total_consultas' => Consulta::count(),
            'total_clientes' => Usuario::where('rol_id', 3)->count(),
            'total_veterinarios' => Usuario::where('rol_id', 2)->count(),
            'total_ingresos' => Pago::where('estado', 'Pagado')->sum('total'),
            'ingresos_pendientes' => Pago::where('estado', 'Pendiente')->sum('total'),
        ];

        $consultas_por_estado = Consulta::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        $ingresos_mensuales = Pago::select(
                DB::raw('EXTRACT(MONTH FROM fecha_pago) as mes'),
                DB::raw('EXTRACT(YEAR FROM fecha_pago) as anio'),
                DB::raw('SUM(total) as total')
            )
            ->where('estado', 'Pagado')
            ->where('fecha_pago', '>=', now()->subMonths(6))
            ->groupBy('mes', 'anio')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        $mascotas_por_especie = Mascota::select('especie', DB::raw('count(*) as total'))
            ->groupBy('especie')
            ->get();

        $consultas_recientes = Consulta::with(['mascota', 'veterinario'])
            ->latest('fecha_consulta')
            ->limit(10)
            ->get();

        $eventos_recientes = RegistroEvento::with('usuario')
            ->latest()
            ->limit(10)
            ->get();

        return Inertia::render('Admin/Dashboard', compact(
            'stats',
            'consultas_por_estado',
            'ingresos_mensuales',
            'mascotas_por_especie',
            'consultas_recientes',
            'eventos_recientes'
        ));
    }
}
