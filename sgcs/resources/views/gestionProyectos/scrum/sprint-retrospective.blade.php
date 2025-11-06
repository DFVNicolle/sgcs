@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Navegación Scrum -->
    <x-scrum.navigation :proyecto="$proyecto" active="retrospective" />

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Sprint Retrospective</h1>
                    <p class="text-gray-600 mt-1">Reflexión y mejora continua del {{ $sprintActual }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    {{ $sprintActual }}
                </span>
            </div>
        </div>
    </div>

    <!-- Guía de Sprint Retrospective -->
    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-purple-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Guía para Sprint Retrospective
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <h4 class="font-semibold text-purple-800 mb-2">Objetivos:</h4>
                <ul class="space-y-2 text-purple-900">
                    <li class="flex items-start gap-2">
                        <span class="text-purple-600 mt-1">•</span>
                        <span>Inspeccionar cómo fue el último Sprint en relación a personas, relaciones, procesos y herramientas</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-purple-600 mt-1">•</span>
                        <span>Identificar y ordenar lo que salió bien y posibles mejoras</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-purple-600 mt-1">•</span>
                        <span>Crear un plan de mejora para el siguiente Sprint</span>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-purple-800 mb-2">Participantes:</h4>
                <ul class="space-y-2 text-purple-900">
                    <li class="flex items-start gap-2">
                        <span class="text-purple-600 mt-1">•</span>
                        <span>Scrum Team completo (Development Team, Scrum Master, Product Owner)</span>
                    </li>
                </ul>
                <h4 class="font-semibold text-purple-800 mt-4 mb-2">Duración:</h4>
                <p class="text-purple-900">Máximo 3 horas para sprints de 1 mes (ajustar proporcionalmente)</p>
            </div>
        </div>
    </div>

    <!-- Formato Estrella de Mar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Formato: Estrella de Mar</h2>
        <p class="text-gray-600 mb-6">Este formato ayuda al equipo a reflexionar sobre prácticas actuales y futuras.</p>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Comenzar a hacer -->
            <div class="border-2 border-green-200 rounded-lg p-4 bg-green-50">
                <h3 class="font-semibold text-green-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Comenzar a hacer
                </h3>
                <p class="text-sm text-green-700 mb-4">¿Qué nuevas prácticas deberíamos adoptar?</p>
                <div class="bg-white rounded p-3 min-h-[150px] border border-green-200">
                    <textarea class="w-full h-full border-0 focus:ring-0 text-sm text-gray-700 resize-none" placeholder="Ej: Realizar code reviews diarias, Documentar decisiones técnicas..."></textarea>
                </div>
            </div>

            <!-- Hacer más -->
            <div class="border-2 border-blue-200 rounded-lg p-4 bg-blue-50">
                <h3 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    Hacer más
                </h3>
                <p class="text-sm text-blue-700 mb-4">¿Qué está funcionando bien y deberíamos intensificar?</p>
                <div class="bg-white rounded p-3 min-h-[150px] border border-blue-200">
                    <textarea class="w-full h-full border-0 focus:ring-0 text-sm text-gray-700 resize-none" placeholder="Ej: Pair programming, Testing automatizado, Retrospectivas..."></textarea>
                </div>
            </div>

            <!-- Seguir haciendo -->
            <div class="border-2 border-yellow-200 rounded-lg p-4 bg-yellow-50">
                <h3 class="font-semibold text-yellow-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Seguir haciendo
                </h3>
                <p class="text-sm text-yellow-700 mb-4">¿Qué está funcionando bien y debemos mantener?</p>
                <div class="bg-white rounded p-3 min-h-[150px] border border-yellow-200">
                    <textarea class="w-full h-full border-0 focus:ring-0 text-sm text-gray-700 resize-none" placeholder="Ej: Daily standups, Sprint planning detallado, Comunicación abierta..."></textarea>
                </div>
            </div>

            <!-- Hacer menos -->
            <div class="border-2 border-orange-200 rounded-lg p-4 bg-orange-50">
                <h3 class="font-semibold text-orange-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                    Hacer menos
                </h3>
                <p class="text-sm text-orange-700 mb-4">¿Qué deberíamos reducir o minimizar?</p>
                <div class="bg-white rounded p-3 min-h-[150px] border border-orange-200">
                    <textarea class="w-full h-full border-0 focus:ring-0 text-sm text-gray-700 resize-none" placeholder="Ej: Reuniones largas, Interrupciones durante el desarrollo, Multitasking..."></textarea>
                </div>
            </div>

            <!-- Dejar de hacer -->
            <div class="border-2 border-red-200 rounded-lg p-4 bg-red-50">
                <h3 class="font-semibold text-red-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Dejar de hacer
                </h3>
                <p class="text-sm text-red-700 mb-4">¿Qué prácticas no están funcionando y debemos eliminar?</p>
                <div class="bg-white rounded p-3 min-h-[150px] border border-red-200">
                    <textarea class="w-full h-full border-0 focus:ring-0 text-sm text-gray-700 resize-none" placeholder="Ej: Commits sin revisión, Saltarse la Definition of Done, Estimaciones apresuradas..."></textarea>
                </div>
            </div>

            <!-- Acciones -->
            <div class="border-2 border-purple-200 rounded-lg p-4 bg-purple-50">
                <h3 class="font-semibold text-purple-800 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Plan de Acción
                </h3>
                <p class="text-sm text-purple-700 mb-4">Acciones concretas para el próximo Sprint</p>
                <div class="bg-white rounded p-3 min-h-[150px] border border-purple-200">
                    <textarea class="w-full h-full border-0 focus:ring-0 text-sm text-gray-700 resize-none" placeholder="Ej: 1. Implementar sesiones de pair programming 2 veces/semana
2. Crear template para documentación técnica
3. Reducir daily standup a 10 minutos máximo..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Otras Técnicas de Retrospective -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Otras Técnicas de Retrospective</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-2">Mad, Sad, Glad</h3>
                <p class="text-sm text-gray-600">Clasifica eventos del sprint en tres categorías emocionales para entender el impacto en el equipo.</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-2">4Ls</h3>
                <p class="text-sm text-gray-600">Liked (Gustó), Learned (Aprendimos), Lacked (Faltó), Longed for (Deseábamos) - Reflexión estructurada.</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-2">Sailboat</h3>
                <p class="text-sm text-gray-600">Metáfora del barco: viento (impulsa), anclas (frena), islas (objetivos), rocas (riesgos).</p>
            </div>
        </div>
    </div>

    <!-- Acciones -->
    <div class="flex justify-between items-center">
        <a href="{{ route('scrum.sprint-review', $proyecto) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a Sprint Review
        </a>

        <a href="{{ route('scrum.dashboard', $proyecto) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
            Volver al Dashboard Scrum
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
    </div>
</div>
@endsection
