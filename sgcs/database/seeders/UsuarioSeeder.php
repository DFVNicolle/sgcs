<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // USUARIOS PROFESIONALES PARA DEMOSTRACIÓN COMPLETA DEL SGCS
        
        // Gestor de Configuración
        Usuario::firstOrCreate(['correo' => 'scm.manager@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Carlos Méndez',
            'contrasena_hash' => Hash::make('scm123'),
            'correo_verificado_en' => now(),
        ]);

        // Administrador CCB
        Usuario::firstOrCreate(['correo' => 'ccb.admin@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Ana Patricia López',
            'contrasena_hash' => Hash::make('ccb123'),
            'correo_verificado_en' => now(),
        ]);

        // === EQUIPO PROYECTO SCRUM ===
        
        // Product Owner - Scrum
        Usuario::firstOrCreate(['correo' => 'po.scrum@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'María González',
            'contrasena_hash' => Hash::make('po123'),
            'correo_verificado_en' => now(),
        ]);

        // Scrum Master
        Usuario::firstOrCreate(['correo' => 'sm.scrum@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Roberto Castillo',
            'contrasena_hash' => Hash::make('sm123'),
            'correo_verificado_en' => now(),
        ]);

        // Desarrolladores Scrum
        Usuario::firstOrCreate(['correo' => 'dev.senior.scrum@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Luis Hernández',
            'contrasena_hash' => Hash::make('dev123'),
            'correo_verificado_en' => now(),
        ]);

        Usuario::firstOrCreate(['correo' => 'dev1.scrum@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Carmen Ruiz',
            'contrasena_hash' => Hash::make('dev123'),
            'correo_verificado_en' => now(),
        ]);

        Usuario::firstOrCreate(['correo' => 'dev2.scrum@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Diego Morales',
            'contrasena_hash' => Hash::make('dev123'),
            'correo_verificado_en' => now(),
        ]);

        // QA Scrum
        Usuario::firstOrCreate(['correo' => 'qa.scrum@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Patricia Vega',
            'contrasena_hash' => Hash::make('qa123'),
            'correo_verificado_en' => now(),
        ]);

        Usuario::firstOrCreate(['correo' => 'tester.scrum@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Jorge Ramírez',
            'contrasena_hash' => Hash::make('test123'),
            'correo_verificado_en' => now(),
        ]);

        // === EQUIPO PROYECTO CASCADA ===
        
        // Líder de Proyecto - Cascada
        Usuario::firstOrCreate(['correo' => 'pm.cascada@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Fernando Sánchez',
            'contrasena_hash' => Hash::make('pm123'),
            'correo_verificado_en' => now(),
        ]);

        // Arquitecto de Software
        Usuario::firstOrCreate(['correo' => 'architect.cascada@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Dr. Alberto Jiménez',
            'contrasena_hash' => Hash::make('arch123'),
            'correo_verificado_en' => now(),
        ]);

        // Analista
        Usuario::firstOrCreate(['correo' => 'analyst.cascada@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Laura Martínez',
            'contrasena_hash' => Hash::make('analyst123'),
            'correo_verificado_en' => now(),
        ]);

        // Desarrolladores Cascada
        Usuario::firstOrCreate(['correo' => 'dev.senior.cascada@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Andrés Ortiz',
            'contrasena_hash' => Hash::make('dev123'),
            'correo_verificado_en' => now(),
        ]);

        Usuario::firstOrCreate(['correo' => 'dev1.cascada@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Sofía Gutiérrez',
            'contrasena_hash' => Hash::make('dev123'),
            'correo_verificado_en' => now(),
        ]);

        Usuario::firstOrCreate(['correo' => 'dev2.cascada@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Miguel Ángel Torres',
            'contrasena_hash' => Hash::make('dev123'),
            'correo_verificado_en' => now(),
        ]);

        // QA y Testing Cascada
        Usuario::firstOrCreate(['correo' => 'qa.cascada@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Gabriela Rojas',
            'contrasena_hash' => Hash::make('qa123'),
            'correo_verificado_en' => now(),
        ]);

        Usuario::firstOrCreate(['correo' => 'tester.cascada@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Ricardo Pérez',
            'contrasena_hash' => Hash::make('test123'),
            'correo_verificado_en' => now(),
        ]);

        // Release Manager
        Usuario::firstOrCreate(['correo' => 'release.manager@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Elena Vargas',
            'contrasena_hash' => Hash::make('release123'),
            'correo_verificado_en' => now(),
        ]);

        // Auditor de Configuración
        Usuario::firstOrCreate(['correo' => 'auditor@sgcs.com'], [
            'id' => Str::uuid()->toString(),
            'nombre_completo' => 'Lic. Javier Campos',
            'contrasena_hash' => Hash::make('audit123'),
            'correo_verificado_en' => now(),
        ]);
    }
}
