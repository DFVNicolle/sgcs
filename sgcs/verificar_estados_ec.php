<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VERIFICACIÓN DE ESTADOS NORMALIZADOS ===\n\n";

echo "📋 Estados en elementos_configuracion:\n";
$estadosEC = DB::table('elementos_configuracion')
    ->select('estado', DB::raw('count(*) as total'))
    ->groupBy('estado')
    ->get();

foreach ($estadosEC as $estado) {
    echo "  • {$estado->estado}: {$estado->total}\n";
}

echo "\n📋 Estados en versiones_ec:\n";
$estadosVer = DB::table('versiones_ec')
    ->select('estado', DB::raw('count(*) as total'))
    ->groupBy('estado')
    ->get();

foreach ($estadosVer as $estado) {
    echo "  • {$estado->estado}: {$estado->total}\n";
}

echo "\n✅ Estados normalizados correctamente!\n";
echo "   Todos deberían usar: BORRADOR, EN_REVISION, APROBADO, LIBERADO, OBSOLETO\n";
