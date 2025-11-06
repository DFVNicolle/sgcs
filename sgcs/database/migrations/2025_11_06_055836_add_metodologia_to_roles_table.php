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
        Schema::table('roles', function (Blueprint $table) {
            // Agregar campo metodologia_id (nullable para roles genéricos)
            $table->unsignedBigInteger('metodologia_id')->nullable()->after('descripcion');
            $table->foreign('metodologia_id')->references('id_metodologia')->on('metodologias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['metodologia_id']);
            $table->dropColumn('metodologia_id');
        });
    }
};
