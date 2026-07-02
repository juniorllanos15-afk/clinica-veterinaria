<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Categoria;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function home()
    {
        $servicios = Servicio::with('categoria')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return Inertia::render('Public/Home', [
            'servicios' => $servicios
        ]);
    }

    public function servicios()
    {
        $servicios = Servicio::with('categoria')
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::orderBy('nombre')->get();

        return Inertia::render('Public/Servicios', [
            'servicios' => $servicios,
            'categorias' => $categorias
        ]);
    }

    public function nosotros()
    {
        return Inertia::render('Public/Nosotros');
    }
}
