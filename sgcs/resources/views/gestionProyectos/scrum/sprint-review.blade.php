@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Navegación Scrum -->
    <x-scrum.navigation :proyecto="$proyecto" active="review" />

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Sprint Review</h1>
                    <p class="text-gray-600 mt-1">Revisión de incremento y demo del {{ $sprintActual }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    {{ $sprintActual }}
                </span>
            </div>
        </div>
    </div>

    <!-- Métricas del Sprint -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Tareas</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalTareas }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completadas</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $tareasCompletadas }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Story Points</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalStoryPoints }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tasa Completitud</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalTareas > 0 ? round(($tareasCompletadas / $totalTareas) * 100) : 0 }}%</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Guía de Sprint Review -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Guía para Sprint Review
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <h4 class="font-semibold text-blue-800 mb-2">Objetivos:</h4>
                <ul class="space-y-2 text-blue-900">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Inspeccionar el incremento del producto</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Demo de funcionalidades completadas</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Recopilar feedback de stakeholders</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Actualizar el Product Backlog según sea necesario</span>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-blue-800 mb-2">Participantes:</h4>
                <ul class="space-y-2 text-blue-900">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Scrum Team (Dev Team, Scrum Master, Product Owner)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Stakeholders invitados</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600 mt-1">•</span>
                        <span>Usuarios clave o representantes del cliente</span>
                    </li>
                </ul>
                <h4 class="font-semibold text-blue-800 mt-4 mb-2">Duración:</h4>
                <p class="text-blue-900">Máximo 4 horas para sprints de 1 mes (ajustar proporcionalmente)</p>
            </div>
        </div>
    </div>

    <!-- Tareas del Sprint -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Tareas del {{ $sprintActual }}</h2>

        @if($tareasDelSprint->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="mt-4 text-gray-500">No hay tareas asignadas a este sprint</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarea</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Story Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progreso</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tareasDelSprint as $tarea)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $tarea->nombre }}</div>
                                @if($tarea->descripcion)
                                    <div class="text-sm text-gray-500 mt-1">{{ Str::limit($tarea->descripcion, 60) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $estadoClasses = [
                                        'Pendiente' => 'bg-gray-100 text-gray-800',
                                        'En Progreso' => 'bg-blue-100 text-blue-800',
                                        'En Revisión' => 'bg-yellow-100 text-yellow-800',
                                        'Completado' => 'bg-green-100 text-green-800',
                                        'Bloqueado' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoClasses[$tarea->estado] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $tarea->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $prioridadClasses = [
                                        'Baja' => 'bg-green-100 text-green-800',
                                        'Media' => 'bg-yellow-100 text-yellow-800',
                                        'Alta' => 'bg-orange-100 text-orange-800',
                                        'Crítica' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $prioridadClasses[$tarea->prioridad] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $tarea->prioridad }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $tarea->story_points ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $tarea->responsableUsuario->nombre_completo ?? 'Sin asignar' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $tarea->progreso ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $tarea->progreso ?? 0 }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Resumen por Estado -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach(['Pendiente', 'En Progreso', 'En Revisión', 'Completado', 'Bloqueado'] as $estado)
                    @php
                        $count = $tareasDelSprint->where('estado', $estado)->count();
                    @endphp
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900">{{ $count }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $estado }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Acciones -->
    <div class="flex justify-between items-center">
        <a href="{{ route('scrum.dashboard', $proyecto) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al Dashboard
        </a>

        <a href="{{ route('scrum.sprint-retrospective', $proyecto) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
            Ir a Sprint Retrospective
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>
    </div>
</div>
@endsection
