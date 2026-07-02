<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Consulta, Mascota, Pago, Producto, Servicio, Usuario};
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReporteController extends Controller
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

        $consultas_por_mes = Consulta::select(
                DB::raw('EXTRACT(MONTH FROM fecha_consulta) as mes'),
                DB::raw('EXTRACT(YEAR FROM fecha_consulta) as anio'),
                DB::raw('COUNT(*) as total')
            )
            ->where('fecha_consulta', '>=', now()->subMonths(6))
            ->groupBy('mes', 'anio')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get();

        $servicios_populares = Servicio::select('servicio.*', DB::raw('COUNT(*) as total_usos'))
            ->leftJoin('consulta_servicio', 'servicio.id', '=', 'consulta_servicio.servicio_id')
            ->groupBy('servicio.id')
            ->orderByDesc('total_usos')
            ->limit(5)
            ->get();

        $productos_vendidos = Producto::select('producto.*', DB::raw('SUM(consulta_producto.cantidad) as total_vendido'))
            ->leftJoin('consulta_producto', 'producto.id', '=', 'consulta_producto.producto_id')
            ->groupBy('producto.id')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        $consultas_por_vet = Usuario::select('usuario.*', DB::raw('COUNT(consulta.id) as total_consultas'))
            ->where('rol_id', 2)
            ->leftJoin('consulta', 'usuario.id', '=', 'consulta.veterinario_id')
            ->groupBy('usuario.id')
            ->orderByDesc('total_consultas')
            ->get();

        return Inertia::render('Admin/Reportes/Index', compact(
            'stats', 'consultas_por_mes', 'servicios_populares',
            'productos_vendidos', 'consultas_por_vet'
        ));
    }
}
