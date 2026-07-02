<?php
namespace App\Http\Controllers\Veterinario;
use App\Http\Controllers\Controller;
use App\Models\Mascota;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MascotaController extends Controller
{
    public function index()
    {
        $mascotas = Mascota::whereHas('consultas', function ($q) {
            $q->where('veterinario_id', Auth::id());
        })->with('dueno')->paginate(15);

        return Inertia::render('Veterinario/Mascotas/Index', compact('mascotas'));
    }
}
