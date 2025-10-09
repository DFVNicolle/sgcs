<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre' => 'administrador',
                'descripcion' => 'Administrador del sistema con acceso completo'
            ],
            [
                'nombre' => 'lider',
                'descripcion' => 'Líder de proyecto con permisos de gestión'
            ],
            [
                'nombre' => 'desarrollador',
                'descripcion' => 'Desarrollador con acceso a elementos de configuración'
            ],
            [
                'nombre' => 'tester',
                'descripcion' => 'Tester con acceso a casos de prueba y elementos de QA'
            ],
            [
                'nombre' => 'documentador',
                'descripcion' => 'Documentador con acceso a documentos y artefactos'
            ],
            [
                'nombre' => 'auditor',
                'descripcion' => 'Auditor con acceso de solo lectura para revisiones'
            ],
            [
                'nombre' => 'cliente',
                'descripcion' => 'Cliente con acceso limitado para seguimiento'
            ]
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->insert($rol);
        }
    }
}