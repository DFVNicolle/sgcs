<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Rol;

echo "=== ROLES POR METODOLOGÍA ===\n\n";

$roles = Rol::with('metodologia')->orderBy('metodologia_id')->get();

foreach ($roles as $rol) {
    $metodologia = $rol->metodologia ? $rol->metodologia->nombre : 'Genérico (todas las metodologías)';
    echo "• {$rol->nombre} -> {$metodologia}\n";
}

echo "\n✅ Total de roles: " . $roles->count() . "\n";
