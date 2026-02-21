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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64)->unique();
            $table->string('nombre_completo', 100);
            $table->string('email', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->text('direccion')->nullable();
            $table->string('plan')->nullable();
           /* $table->decimal('velocidad_subida', 8, 2)->nullable();
            $table->decimal('velocidad_bajada', 8, 2)->nullable();
            $table->string('tipo_conexion')->nullable();
            $table->ipAddress('ip_asignada')->nullable();
            $table->macAddress('mac_address')->nullable();
            $table->date('fecha_instalacion')->nullable();
            $table->date('fecha_corte')->nullable();  */
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            //hace que la DB lo marque como eliminado pero esta ahí
            $table->softDeletes();

            // Índices para mejor rendimiento de busqueda en la consulta
            $table->index('username');
            $table->index('estado');
            //$table->index('fecha_corte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
