<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP TABLE IF EXISTS pago_cuota CASCADE');
        DB::statement('DROP TABLE IF EXISTS pago CASCADE');
        DB::statement('DROP TABLE IF EXISTS consulta_producto CASCADE');
        DB::statement('DROP TABLE IF EXISTS consulta_servicio CASCADE');
        DB::statement('DROP TABLE IF EXISTS consulta CASCADE');
        DB::statement('DROP TABLE IF EXISTS mascota CASCADE');
        DB::statement('DROP TABLE IF EXISTS producto CASCADE');
        DB::statement('DROP TABLE IF EXISTS servicio CASCADE');
        DB::statement('DROP TABLE IF EXISTS categoria CASCADE');

        DB::statement('DROP TABLE IF EXISTS pago CASCADE');
        DB::statement('DROP TABLE IF EXISTS metodo_pago CASCADE');
        DB::statement('DROP TABLE IF EXISTS inscripcion CASCADE');
        DB::statement('DROP TABLE IF EXISTS plan_pago CASCADE');
        DB::statement('DROP TABLE IF EXISTS curso_horario CASCADE');
        DB::statement('DROP TABLE IF EXISTS cursos_horarios CASCADE');
        DB::statement('DROP TABLE IF EXISTS curso_edicion CASCADE');
        DB::statement('DROP TABLE IF EXISTS curso CASCADE');
        DB::statement('DROP TABLE IF EXISTS tipo_curso CASCADE');
        DB::statement('DROP TABLE IF EXISTS tipos_curso CASCADE');
        DB::statement('DROP TABLE IF EXISTS vehiculo CASCADE');
        DB::statement('DROP TABLE IF EXISTS tipo_vehiculo CASCADE');
        DB::statement('DROP TABLE IF EXISTS tipos_vehiculo CASCADE');
    }

    public function down(): void
    {
        // No revertimos
    }
};
