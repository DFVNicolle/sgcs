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
        // Registro de incidencias o no conformidades detectadas en auditorías
        Schema::create('incidencias', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('proyecto_id', 36)->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['ERROR', 'OMISION', 'CAMBIO_NO_AUTORIZADO', 'OTRO'])->default('OTRO');
            $table->enum('severidad', ['BAJA', 'MEDIA', 'ALTA', 'CRITICA'])->default('MEDIA');
            $table->enum('estado', ['ABIERTA', 'EN_PROCESO', 'RESUELTA', 'CERRADA'])->default('ABIERTA');
            $table->char('reportado_por', 36)->nullable();
            $table->char('asignado_a', 36)->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('cerrado_en')->nullable();
            
            $table->foreign('proyecto_id')->references('id')->on('proyectos');
            $table->foreign('reportado_por')->references('id')->on('usuarios');
            $table->foreign('asignado_a')->references('id')->on('usuarios');
        });

        // Tabla de métricas de desempeño y control
        Schema::create('metricas_proyecto', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('proyecto_id', 36)->nullable();
            $table->string('tipo', 100)->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            
            $table->foreign('proyecto_id')->references('id')->on('proyectos');
        });

        // Revisiones técnicas y de calidad
        Schema::create('revisiones', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('ec_id', 36)->nullable();
            $table->char('version_ec_id', 36)->nullable();
            $table->char('revisor_id', 36)->nullable();
            $table->enum('resultado', ['APROBADO', 'OBSERVADO', 'RECHAZADO'])->default('OBSERVADO');
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha_revision')->useCurrent();
            
            $table->foreign('ec_id')->references('id')->on('elementos_configuracion');
            $table->foreign('version_ec_id')->references('id')->on('versiones_ec');
            $table->foreign('revisor_id')->references('id')->on('usuarios');
        });

        // Respaldos automáticos o manuales del sistema
        Schema::create('respaldos', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->text('ruta')->nullable();
            $table->enum('tipo', ['AUTOMATICO', 'MANUAL'])->default('MANUAL');
            $table->decimal('tamano_mb', 10, 2)->nullable();
            $table->char('realizado_por', 36)->nullable();
            $table->timestamp('fecha')->useCurrent();
            
            $table->foreign('realizado_por')->references('id')->on('usuarios');
        });

        // Bitácora de implementación
        Schema::create('bitacora_implementacion', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('proyecto_id', 36)->nullable();
            $table->char('liberacion_id', 36)->nullable();
            $table->text('descripcion')->nullable();
            $table->char('realizado_por', 36)->nullable();
            $table->timestamp('fecha')->useCurrent();
            
            $table->foreign('proyecto_id')->references('id')->on('proyectos');
            $table->foreign('liberacion_id')->references('id')->on('liberaciones');
            $table->foreign('realizado_por')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_implementacion');
        Schema::dropIfExists('respaldos');
        Schema::dropIfExists('revisiones');
        Schema::dropIfExists('metricas_proyecto');
        Schema::dropIfExists('incidencias');
    }
};