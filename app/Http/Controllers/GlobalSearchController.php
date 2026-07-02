<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Mascota;
use App\Models\Consulta;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Pago;
use App\Models\Rol;
use App\Models\Menu;
use App\Models\RegistroEvento;
use App\Models\VisitaPagina;
use Illuminate\Support\Facades\Log;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $query = $request->input('q', '');

            if (strlen($query) < 2) {
                return response()->json([
                    'usuarios' => [],
                    'mascotas' => [],
                    'consultas' => [],
                    'servicios' => [],
                    'productos' => [],
                    'categorias' => [],
                    'pagos' => [],
                    'roles' => [],
                    'menus' => [],
                    'eventos' => [],
                    'visitas' => []
                ]);
            }

            $searchTerm = '%' . $query . '%';

            // 1. Usuarios
            $usuarios = Usuario::with('rol')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm)
                      ->orWhere('apellido', 'like', $searchTerm)
                      ->orWhere('email', 'like', $searchTerm)
                      ->orWhere('telefono', 'like', $searchTerm)
                      ->orWhere('numero_documento', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 2. Mascotas
            $mascotas = Mascota::with('dueno')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm)
                      ->orWhere('especie', 'like', $searchTerm)
                      ->orWhere('raza', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 3. Consultas
            $consultas = Consulta::with('mascota', 'veterinario')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('motivo_consulta', 'like', $searchTerm)
                      ->orWhere('diagnostico', 'like', $searchTerm)
                      ->orWhere('estado', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 4. Servicios
            $servicios = Servicio::where(function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm)
                      ->orWhere('descripcion', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 5. Productos
            $productos = Producto::with('categoria')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm)
                      ->orWhere('descripcion', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 6. Categorias
            $categorias = Categoria::where('nombre', 'like', $searchTerm)
                ->orderBy('nombre')
                ->limit(5)
                ->get();

            // 7. Pagos
            $pagos = Pago::with('consulta.mascota')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('estado', 'like', $searchTerm)
                      ->orWhere('tipo_pago', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 8. Roles
            $roles = Rol::where('nombre', 'like', $searchTerm)
                ->orderBy('nombre')
                ->limit(5)
                ->get();

            // 9. Menus
            $menus = Menu::with('rol')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('nombre', 'like', $searchTerm)
                      ->orWhere('ruta', 'like', $searchTerm);
                })
                ->orderBy('orden')
                ->limit(5)
                ->get();

            // 10. Eventos del Sistema
            $eventos = RegistroEvento::with('usuario')
                ->where(function ($q) use ($searchTerm) {
                    $q->where('ruta', 'like', $searchTerm)
                      ->orWhere('descripcion', 'like', $searchTerm);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // 11. Visitas a Paginas
            $visitas = VisitaPagina::where('ruta', 'like', $searchTerm)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'usuarios' => $usuarios,
                'mascotas' => $mascotas,
                'consultas' => $consultas,
                'servicios' => $servicios,
                'productos' => $productos,
                'categorias' => $categorias,
                'pagos' => $pagos,
                'roles' => $roles,
                'menus' => $menus,
                'eventos' => $eventos,
                'visitas' => $visitas
            ]);

        } catch (\Exception $e) {
            Log::error('Error en busqueda global: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error en la busqueda',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
