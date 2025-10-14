<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('google2fa_secret', 255)->nullable()->after('correo_verificado_en');
        });
    }

    public function down() {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('google2fa_secret');
        });
    }
};
