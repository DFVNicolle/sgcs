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
        Schema::create('auditorias', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('tipo_entidad', 100)->nullable();
            $table->char('entidad_id', 36)->nullable();
            $table->string('accion', 50)->nullable();
            $table->char('usuario_id', 36)->nullable();
            $table->json('detalles')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('set null');
        });

        Schema::create('accesos', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('usuario_id', 36)->nullable();
            $table->string('ip', 45)->nullable(); // Cambiado de 100 a 45
            $table->text('accion')->nullable();
            $table->text('recurso')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('set null');
        });

        Schema::create('notificaciones', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('usuario_id', 36)->nullable();
            $table->string('tipo', 100)->nullable();
            $table->json('datos')->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamp('creado_en')->useCurrent(); // Cambiado de creada_en
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('set null');
        });

        Schema::create('commits_repositorio', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->text('url_repositorio')->nullable();
            $table->text('hash_commit')->nullable();
            $table->text('autor')->nullable();
            $table->text('mensaje')->nullable();
            $table->timestamp('fecha_commit')->nullable();
            $table->char('ec_id', 36)->nullable();
            $table->foreign('ec_id')->references('id')->on('elementos_configuracion');
        });

        // Agregar FK de commit_id en versiones_ec (después de crear commits_repositorio)
        Schema::table('versiones_ec', function (Blueprint $table) {
            $table->foreign('commit_id')->references('id')->on('commits_repositorio')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar FK antes de borrar commits_repositorio
        Schema::table('versiones_ec', function (Blueprint $table) {
            $table->dropForeign(['commit_id']);
        });

        Schema::dropIfExists('commits_repositorio');
        Schema::dropIfExists('notificaciones');
        Schema::dropIfExists('accesos');
        Schema::dropIfExists('auditorias');
    }
};
