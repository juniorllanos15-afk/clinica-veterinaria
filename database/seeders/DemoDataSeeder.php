<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Additional demo data beyond DatabaseSeeder

        // 1. MÁS CLIENTES
        if (!DB::table('usuario')->where('email', 'sofia.martinez@mail.com')->exists()) {
            DB::table('usuario')->insert([
                ['nombre' => 'Sofía', 'apellido' => 'Martínez', 'email' => 'sofia.martinez@mail.com', 'telefono' => '70000007', 'contrasena' => Hash::make('123123'), 'rol_id' => 3, 'estado_usuario' => 'activo', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Diego', 'apellido' => 'Ramírez', 'email' => 'diego.ramirez@mail.com', 'telefono' => '70000008', 'contrasena' => Hash::make('123123'), 'rol_id' => 3, 'estado_usuario' => 'activo', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. MÁS MASCOTAS
        if (DB::table('mascota')->count() < 10) {
            DB::table('mascota')->insert([
                ['nombre' => 'Max',    'especie' => 'Perro', 'raza' => 'Golden Retriever', 'fecha_nacimiento' => '2021-09-10', 'sexo' => 'Macho',  'color' => 'Dorado',  'peso' => 28.00, 'dueno_id' => 7, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Nina',   'especie' => 'Perro', 'raza' => 'Beagle',           'fecha_nacimiento' => '2022-04-22', 'sexo' => 'Hembra', 'color' => 'Tricolor', 'peso' => 12.50, 'dueno_id' => 7, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Simba',  'especie' => 'Gato',  'raza' => 'Maine Coon',       'fecha_nacimiento' => '2020-12-01', 'sexo' => 'Macho',  'color' => 'Naranja', 'peso' => 6.20,  'dueno_id' => 8, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Canela', 'especie' => 'Perro', 'raza' => 'Cocker Spaniel',  'fecha_nacimiento' => '2023-02-14', 'sexo' => 'Hembra', 'color' => 'Café',    'peso' => 10.00, 'dueno_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 3. MÁS CONSULTAS
        if (DB::table('consulta')->count() < 10) {
            DB::table('consulta')->insert([
                ['fecha_consulta' => Carbon::now()->subDays(5)->toDateString(), 'motivo_consulta' => 'Dolor al caminar y cojera',                    'diagnostico' => 'Displasia de cadera leve',                             'tratamiento' => 'Antiinflamatorio, condroprotectores, fisioterapia',                                             'observaciones' => 'Evitar saltos, control en 30 días',                           'mascota_id' => 6, 'veterinario_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['fecha_consulta' => Carbon::now()->subDays(3)->toDateString(), 'motivo_consulta' => 'Estornudos frecuentes y secreción ocular',       'diagnostico' => 'Conjuntivitis bacteriana + rinitis',                     'tratamiento' => 'Colirio antibiótico, antihistamínico oral',                                                    'observaciones' => 'Aislar de otros animales 5 días',                            'mascota_id' => 7, 'veterinario_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['fecha_consulta' => Carbon::now()->subDays(1)->toDateString(), 'motivo_consulta' => 'Revisión post-operatoria y retiro de puntos',      'diagnostico' => 'Herida quirúrgica cicatrizando correctamente',           'tratamiento' => 'Retiro de suturas, curación local',                                                            'observaciones' => 'Continuar con antibiótico 3 días más',                        'mascota_id' => 8, 'veterinario_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 4. CONSULTA_PRODUCTO adicionales
        if (DB::table('consulta_producto')->count() < 15) {
            DB::table('consulta_producto')->insert([
                ['consulta_id' => 6, 'producto_id' => 5,  'cantidad' => 1, 'precio' => 40.00, 'subtotal' => 40.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 6, 'producto_id' => 10, 'cantidad' => 1, 'precio' => 30.00, 'subtotal' => 30.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 7, 'producto_id' => 2,  'cantidad' => 1, 'precio' => 60.00, 'subtotal' => 60.00, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 5. CONSULTA_SERVICIO adicionales
        if (DB::table('consulta_servicio')->count() < 15) {
            DB::table('consulta_servicio')->insert([
                ['consulta_id' => 6, 'servicio_id' => 1, 'cantidad' => 1, 'precio' => 80.00,  'subtotal' => 80.00,  'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 6, 'servicio_id' => 7, 'cantidad' => 1, 'precio' => 120.00, 'subtotal' => 120.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 7, 'servicio_id' => 1, 'cantidad' => 1, 'precio' => 80.00,  'subtotal' => 80.00,  'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 7, 'servicio_id' => 7, 'cantidad' => 1, 'precio' => 120.00, 'subtotal' => 120.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 8, 'servicio_id' => 5, 'cantidad' => 1, 'precio' => 500.00, 'subtotal' => 500.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 8, 'servicio_id' => 1, 'cantidad' => 1, 'precio' => 80.00,  'subtotal' => 80.00,  'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 6. PAGOS adicionales
        if (DB::table('pago')->count() < 5) {
            DB::table('pago')->insert([
                ['consulta_id' => 6, 'tipo_pago' => 'credito', 'cantidad_cuotas' => 3, 'total' => 270.00, 'fecha_pago' => Carbon::now()->subDays(5)->toDateString(), 'estado' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 7, 'tipo_pago' => 'contado', 'cantidad_cuotas' => 1, 'total' => 260.00, 'fecha_pago' => Carbon::now()->subDays(3)->toDateString(), 'estado' => 'Pagado',   'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 7. PAGO_CUOTAS adicionales
        if (DB::table('pago_cuota')->count() < 10) {
            DB::table('pago_cuota')->insert([
                ['pago_id' => 3, 'numero_cuota' => 1, 'monto' => 90.00, 'fecha_vencimiento' => Carbon::now()->addMonth()->toDateString(),  'fecha_pago' => null, 'estado' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
                ['pago_id' => 3, 'numero_cuota' => 2, 'monto' => 90.00, 'fecha_vencimiento' => Carbon::now()->addMonths(2)->toDateString(), 'fecha_pago' => null, 'estado' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
                ['pago_id' => 3, 'numero_cuota' => 3, 'monto' => 90.00, 'fecha_vencimiento' => Carbon::now()->addMonths(3)->toDateString(), 'fecha_pago' => null, 'estado' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
                ['pago_id' => 4, 'numero_cuota' => 1, 'monto' => 260.00, 'fecha_vencimiento' => Carbon::now()->subDays(3)->toDateString(),  'fecha_pago' => Carbon::now()->subDays(3)->toDateString(), 'estado' => 'Pagado', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        $this->command->info('   Demo data adicional cargada correctamente');
    }
}
