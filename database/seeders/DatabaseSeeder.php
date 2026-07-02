<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ROLES (3 roles)
        if (DB::table('rol')->count() === 0) {
            DB::table('rol')->insert([
                ['nombre' => 'administrador', 'descripcion' => 'Propietario / Dueño de la clínica', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'profesor', 'descripcion' => 'Veterinario', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'alumno', 'descripcion' => 'Cliente / Dueño de mascota', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. USUARIOS
        if (!DB::table('usuario')->where('email', 'juniorllanos15@gmail.com')->exists()) {
            DB::table('usuario')->insert([
                'nombre' => 'Junior',
                'apellido' => 'Llanos',
                'email' => 'juniorllanos15@gmail.com',
                'telefono' => '70000001',
                'contrasena' => Hash::make('123123'),
                'rol_id' => 1,
                'estado_usuario' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Veterinarios
        $vets = [
            ['nombre' => 'María', 'apellido' => 'Rodríguez', 'email' => 'maria.vet@clinica.bo', 'telefono' => '70000002'],
            ['nombre' => 'Carlos', 'apellido' => 'Mendoza', 'email' => 'carlos.vet@clinica.bo', 'telefono' => '70000003'],
        ];
        foreach ($vets as $vet) {
            if (!DB::table('usuario')->where('email', $vet['email'])->exists()) {
                DB::table('usuario')->insert(array_merge($vet, [
                    'contrasena' => Hash::make('123123'),
                    'rol_id' => 2,
                    'estado_usuario' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }

        // Clientes
        $clients = [
            ['nombre' => 'Ana', 'apellido' => 'Pérez', 'email' => 'ana.perez@mail.com', 'telefono' => '70000004'],
            ['nombre' => 'Pedro', 'apellido' => 'García', 'email' => 'pedro.garcia@mail.com', 'telefono' => '70000005'],
            ['nombre' => 'Laura', 'apellido' => 'Fernández', 'email' => 'laura.fernandez@mail.com', 'telefono' => '70000006'],
        ];
        foreach ($clients as $client) {
            if (!DB::table('usuario')->where('email', $client['email'])->exists()) {
                DB::table('usuario')->insert(array_merge($client, [
                    'contrasena' => Hash::make('123123'),
                    'rol_id' => 3,
                    'estado_usuario' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }

        // 3. CATEGORÍAS DE PRODUCTOS
        if (DB::table('categoria')->count() === 0) {
            DB::table('categoria')->insert([
                ['nombre' => 'Medicamentos', 'descripcion' => 'Fármacos y medicinas veterinarias', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Alimentos', 'descripcion' => 'Alimento balanceado y suplementos', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Accesorios', 'descripcion' => 'Collares, correas, juguetes y afines', 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Higiene', 'descripcion' => 'Productos de limpieza y cuidado', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 4. PRODUCTOS
        if (DB::table('producto')->count() === 0) {
            DB::table('producto')->insert([
                ['codigo_producto' => 'VAC-001',  'nombre' => 'Vacuna Antirrábica',       'descripcion' => 'Vacuna contra la rabia canina y felina',              'stock' => 50, 'precio' => 45.00, 'categoria_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'VAC-002',  'nombre' => 'Vacuna Quíntuple',         'descripcion' => 'Vacuna múltiple para perros',                         'stock' => 40, 'precio' => 60.00, 'categoria_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'DESP-001', 'nombre' => 'Desparasitante Oral',      'descripcion' => 'Comprimidos para desparasitación interna',           'stock' => 80, 'precio' => 25.00, 'categoria_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'DESP-002', 'nombre' => 'Desparasitante Externo',   'descripcion' => 'Pipeta antipulgas y garrapatas',                      'stock' => 60, 'precio' => 35.00, 'categoria_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ANTI-001', 'nombre' => 'Antiinflamatorio',         'descripcion' => 'Antiinflamatorio no esteroide para mascotas',         'stock' => 30, 'precio' => 40.00, 'categoria_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ANTI-002', 'nombre' => 'Antibiótico',              'descripcion' => 'Antibiótico de amplio espectro',                      'stock' => 25, 'precio' => 55.00, 'categoria_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ALIM-001', 'nombre' => 'Alimento Premium Perro',   'descripcion' => 'Alimento balanceado para perros adultos 15kg',        'stock' => 20, 'precio' => 120.00, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ALIM-002', 'nombre' => 'Alimento Premium Gato',    'descripcion' => 'Alimento balanceado para gatos adultos 10kg',        'stock' => 20, 'precio' => 110.00, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ALIM-003', 'nombre' => 'Alimento Cachorro',        'descripcion' => 'Alimento para cachorros 7kg',                        'stock' => 15, 'precio' => 85.00, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ALIM-004', 'nombre' => 'Suplemento Vitamínico',    'descripcion' => 'Complemento vitamínico para mascotas',               'stock' => 40, 'precio' => 30.00, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ALIM-005', 'nombre' => 'Snack Dental',             'descripcion' => 'Galletas dentales para perros',                      'stock' => 100, 'precio' => 15.00, 'categoria_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ACCE-001', 'nombre' => 'Collar Ajustable',         'descripcion' => 'Collar de nylon ajustable para perros',              'stock' => 30, 'precio' => 25.00, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ACCE-002', 'nombre' => 'Correa Retráctil',         'descripcion' => 'Correa retráctil de 5 metros',                       'stock' => 20, 'precio' => 40.00, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ACCE-003', 'nombre' => 'Juguete Mordedor',         'descripcion' => 'Juguete de goma resistente para perros',             'stock' => 50, 'precio' => 18.00, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ACCE-004', 'nombre' => 'Cama Ortopédica',          'descripcion' => 'Cama acolchonada para mascotas medianas',            'stock' => 10, 'precio' => 150.00, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'ACCE-005', 'nombre' => 'Cepillo de Cerdas',        'descripcion' => 'Cepillo para cuidado del pelaje',                    'stock' => 35, 'precio' => 22.00, 'categoria_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'HIGI-001', 'nombre' => 'Shampoo Neutro',           'descripcion' => 'Shampoo neutro para perros y gatos',                 'stock' => 40, 'precio' => 28.00, 'categoria_id' => 4, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'HIGI-002', 'nombre' => 'Shampoo Antipulgas',       'descripcion' => 'Shampoo con tratamiento antipulgas',                'stock' => 30, 'precio' => 35.00, 'categoria_id' => 4, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'HIGI-003', 'nombre' => 'Cepillo Dental',           'descripcion' => 'Kit de cepillo y pasta dental para mascotas',        'stock' => 25, 'precio' => 20.00, 'categoria_id' => 4, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_producto' => 'HIGI-004', 'nombre' => 'Toallitas Húmedas',        'descripcion' => 'Paquete de toallitas húmedas para mascotas',         'stock' => 60, 'precio' => 12.00, 'categoria_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 5. SERVICIOS
        if (DB::table('servicio')->count() === 0) {
            DB::table('servicio')->insert([
                ['codigo_servicio' => 'CON-GRAL', 'nombre' => 'Consulta General',     'descripcion' => 'Revisión médica general',             'duracion_estimada' => 30, 'precio' => 80.00, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_servicio' => 'CON-URG',  'nombre' => 'Consulta Urgencia',    'descripcion' => 'Atención veterinaria de emergencia',  'duracion_estimada' => 45, 'precio' => 150.00, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_servicio' => 'VACUN',    'nombre' => 'Vacunación',            'descripcion' => 'Aplicación de vacunas',              'duracion_estimada' => 15, 'precio' => 50.00, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_servicio' => 'DESPAR',   'nombre' => 'Desparasitación',       'descripcion' => 'Desparasitación interna y externa',   'duracion_estimada' => 15, 'precio' => 40.00, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_servicio' => 'CIRUG',    'nombre' => 'Cirugía',               'descripcion' => 'Procedimiento quirúrgico',           'duracion_estimada' => 120, 'precio' => 500.00, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_servicio' => 'PELIQ',    'nombre' => 'Peluquería',            'descripcion' => 'Corte de pelo y baño',               'duracion_estimada' => 60, 'precio' => 70.00, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_servicio' => 'LABOR',    'nombre' => 'Laboratorio',           'descripcion' => 'Análisis clínicos y pruebas',        'duracion_estimada' => 45, 'precio' => 120.00, 'created_at' => now(), 'updated_at' => now()],
                ['codigo_servicio' => 'RAYOS-X',  'nombre' => 'Radiografía',           'descripcion' => 'Estudio radiológico',                'duracion_estimada' => 30, 'precio' => 200.00, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 6. MASCOTAS
        if (DB::table('mascota')->count() === 0) {
            DB::table('mascota')->insert([
                ['nombre' => 'Tobby',  'especie' => 'Perro', 'raza' => 'Labrador',     'fecha_nacimiento' => '2020-03-15', 'sexo' => 'Macho',  'color' => 'Dorado',  'peso' => 25.50, 'dueno_id' => 4, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Luna',   'especie' => 'Perro', 'raza' => 'Pastor Alemán', 'fecha_nacimiento' => '2021-07-22', 'sexo' => 'Hembra', 'color' => 'Negro',   'peso' => 30.00, 'dueno_id' => 4, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Misi',   'especie' => 'Gato',  'raza' => 'Siamés',       'fecha_nacimiento' => '2022-01-10', 'sexo' => 'Hembra', 'color' => 'Café',    'peso' => 4.50,  'dueno_id' => 5, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Rocky',  'especie' => 'Perro', 'raza' => 'Bulldog',      'fecha_nacimiento' => '2019-11-05', 'sexo' => 'Macho',  'color' => 'Blanco',  'peso' => 22.00, 'dueno_id' => 5, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Pelusa', 'especie' => 'Gato',  'raza' => 'Persa',        'fecha_nacimiento' => '2023-05-18', 'sexo' => 'Hembra', 'color' => 'Gris',    'peso' => 3.80,  'dueno_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 7. CONSULTAS
        if (DB::table('consulta')->count() === 0) {
            DB::table('consulta')->insert([
                ['fecha_consulta' => '2026-06-15', 'motivo_consulta' => 'Control anual y vacunación',                   'diagnostico' => 'Saludable, peso ideal',                       'tratamiento' => 'Vacuna antirrábica aplicada, desparasitación oral recetada',            'observaciones' => 'Programar próximo control en 6 meses',     'mascota_id' => 1, 'veterinario_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['fecha_consulta' => '2026-06-16', 'motivo_consulta' => 'Herida en pata trasera',                       'diagnostico' => 'Corte profundo en almohadilla plantar',       'tratamiento' => 'Limpieza quirúrgica, sutura, antibiótico y antiinflamatorio recetados', 'observaciones' => 'Reposo absoluto 7 días, revisar apósito', 'mascota_id' => 2, 'veterinario_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['fecha_consulta' => '2026-06-17', 'motivo_consulta' => 'Revisión general y baño',                      'diagnostico' => 'Saludable, pelaje enmarañado con nudos',      'tratamiento' => 'Cepillado profundo, baño medicado, corte de uñas',                      'observaciones' => 'Cepillar diariamente para evitar nudos',   'mascota_id' => 3, 'veterinario_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['fecha_consulta' => '2026-06-18', 'motivo_consulta' => 'Vómitos recurrentes y decaimiento',            'diagnostico' => 'Intoxicación alimentaria leve',               'tratamiento' => 'Suero oral, protectores gástricos, dieta blanda 48h',                  'observaciones' => 'Suspender alimentación 12h, luego reintroducir gradual', 'mascota_id' => 4, 'veterinario_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['fecha_consulta' => '2026-06-18', 'motivo_consulta' => 'Control de peso y segunda dosis vacuna',       'diagnostico' => 'Sobrepeso leve (3.8kg, ideal 3.0-3.5kg)',     'tratamiento' => 'Dieta hipocalórica, porciones medidas, vacuna quintuple aplicada',      'observaciones' => 'Reducir porciones 20%, pesar en 30 días',  'mascota_id' => 5, 'veterinario_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 8. CONSULTA_PRODUCTO
        if (DB::table('consulta_producto')->count() === 0) {
            DB::table('consulta_producto')->insert([
                ['consulta_id' => 1, 'producto_id' => 1,  'cantidad' => 1, 'precio' => 45.00, 'subtotal' => 45.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 1, 'producto_id' => 3,  'cantidad' => 1, 'precio' => 25.00, 'subtotal' => 25.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 2, 'producto_id' => 6,  'cantidad' => 1, 'precio' => 55.00, 'subtotal' => 55.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 2, 'producto_id' => 5,  'cantidad' => 1, 'precio' => 40.00, 'subtotal' => 40.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 3, 'producto_id' => 17, 'cantidad' => 1, 'precio' => 28.00, 'subtotal' => 28.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 4, 'producto_id' => 5,  'cantidad' => 1, 'precio' => 40.00, 'subtotal' => 40.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 5, 'producto_id' => 10, 'cantidad' => 1, 'precio' => 30.00, 'subtotal' => 30.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 5, 'producto_id' => 11, 'cantidad' => 2, 'precio' => 15.00, 'subtotal' => 30.00, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 9. CONSULTA_SERVICIO
        if (DB::table('consulta_servicio')->count() === 0) {
            DB::table('consulta_servicio')->insert([
                ['consulta_id' => 1, 'servicio_id' => 3, 'cantidad' => 1, 'precio' => 50.00,  'subtotal' => 50.00,  'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 1, 'servicio_id' => 4, 'cantidad' => 1, 'precio' => 40.00,  'subtotal' => 40.00,  'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 2, 'servicio_id' => 2, 'cantidad' => 1, 'precio' => 150.00, 'subtotal' => 150.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 2, 'servicio_id' => 5, 'cantidad' => 1, 'precio' => 500.00, 'subtotal' => 500.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 3, 'servicio_id' => 1, 'cantidad' => 1, 'precio' => 80.00,  'subtotal' => 80.00,  'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 3, 'servicio_id' => 6, 'cantidad' => 1, 'precio' => 70.00,  'subtotal' => 70.00,  'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 4, 'servicio_id' => 2, 'cantidad' => 1, 'precio' => 150.00, 'subtotal' => 150.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 4, 'servicio_id' => 7, 'cantidad' => 1, 'precio' => 120.00, 'subtotal' => 120.00, 'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 5, 'servicio_id' => 1, 'cantidad' => 1, 'precio' => 80.00,  'subtotal' => 80.00,  'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 5, 'servicio_id' => 3, 'cantidad' => 1, 'precio' => 50.00,  'subtotal' => 50.00,  'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 10. PAGOS
        if (DB::table('pago')->count() === 0) {
            DB::table('pago')->insert([
                ['consulta_id' => 1, 'tipo_pago' => 'contado', 'cantidad_cuotas' => 1,  'total' => 160.00, 'fecha_pago' => '2026-06-15', 'estado' => 'Pagado',   'created_at' => now(), 'updated_at' => now()],
                ['consulta_id' => 2, 'tipo_pago' => 'credito', 'cantidad_cuotas' => 3,  'total' => 745.00, 'fecha_pago' => '2026-06-16', 'estado' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 11. PAGO_CUOTAS
        if (DB::table('pago_cuota')->count() === 0) {
            DB::table('pago_cuota')->insert([
                ['pago_id' => 1, 'numero_cuota' => 1, 'monto' => 160.00, 'fecha_vencimiento' => '2026-06-15', 'fecha_pago' => '2026-06-15', 'estado' => 'Pagado',   'created_at' => now(), 'updated_at' => now()],
                ['pago_id' => 2, 'numero_cuota' => 1, 'monto' => 248.33, 'fecha_vencimiento' => '2026-07-16', 'fecha_pago' => null,       'estado' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
                ['pago_id' => 2, 'numero_cuota' => 2, 'monto' => 248.33, 'fecha_vencimiento' => '2026-08-16', 'fecha_pago' => null,       'estado' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
                ['pago_id' => 2, 'numero_cuota' => 3, 'monto' => 248.34, 'fecha_vencimiento' => '2026-09-16', 'fecha_pago' => null,       'estado' => 'Pendiente', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 12. MENÚS
        $this->call(MenuSeeder::class);
    }
}
