<?php

namespace Database\Seeders;

use App\Models\Usuario;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles primero
        $this->call(RolesSeeder::class);

        // Crear usuarios de prueba
        Usuario::factory()->create([
            'nombre_completo' => 'Administrador del Sistema',
            'correo' => 'admin@sgcs.com',
            'activo' => true,
        ]);

        Usuario::factory()->create([
            'nombre_completo' => 'Juan Pérez García',
            'correo' => 'juan.perez@sgcs.com',
            'activo' => true,
        ]);

        Usuario::factory()->create([
            'nombre_completo' => 'María López Rodríguez',
            'correo' => 'maria.lopez@sgcs.com',
            'activo' => true,
        ]);

        Usuario::factory()->create([
            'nombre_completo' => 'Carlos Martínez Sánchez',
            'correo' => 'carlos.martinez@sgcs.com',
            'activo' => true,
        ]);

        Usuario::factory()->create([
            'nombre_completo' => 'Ana Torres Vega',
            'correo' => 'ana.torres@sgcs.com',
            'activo' => true,
        ]);

        Usuario::factory()->create([
            'nombre_completo' => 'Pedro Ramírez Castro',
            'correo' => 'pedro.ramirez@sgcs.com',
            'activo' => true,
        ]);

        Usuario::factory()->create([
            'nombre_completo' => 'Laura Fernández Ortiz',
            'correo' => 'laura.fernandez@sgcs.com',
            'activo' => true,
        ]);

        Usuario::factory()->create([
            'nombre_completo' => 'Roberto Silva Mendoza',
            'correo' => 'roberto.silva@sgcs.com',
            'activo' => true,
        ]);

        Usuario::factory()->create([
            'nombre_completo' => 'Sofía González Díaz',
            'correo' => 'sofia.gonzalez@sgcs.com',
            'activo' => true,
        ]);

        Usuario::factory()->create([
            'nombre_completo' => 'Diego Vargas Moreno',
            'correo' => 'diego.vargas@sgcs.com',
            'activo' => true,
        ]);

        $this->command->info('✅ 10 usuarios de prueba creados exitosamente!');
    }
}
