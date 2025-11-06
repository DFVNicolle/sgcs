<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Proyecto;
use App\Models\ComiteCambio;
use Illuminate\Support\Facades\Auth;

echo "=== VERIFICACIÓN CCB ===\n\n";

// Obtener el proyecto "Sistema ERP Empresarial"
$proyecto = Proyecto::where('nombre', 'Sistema ERP Empresarial')->first();

if (!$proyecto) {
    echo "Proyecto no encontrado\n";
    exit;
}

echo "Proyecto: {$proyecto->nombre} (ID: {$proyecto->id})\n";

// Verificar si existe CCB
$ccb = ComiteCambio::where('proyecto_id', $proyecto->id)->first();
echo "CCB existe: " . ($ccb ? 'SÍ' : 'NO') . "\n";

if ($ccb) {
    echo "CCB ID: {$ccb->id}\n";
    echo "CCB Nombre: {$ccb->nombre}\n";
    echo "CCB Quorum: {$ccb->quorum}\n";

    // Verificar miembros
    $miembros = $ccb->miembros()->get();
    echo "Total miembros CCB: {$miembros->count()}\n";

    foreach ($miembros as $miembro) {
        echo "  - {$miembro->nombre_completo} (ID: {$miembro->id})\n";
    }

    // Verificar si el usuario actual es miembro
    $usuarioActual = Auth::user();
    if ($usuarioActual) {
        $esMiembro = $ccb->esMiembro($usuarioActual->id);
        echo "\nUsuario actual: {$usuarioActual->nombre_completo} (ID: {$usuarioActual->id})\n";
        echo "Es miembro del CCB: " . ($esMiembro ? 'SÍ' : 'NO') . "\n";

        // Si no es miembro, agregarlo
        if (!$esMiembro) {
            echo "Agregando usuario al CCB...\n";
            $ccb->miembros()->attach($usuarioActual->id, ['rol_en_ccb' => 'Miembro']);
            $ccb->calcularQuorum();
            echo "Usuario agregado al CCB. Nuevo quorum: {$ccb->quorum}\n";
        }
    } else {
        echo "No hay usuario autenticado\n";
    }
}
