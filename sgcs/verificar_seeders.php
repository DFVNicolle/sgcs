<?php

/**
 * Script de verificación de seeders del SGCS
 * Ejecuta: php verificar_seeders.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n";
echo "🔍 VERIFICACIÓN DE SEEDERS DEL SGCS\n";
echo "====================================\n\n";

// Verificar metodologías
$metodologias = DB::table('metodologias')->count();
echo "📚 Metodologías: $metodologias (esperado: 2)\n";
if ($metodologias == 2) {
    $metods = DB::table('metodologias')->pluck('nombre')->toArray();
    echo "   ✅ " . implode(', ', $metods) . "\n";
}

// Verificar fases
$fasesScrum = DB::table('fases_metodologia')
    ->join('metodologias', 'fases_metodologia.id_metodologia', '=', 'metodologias.id_metodologia')
    ->where('metodologias.nombre', 'Scrum')
    ->count();
$fasesCascada = DB::table('fases_metodologia')
    ->join('metodologias', 'fases_metodologia.id_metodologia', '=', 'metodologias.id_metodologia')
    ->where('metodologias.nombre', 'Cascada')
    ->count();
echo "   • Fases Scrum: $fasesScrum (esperado: 5)\n";
echo "   • Fases Cascada: $fasesCascada (esperado: 7)\n";

echo "\n";

// Verificar roles
$roles = DB::table('roles')->count();
echo "👔 Roles: $roles (esperado: 12)\n";
if ($roles >= 10) {
    echo "   ✅ Roles profesionales creados\n";
}

echo "\n";

// Verificar usuarios
$usuarios = DB::table('usuarios')->count();
echo "👥 Usuarios: $usuarios (esperado: 19)\n";
if ($usuarios >= 15) {
    echo "   ✅ Equipo completo creado\n";
    
    // Mostrar algunos usuarios clave
    $usuariosClave = DB::table('usuarios')
        ->whereIn('correo', [
            'scm.manager@sgcs.com',
            'po.scrum@sgcs.com',
            'pm.cascada@sgcs.com'
        ])
        ->get(['nombre_completo', 'correo']);
    
    foreach ($usuariosClave as $user) {
        echo "   • {$user->nombre_completo} - {$user->correo}\n";
    }
}

echo "\n";

// Verificar proyectos
$proyectos = DB::table('proyectos')->count();
echo "📦 Proyectos: $proyectos (esperado: 5)\n";
if ($proyectos >= 2) {
    $proyectosPrincipales = DB::table('proyectos')
        ->whereIn('codigo', ['ECOM-2024', 'ERP-2024'])
        ->get(['codigo', 'nombre']);
    
    foreach ($proyectosPrincipales as $proyecto) {
        echo "   ✅ {$proyecto->codigo} - {$proyecto->nombre}\n";
    }
}

echo "\n";

// Verificar equipos
$equipos = DB::table('equipos')->count();
echo "👨‍💼 Equipos: $equipos (esperado: 2)\n";

$miembrosEquipo = DB::table('miembros_equipo')->count();
echo "   • Miembros en equipos: $miembrosEquipo\n";

echo "\n";

// Verificar elementos de configuración
$ecs = DB::table('elementos_configuracion')->count();
echo "📄 Elementos de Configuración: $ecs (esperado: ~27)\n";

// ECs por proyecto
$ecsScrum = DB::table('elementos_configuracion')
    ->join('proyectos', 'elementos_configuracion.proyecto_id', '=', 'proyectos.id')
    ->where('proyectos.codigo', 'ECOM-2024')
    ->count();
$ecsCascada = DB::table('elementos_configuracion')
    ->join('proyectos', 'elementos_configuracion.proyecto_id', '=', 'proyectos.id')
    ->where('proyectos.codigo', 'ERP-2024')
    ->count();

echo "   • E-Commerce (Scrum): $ecsScrum\n";
echo "   • ERP (Cascada): $ecsCascada\n";

// ECs por tipo
$ecsPorTipo = DB::table('elementos_configuracion')
    ->select('tipo', DB::raw('count(*) as total'))
    ->groupBy('tipo')
    ->get();

foreach ($ecsPorTipo as $tipo) {
    echo "   • Tipo {$tipo->tipo}: {$tipo->total}\n";
}

echo "\n";

// Verificar relaciones
$relaciones = DB::table('relaciones_ec')->count();
echo "🔗 Relaciones entre ECs: $relaciones\n";

if ($relaciones > 0) {
    $relacionesPorTipo = DB::table('relaciones_ec')
        ->select('tipo_relacion', DB::raw('count(*) as total'))
        ->groupBy('tipo_relacion')
        ->get();
    
    foreach ($relacionesPorTipo as $rel) {
        echo "   • {$rel->tipo_relacion}: {$rel->total}\n";
    }
}

echo "\n";

// Verificar versiones
$versiones = DB::table('versiones_ec')->count();
echo "📌 Versiones de ECs: $versiones\n";

echo "\n";

// Verificar tareas
$tareas = DB::table('tareas_proyecto')->count();
echo "✅ Tareas de Proyecto: $tareas (esperado: ~25)\n";

// Tareas por proyecto
$tareasScrum = DB::table('tareas_proyecto')
    ->where('id_proyecto', DB::table('proyectos')->where('codigo', 'ECOM-2024')->value('id'))
    ->count();
$tareasCascada = DB::table('tareas_proyecto')
    ->where('id_proyecto', DB::table('proyectos')->where('codigo', 'ERP-2024')->value('id'))
    ->count();

echo "   • E-Commerce (Scrum): $tareasScrum\n";
echo "   • ERP (Cascada): $tareasCascada\n";

echo "\n";

// Verificar CCBs
$ccbs = DB::table('comite_cambios')->count();
echo "🔒 Comités de Control de Cambios: $ccbs (esperado: 2)\n";

$miembrosCCB = DB::table('miembros_ccb')->count();
echo "   • Miembros en CCBs: $miembrosCCB\n";

echo "\n";

// Verificar plantillas EC
$plantillas = DB::table('plantillas_ec')->count();
echo "📋 Plantillas de EC: $plantillas\n";

echo "\n";
echo "====================================\n";

// Resumen final
$errores = [];
if ($metodologias != 2) $errores[] = "Metodologías incorrectas";
if ($usuarios < 15) $errores[] = "Faltan usuarios";
if ($proyectos < 2) $errores[] = "Faltan proyectos principales";
if ($ecs < 20) $errores[] = "Faltan elementos de configuración";
if ($equipos < 2) $errores[] = "Faltan equipos";
if ($ccbs < 2) $errores[] = "Faltan CCBs";

if (count($errores) == 0) {
    echo "✅ ¡VERIFICACIÓN EXITOSA!\n";
    echo "   Todos los seeders se ejecutaron correctamente.\n";
    echo "   El SGCS está listo para la demostración.\n";
} else {
    echo "⚠️  SE ENCONTRARON PROBLEMAS:\n";
    foreach ($errores as $error) {
        echo "   • $error\n";
    }
    echo "\n   Ejecuta: php artisan db:seed\n";
}

echo "\n";
