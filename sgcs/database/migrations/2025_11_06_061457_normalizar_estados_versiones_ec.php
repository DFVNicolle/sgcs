<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Normalizar estados de versiones_ec para que coincidan con elementos_configuracion
     */
    public function up(): void
    {
        // Cambiar estados antiguos a los nuevos normalizados
        DB::table('versiones_ec')
            ->where('estado', 'REVISION')
            ->update(['estado' => 'EN_REVISION']);

        DB::table('versiones_ec')
            ->where('estado', 'DEPRECADO')
            ->update(['estado' => 'OBSOLETO']);

        // Modificar la columna para usar los estados normalizados
        Schema::table('versiones_ec', function (Blueprint $table) {
            $table->enum('estado', ['BORRADOR', 'EN_REVISION', 'APROBADO', 'LIBERADO', 'OBSOLETO'])
                ->default('BORRADOR')
                ->change();
        });

        // Normalizar también elementos_configuracion (eliminar PENDIENTE, usar BORRADOR como default)
        DB::table('elementos_configuracion')
            ->where('estado', 'PENDIENTE')
            ->update(['estado' => 'BORRADOR']);

        Schema::table('elementos_configuracion', function (Blueprint $table) {
            $table->enum('estado', ['BORRADOR', 'EN_REVISION', 'APROBADO', 'LIBERADO', 'OBSOLETO'])
                ->default('BORRADOR')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a estados originales
        Schema::table('versiones_ec', function (Blueprint $table) {
            $table->enum('estado', ['PENDIENTE','BORRADOR','REVISION','APROBADO','LIBERADO','DEPRECADO'])
                ->default('PENDIENTE')
                ->change();
        });

        Schema::table('elementos_configuracion', function (Blueprint $table) {
            $table->enum('estado', ['PENDIENTE','BORRADOR', 'EN_REVISION', 'APROBADO', 'LIBERADO', 'OBSOLETO'])
                ->default('PENDIENTE')
                ->change();
        });
    }
};
