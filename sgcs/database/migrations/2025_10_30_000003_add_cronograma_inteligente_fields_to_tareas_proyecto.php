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
        Schema::table('tareas_proyecto', function (Blueprint $table) {
            // Campos para cronograma inteligente
            $table->integer('duracion_minima')->nullable()->comment('Duración mínima posible en días');
            $table->boolean('es_ruta_critica')->default(false)->comment('Indica si la tarea está en la ruta crítica');
            $table->integer('holgura_dias')->default(0)->comment('Días de holgura (slack) - 0 para ruta crítica');
            $table->date('fecha_inicio_original')->nullable()->comment('Fecha original antes de ajustes automáticos');
            $table->date('fecha_fin_original')->nullable()->comment('Fecha original antes de ajustes automáticos');
            $table->boolean('puede_paralelizarse')->default(false)->comment('Indica si la tarea puede ejecutarse en paralelo con otras');
            $table->json('dependencias')->nullable()->comment('IDs de tareas de las que depende esta tarea');
            $table->decimal('progreso_real', 5, 2)->default(0)->comment('Porcentaje de progreso real (0-100)');

            // Índices para optimizar consultas
            $table->index('es_ruta_critica');
            $table->index(['fecha_inicio', 'fecha_fin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas_proyecto', function (Blueprint $table) {
            $table->dropIndex(['es_ruta_critica']);
            $table->dropIndex(['tareas_proyecto_fecha_inicio_fecha_fin_index']);

            $table->dropColumn([
                'duracion_minima',
                'es_ruta_critica',
                'holgura_dias',
                'fecha_inicio_original',
                'fecha_fin_original',
                'puede_paralelizarse',
                'dependencias',
                'progreso_real',
            ]);
        });
    }
};
