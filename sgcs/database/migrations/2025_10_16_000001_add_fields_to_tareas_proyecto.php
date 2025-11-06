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
            // Información básica
            $table->string('nombre', 255)->after('id_tarea');
            $table->text('descripcion')->nullable()->after('nombre');

            // Prioridad (1-5, universal para todas las metodologías)
            $table->integer('prioridad')->default(3)->after('descripcion');

            // Campos ESPECÍFICOS por metodología (NULLABLES)
            // Para SCRUM:
            $table->integer('story_points')->nullable()->after('prioridad')->comment('Solo para Scrum');
            $table->string('sprint', 50)->nullable()->after('story_points')->comment('Solo para Scrum');

            // Para CASCADA:
            $table->decimal('horas_estimadas', 8, 2)->nullable()->after('sprint')->comment('Más usado en Cascada');
            $table->string('entregable', 255)->nullable()->after('horas_estimadas')->comment('Específico de Cascada');

            // Campos COMUNES (ambas metodologías)
            $table->json('criterios_aceptacion')->nullable()->after('entregable');
            $table->text('notas')->nullable()->after('criterios_aceptacion');

            // Usuario que creó la tarea
            $table->char('creado_por', 36)->nullable()->after('notas');
            $table->foreign('creado_por')->references('id')->on('usuarios')->onDelete('set null');

            // Timestamps
            $table->timestamp('creado_en')->useCurrent()->after('creado_por');
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate()->after('creado_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas_proyecto', function (Blueprint $table) {
            $table->dropForeign(['creado_por']);
            $table->dropColumn([
                'nombre',
                'descripcion',
                'prioridad',
                'story_points',
                'sprint',
                'horas_estimadas',
                'entregable',
                'criterios_aceptacion',
                'notas',
                'creado_por',
                'creado_en',
                'actualizado_en'
            ]);
        });
    }
};
