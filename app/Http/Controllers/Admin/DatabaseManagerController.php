<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{DB, Artisan};
use Inertia\Inertia;

class DatabaseManagerController extends Controller
{
    public function index()
    {
        $stats = [
            'roles' => DB::table('rol')->count(),
            'usuarios' => DB::table('usuario')->count(),
            'categorias' => DB::table('categoria')->count(),
            'servicios' => DB::table('servicio')->count(),
            'productos' => DB::table('producto')->count(),
            'mascotas' => DB::table('mascota')->count(),
            'consultas' => DB::table('consulta')->count(),
            'consulta_servicios' => DB::table('consulta_servicio')->count(),
            'consulta_productos' => DB::table('consulta_producto')->count(),
            'pagos' => DB::table('pago')->count(),
            'pago_cuotas' => DB::table('pago_cuota')->count(),
            'menus' => DB::table('menu')->count(),
            'visitas' => DB::table('visita_pagina')->count(),
            'eventos' => DB::table('registro_evento')->count(),
        ];

        $testUsers = [
            'admin' => [
                'email' => 'admin@veterinaria.com',
                'password' => 'password',
                'exists' => DB::table('usuario')->where('email', 'admin@veterinaria.com')->exists()
            ],
            'veterinario' => [
                'email' => 'veterinario@veterinaria.com',
                'password' => 'password',
                'exists' => DB::table('usuario')->where('email', 'veterinario@veterinaria.com')->exists()
            ],
            'cliente' => [
                'email' => 'cliente@veterinaria.com',
                'password' => 'password',
                'exists' => DB::table('usuario')->where('email', 'cliente@veterinaria.com')->exists()
            ]
        ];

        return Inertia::render('Admin/DatabaseManager/Index', compact('stats', 'testUsers'));
    }

    public function truncate()
    {
        try {
            DB::beginTransaction();

            $tables = [
                'pago_cuota',           // depende de pago
                'consulta_producto',     // depende de consulta, producto
                'consulta_servicio',     // depende de consulta, servicio
                'pago',                  // depende de consulta
                'consulta',              // depende de mascota
                'mascota',               // depende de usuario
                'visita_pagina',         // independiente
                'registro_evento'        // independiente
            ];

            foreach ($tables as $table) {
                DB::statement("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE");
            }

            // Limpiar usuarios excepto los 3 de prueba
            DB::table('usuario')
                ->whereNotIn('email', [
                    'admin@veterinaria.com',
                    'veterinario@veterinaria.com',
                    'cliente@veterinaria.com'
                ])
                ->delete();

            DB::commit();
            return back()->with('success', 'Datos variables limpiados. Configuración base y usuarios de prueba preservados.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al limpiar BD: ' . $e->getMessage());
        }
    }

    public function seed()
    {
        try {
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);

            return back()->with('success', 'Datos de demostración insertados exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al poblar BD: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            DB::beginTransaction();

            $tables = [
                'pago_cuota',
                'consulta_producto',
                'consulta_servicio',
                'pago',
                'consulta',
                'mascota',
                'usuario',
                'servicio',
                'producto',
                'categoria',
                'rol',
                'menu',
                'visita_pagina',
                'registro_evento'
            ];

            foreach ($tables as $table) {
                DB::statement("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE");
            }

            DB::commit();

            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);

            return back()->with('success', 'Base de datos reseteada y poblada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al resetear BD: ' . $e->getMessage());
        }
    }
}
