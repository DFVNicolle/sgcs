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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('usuarios_roles', function (Blueprint $table) {
            $table->id(); // PK auto-increment para evitar problemas con NULL
            $table->char('usuario_id', 36);
            $table->unsignedBigInteger('rol_id');
            $table->char('proyecto_id', 36)->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('restrict');
            // Índice único para evitar duplicados
            $table->unique(['usuario_id', 'rol_id', 'proyecto_id'], 'unique_usuario_rol_proyecto');
            // FK a proyectos se añadirá después cuando exista la tabla
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_roles');
        Schema::dropIfExists('roles');
    }
};
