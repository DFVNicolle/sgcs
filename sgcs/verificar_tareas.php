<?php
// Script simple para verificar tareas
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Configurar la conexión a la base de datos
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'database' => env('DB_DATABASE', 'sgcs'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🔍 Verificando tareas en la base de datos...\n\n";

// Verificar usuarios
echo "👥 USUARIOS:\n";
$usuarios = DB::table('usuarios')->select('id', 'nombre_completo', 'correo')->get();
foreach ($usuarios as $usuario) {
    echo "- {$usuario->nombre_completo} ({$usuario->correo}) - ID: {$usuario->id}\n";
}

echo "\n📋 TAREAS POR USUARIO:\n";
foreach ($usuarios as $usuario) {
    $tareas = DB::table('tareas_proyecto')
        ->where('responsable', $usuario->id)
        ->get();
    
    echo "\n{$usuario->nombre_completo}:\n";
    if ($tareas->count() > 0) {
        foreach ($tareas as $tarea) {
            echo "  - {$tarea->nombre} ({$tarea->estado})\n";
        }
    } else {
        echo "  Sin tareas asignadas\n";
    }
}

echo "\n🎯 CREAR TAREAS DE PRUEBA:\n";
$dev1 = DB::table('usuarios')->where('correo', 'dev1@demo.com')->first();
if ($dev1) {
    // Crear tareas de prueba
    $proyecto = DB::table('proyectos')->first();
    if ($proyecto) {
        $fase = DB::table('fases_metodologia')->first();
        
        // Crear algunas tareas de prueba
        $tareasCreadas = 0;
        
        $tareasPrueba = [
            ['nombre' => 'Tarea de Prueba 1', 'estado' => 'Pendiente'],
            ['nombre' => 'Tarea de Prueba 2', 'estado' => 'En Progreso'],
            ['nombre' => 'Tarea de Prueba 3', 'estado' => 'Completado'],
        ];
        
        foreach ($tareasPrueba as $tareaData) {
            $existe = DB::table('tareas_proyecto')
                ->where('nombre', $tareaData['nombre'])
                ->where('responsable', $dev1->id)
                ->exists();
                
            if (!$existe) {
                DB::table('tareas_proyecto')->insert([
                    'id_proyecto' => $proyecto->id,
                    'id_fase' => $fase->id_fase ?? 1,
                    'nombre' => $tareaData['nombre'],
                    'descripcion' => 'Tarea de prueba para verificar el tablero Kanban',
                    'responsable' => $dev1->id,
                    'estado' => $tareaData['estado'],
                    'prioridad' => 5,
                    'fecha_inicio' => date('Y-m-d'),
                    'fecha_fin' => date('Y-m-d', strtotime('+7 days')),
                    'creado_por' => $proyecto->creado_por ?? $dev1->id,
                ]);
                $tareasCreadas++;
            }
        }
        
        if ($tareasCreadas > 0) {
            echo "✅ Se crearon {$tareasCreadas} tareas de prueba para {$dev1->nombre_completo}\n";
        } else {
            echo "ℹ️ Las tareas de prueba ya existen\n";
        }
    }
}

echo "\n✅ Verificación completada. Ahora recarga la página web.\n";