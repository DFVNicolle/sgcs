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
        $this->call(RolesSeeder::class);
        $this->call(UsuarioSeeder::class);
        $this->call(ProyectoSeeder::class);
        $this->call(ElementoConfiguracionSeeder::class);
        $this->call(RelacionECSeeder::class);
        $this->call(UsuariosRolesSeeder::class);
        $this->call(EquipoSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UsuarioSeeder::class);
        $this->call(ProyectoSeeder::class);
        $this->call(ElementoConfiguracionSeeder::class);
        $this->call(RelacionECSeeder::class);
        $this->call(UsuariosRolesSeeder::class);
        // Crear roles primero
        $this->call(RolesSeeder::class);
        $this->call(UsuarioSeeder::class);
        $this->call(ProyectoSeeder::class);
        $this->call(ElementoConfiguracionSeeder::class);
        $this->call(RelacionECSeeder::class);

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
            $this->call([
                UsuarioSeeder::class,
                // Agrega otros seeders aquí si es necesario
            ]);
    }
}
