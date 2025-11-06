<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Proyecto;
use App\Models\Rol;

echo "=== VERIFICACIÓN DE FILTRADO DE ROLES POR PROYECTO ===\n\n";

$proyectos = Proyecto::with('metodologia')->get();

foreach ($proyectos as $proyecto) {
    echo "📁 Proyecto: {$proyecto->nombre}\n";
    echo "   Metodología: {$proyecto->metodologia->nombre}\n";

    // Obtener roles filtrados según la lógica del controlador
    $rolesFiltrados = Rol::where(function($query) use ($proyecto) {
        $query->where('metodologia_id', $proyecto->id_metodologia)
              ->orWhereNull('metodologia_id');
    })->orderBy('nombre')->get();

    echo "   Roles disponibles para este proyecto:\n";
    foreach ($rolesFiltrados as $rol) {
        echo "      • {$rol->nombre}\n";
    }
    echo "\n";
}
