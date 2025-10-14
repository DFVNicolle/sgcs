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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('codigo', 50)->unique()->nullable();
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->enum('metodologia', ['agil', 'cascada', 'hibrida'])->default('agil');
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });

        // Añadir FK de usuarios_roles a proyectos
        Schema::table('usuarios_roles', function (Blueprint $table) {
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('set null');
        });

        Schema::create('equipos', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('proyecto_id', 36);
            $table->string('nombre', 255);
            $table->char('lider_id', 36)->nullable();
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('cascade');
            $table->foreign('lider_id')->references('id')->on('usuarios')->onDelete('set null');
        });

        Schema::create('miembros_equipo', function (Blueprint $table) {
            $table->char('equipo_id', 36);
            $table->char('usuario_id', 36);
            $table->unsignedBigInteger('rol_id');
            $table->primary(['equipo_id', 'usuario_id']);
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('rol_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('miembros_equipo');
        Schema::dropIfExists('equipos');
        
        Schema::table('usuarios_roles', function (Blueprint $table) {
            $table->dropForeign(['proyecto_id']);
        });
        
        Schema::dropIfExists('proyectos');
    }
};
