<?php
namespace App\Http\Controllers\Cliente;
use App\Http\Controllers\Controller;
use App\Models\Mascota;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MascotaController extends Controller
{
    public function index()
    {
        $mascotas = Mascota::where('dueno_id', Auth::id())->paginate(15);
        return Inertia::render('Cliente/Mascotas/Index', compact('mascotas'));
    }

    public function show(Mascota $mascota)
    {
        if ($mascota->dueno_id !== Auth::id()) {
            abort(404);
        }
        $mascota->load('dueno');
        return Inertia::render('Cliente/Mascotas/Show', compact('mascota'));
    }
}
