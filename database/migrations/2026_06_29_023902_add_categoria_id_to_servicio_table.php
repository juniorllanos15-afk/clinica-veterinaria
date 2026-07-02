<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servicio', function (Blueprint $table) {
            $table->foreignId('categoria_id')->nullable()->after('descripcion')->constrained('categoria');
        });
    }

    public function down(): void
    {
        Schema::table('servicio', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
    }
};
