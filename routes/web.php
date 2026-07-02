<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\PublicController;
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboard,
    UsuarioController,
    CategoriaController,
    ServicioController,
    ProductoController,
    MascotaController,
    ConsultaController,
    PagoController,
    HistorialController,
    ReporteController,
    RolController,
    MenuController,
    RegistroEventoController,
    VisitaPaginaController,
    DatabaseManagerController
};
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Rutas públicas
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/servicios', [PublicController::class, 'servicios'])->name('servicios.publicos');
Route::get('/nosotros', [PublicController::class, 'nosotros'])->name('nosotros');

// Autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Perfil de usuario
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return Inertia::render('Profile/Edit');
    })->name('profile.edit');

    Route::put('/profile', function (Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . auth()->id(),
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'genero' => 'nullable|in:Masculino,Femenino,Otro'
        ]);
        auth()->user()->update($request->only(['nombre', 'apellido', 'email', 'telefono', 'fecha_nacimiento', 'genero']));
        return redirect()->route('profile.edit')->with('success', 'Perfil actualizado correctamente');
    })->name('profile.update');

    Route::put('/password', function (Request $request) {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed'
        ]);
        auth()->user()->update(['password' => bcrypt($request->password)]);
        return redirect()->route('profile.edit')->with('success', 'Contraseña actualizada correctamente');
    })->name('password.update');
});

// ===========================================
// Rutas del Administrador
// ===========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('usuarios', UsuarioController::class);
    Route::get('roles', [RolController::class, 'index'])->name('roles.index');
    Route::resource('menus', MenuController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('servicios', ServicioController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('mascotas', MascotaController::class);
    Route::resource('consultas', ConsultaController::class);
    Route::resource('pagos', PagoController::class);

    Route::get('historial', [HistorialController::class, 'index'])->name('historial.index');
    Route::get('historial/{mascota}', [HistorialController::class, 'show'])->name('historial.show');
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');

    Route::get('eventos', [RegistroEventoController::class, 'index'])->name('eventos.index');
    Route::get('visitas', [VisitaPaginaController::class, 'index'])->name('visitas.index');
    Route::get('database', [DatabaseManagerController::class, 'index'])->name('database.index');
    Route::post('database/truncate', [DatabaseManagerController::class, 'truncate'])->name('database.truncate');
    Route::post('database/seed', [DatabaseManagerController::class, 'seed'])->name('database.seed');
    Route::post('database/reset', [DatabaseManagerController::class, 'reset'])->name('database.reset');
});

// ===========================================
// Rutas para Veterinarios (rol_id = 2)
// ===========================================
Route::middleware(['auth', 'verified'])->prefix('veterinario')->name('veterinario.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Veterinario\DashboardController::class, 'index'])->name('dashboard');
    Route::get('consultas', [\App\Http\Controllers\Veterinario\ConsultaController::class, 'index'])->name('consultas');
    Route::get('consultas/{consulta}', [\App\Http\Controllers\Veterinario\ConsultaController::class, 'show'])->name('consultas.show');
    Route::get('mascotas', [\App\Http\Controllers\Veterinario\MascotaController::class, 'index'])->name('mascotas');
});

// ===========================================
// Rutas para Clientes (rol_id = 3)
// ===========================================
Route::middleware(['auth', 'verified'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Cliente\DashboardController::class, 'index'])->name('dashboard');
    Route::get('mascotas', [\App\Http\Controllers\Cliente\MascotaController::class, 'index'])->name('mascotas');
    Route::get('consultas', [\App\Http\Controllers\Cliente\ConsultaController::class, 'index'])->name('consultas');
    Route::get('pagos', [\App\Http\Controllers\Cliente\PagoController::class, 'index'])->name('pagos');
});
