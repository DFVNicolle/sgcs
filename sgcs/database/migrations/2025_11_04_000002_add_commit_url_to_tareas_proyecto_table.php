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
            // Campo para almacenar la URL del commit cuando el desarrollador completa la tarea
            $table->text('commit_url')->nullable()->after('notas');
            
            // Campo para almacenar el ID del commit registrado (opcional)
            $table->char('commit_id', 36)->nullable()->after('commit_url');
            
            // Foreign key hacia commits_repositorio (si existe el commit registrado)
            $table->foreign('commit_id')
                ->references('id')
                ->on('commits_repositorio')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tareas_proyecto', function (Blueprint $table) {
            $table->dropForeign(['commit_id']);
            $table->dropColumn(['commit_url', 'commit_id']);
        });
    }
};
