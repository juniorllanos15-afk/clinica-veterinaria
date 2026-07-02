<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. CATEGORÍA
        Schema::create('categoria', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        // 2. SERVICIO
        Schema::create('servicio', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_servicio', 20)->unique();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->integer('duracion_estimada')->nullable();
            $table->decimal('precio', 12, 2);
            $table->string('estado', 20)->default('activo');
            $table->timestamps();
        });

        // 3. PRODUCTO
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_producto', 20)->unique();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('precio', 12, 2);
            $table->foreignId('categoria_id')->constrained('categoria');
            $table->string('estado', 20)->default('activo');
            $table->timestamps();
        });

        // 4. MASCOTA
        Schema::create('mascota', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('especie', 50)->nullable();
            $table->string('raza', 50)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('sexo', 20)->nullable();
            $table->string('color', 50)->nullable();
            $table->decimal('peso', 6, 2)->nullable();
            $table->string('estado', 20)->default('activo');
            $table->foreignId('dueno_id')->constrained('usuario');
            $table->timestamps();
        });

        // 5. CONSULTA
        Schema::create('consulta', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_consulta')->default(now());
            $table->text('motivo_consulta')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado', 20)->default('activo');
            $table->foreignId('mascota_id')->constrained('mascota');
            $table->foreignId('veterinario_id')->constrained('usuario');
            $table->timestamps();
        });

        // 6. CONSULTA_SERVICIO
        Schema::create('consulta_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->constrained('consulta');
            $table->foreignId('servicio_id')->constrained('servicio');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index('consulta_id');
            $table->index('servicio_id');
        });

        // 7. CONSULTA_PRODUCTO
        Schema::create('consulta_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->constrained('consulta');
            $table->foreignId('producto_id')->constrained('producto');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index('consulta_id');
            $table->index('producto_id');
        });

        // 8. PAGO
        Schema::create('pago', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consulta_id')->unique()->constrained('consulta');
            $table->string('tipo_pago', 20);
            $table->integer('cantidad_cuotas');
            $table->decimal('total', 12, 2);
            $table->date('fecha_pago')->default(now());
            $table->string('estado', 20)->default('pendiente');
            $table->timestamps();
        });

        // 9. PAGO_CUOTA
        Schema::create('pago_cuota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_id')->constrained('pago');
            $table->integer('numero_cuota');
            $table->decimal('monto', 12, 2);
            $table->date('fecha_vencimiento');
            $table->date('fecha_pago')->nullable();
            $table->string('estado', 20)->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pago_cuota');
        Schema::dropIfExists('pago');
        Schema::dropIfExists('consulta_producto');
        Schema::dropIfExists('consulta_servicio');
        Schema::dropIfExists('consulta');
        Schema::dropIfExists('mascota');
        Schema::dropIfExists('producto');
        Schema::dropIfExists('servicio');
        Schema::dropIfExists('categoria');
        Schema::enableForeignKeyConstraints();
    }
};
