<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menu')->truncate();

        $menus = [
            // Admin / Propietario (rol_id = 1)
            ['nombre' => 'Dashboard',       'ruta' => '/admin/dashboard',          'orden' => 1, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Usuarios',        'ruta' => '/admin/usuarios',           'orden' => 2, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Roles',           'ruta' => '/admin/roles',              'orden' => 3, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Menús',           'ruta' => '/admin/menus',              'orden' => 4, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Categorías',      'ruta' => '/admin/categorias',         'orden' => 5, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Servicios',       'ruta' => '/admin/servicios',          'orden' => 6, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Productos',       'ruta' => '/admin/productos',          'orden' => 7, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Mascotas',        'ruta' => '/admin/mascotas',           'orden' => 8, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Consultas',       'ruta' => '/admin/consultas',          'orden' => 9, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Pagos',           'ruta' => '/admin/pagos',              'orden' => 10, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Eventos Sistema', 'ruta' => '/admin/eventos',            'orden' => 11, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Visitas Páginas', 'ruta' => '/admin/visitas',            'orden' => 12, 'rol_id' => 1, 'activo' => true],
            ['nombre' => 'Admin BD',        'ruta' => '/admin/database',           'orden' => 13, 'rol_id' => 1, 'activo' => true],

            // Veterinario (rol_id = 2)
            ['nombre' => 'Dashboard',       'ruta' => '/veterinario/dashboard',    'orden' => 1, 'rol_id' => 2, 'activo' => true],
            ['nombre' => 'Mis Consultas',   'ruta' => '/veterinario/consultas',    'orden' => 2, 'rol_id' => 2, 'activo' => true],
            ['nombre' => 'Mascotas',        'ruta' => '/veterinario/mascotas',     'orden' => 3, 'rol_id' => 2, 'activo' => true],
            ['nombre' => 'Mi Horario',      'ruta' => '/veterinario/horarios',     'orden' => 4, 'rol_id' => 2, 'activo' => true],

            // Cliente (rol_id = 3)
            ['nombre' => 'Dashboard',       'ruta' => '/cliente/dashboard',        'orden' => 1, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Mis Mascotas',    'ruta' => '/cliente/mascotas',         'orden' => 2, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Mis Consultas',   'ruta' => '/cliente/consultas',        'orden' => 3, 'rol_id' => 3, 'activo' => true],
            ['nombre' => 'Mis Pagos',       'ruta' => '/cliente/pagos',            'orden' => 4, 'rol_id' => 3, 'activo' => true],
        ];

        foreach ($menus as $menu) {
            DB::table('menu')->insert(array_merge($menu, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('Menús creados exitosamente: ' . count($menus));
    }
}
