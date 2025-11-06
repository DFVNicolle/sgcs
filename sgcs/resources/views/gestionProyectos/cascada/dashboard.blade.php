<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">
                    GESTIÓN CASCADA
                </h2>
                <p class="text-sm text-gray-600 mt-1 font-medium">
                    {{ $proyecto->nombre }} • Metodología Cascada (Waterfall)
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('proyectos.show', $proyecto) }}" class="btn btn-ghost btn-sm">
                    ← Volver al proyecto
                </a>
                <button onclick="modalNuevaTarea.showModal()" class="btn btn-sm bg-gray-600 text-white hover:bg-gray-700 rounded-lg font-semibold">
                    + Nueva Actividad
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Métricas del Proyecto -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">FASE ACTUAL</p>
                            <p class="text-xl font-bold text-gray-900">{{ $faseActual->nombre_fase ?? 'Sin definir' }}</p>
                        </div>
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <span class="text-gray-600 text-sm font-bold">{{ substr($faseActual->nombre_fase ?? 'N/A', 0, 1) }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">PROGRESO GENERAL</p>
                            @php
                                $totalFases = $fases->count();
                                $fasesCompletadas = collect($progresoPorFase)->where('fase_completada', true)->count();
                                $progresoGeneral = $totalFases > 0 ? round(($fasesCompletadas / $totalFases) * 100) : 0;
                            @endphp
                            <p class="text-2xl font-bold text-gray-900">{{ $progresoGeneral }}%</p>
                        </div>
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-green-600 text-sm font-bold">{{ $progresoGeneral }}%</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progresoGeneral }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">DURACIÓN TOTAL</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $duracionTotal }}</p>
                            <p class="text-xs text-gray-500 font-medium">días</p>
                        </div>
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-blue-600 text-sm font-bold">{{ $duracionTotal }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">HITOS</p>
                            <p class="text-2xl font-bold text-gray-900">{{ count($hitos) }}</p>
                        </div>
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <span class="text-yellow-600 text-sm font-bold">{{ count($hitos) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Proyecto -->
            @if($fechaInicioProyecto && $fechaFinProyecto)
            <div class="bg-white rounded-lg border border-gray-200 mb-6 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">CRONOLOGÍA DEL PROYECTO</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <span class="text-green-600 text-sm font-bold">IN</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">INICIO</p>
                        <p class="font-bold text-lg text-gray-900">{{ \Carbon\Carbon::parse($fechaInicioProyecto)->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <span class="text-blue-600 text-sm font-bold">HO</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">HOY</p>
                        <p class="font-bold text-lg text-gray-900">{{ now()->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <span class="text-red-600 text-sm font-bold">FI</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">FIN PLANIFICADO</p>
                        <p class="font-bold text-lg text-gray-900">{{ \Carbon\Carbon::parse($fechaFinProyecto)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Fases del Proyecto -->
            <div class="bg-white rounded-lg border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">PROGRESO POR FASES</h3>

                    <div class="space-y-4">
                        @foreach($fases as $index => $fase)
                            @php
                                $progreso = $progresoPorFase[$fase->id_fase];
                                $esFaseActual = $faseActual && $faseActual->id_fase === $fase->id_fase;
                                $faseCompletada = $progreso['fase_completada'];
                                $porcentaje = $progreso['porcentaje'];
                            @endphp

                            <div class="relative">
                                <!-- Línea conectora (excepto para la última fase) -->
                                @if($index < $fases->count() - 1)
                                    <div class="absolute left-6 top-12 w-0.5 h-8 {{ $faseCompletada ? 'bg-green-400' : 'bg-gray-300' }}"></div>
                                @endif

                                <div class="flex items-start gap-4 p-4 rounded-lg {{ $esFaseActual ? 'bg-blue-50 border border-blue-200' : ($faseCompletada ? 'bg-white border border-green-200' : 'bg-white border border-gray-200') }}">
                                    <!-- Icono de estado -->
                                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center {{ $faseCompletada ? 'bg-green-100 text-green-600' : ($esFaseActual ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600') }}">
                                        @if($faseCompletada)
                                            ✓
                                        @elseif($esFaseActual)
                                            {{ $index + 1 }}
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>

                                    <!-- Contenido de la fase -->
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $fase->nombre_fase }}</h4>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium {{ $faseCompletada ? 'text-green-700' : ($esFaseActual ? 'text-blue-700' : 'text-gray-600') }}">
                                                    {{ $porcentaje }}%
                                                </span>
                                                @if($esFaseActual)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Actual</span>
                                                @elseif($faseCompletada)
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Completada</span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="text-sm text-gray-600 mb-3">{{ $fase->descripcion }}</p>

                                        <!-- Barra de progreso -->
                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                            <div class="h-2 rounded-full {{ $faseCompletada ? 'bg-green-500' : ($esFaseActual ? 'bg-blue-500' : 'bg-gray-400') }}"
                                                 style="width: {{ $porcentaje }}%"></div>
                                        </div>

                                        <!-- Información adicional -->
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">
                                                {{ $progreso['completadas'] }}/{{ $progreso['total'] }} actividades
                                            </span>
                                            <a href="{{ route('cascada.ver-fase', [$proyecto, $fase]) }}"
                                               class="text-blue-600 hover:text-blue-800 font-medium">
                                                Ver detalles →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cronograma Maestro -->
            <div class="bg-white rounded-lg border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">CRONOGRAMA MAESTRO</h3>

                    @if($tareas->count() > 0)
                        <div class="space-y-6">
                            @foreach($fases as $fase)
                                @php
                                    $tareasDelaFase = $tareas->where('id_fase', $fase->id_fase);
                                @endphp

                                @if($tareasDelaFase->count() > 0)
                                    <!-- Fase -->
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                            <h4 class="font-semibold text-gray-900">{{ $fase->nombre_fase }}</h4>
                                            <p class="text-sm text-gray-600 font-medium">{{ $tareasDelaFase->count() }} actividades</p>
                                        </div>

                                        <div class="divide-y divide-gray-100">
                                            @foreach($tareasDelaFase as $tarea)
                                                <div class="px-4 py-3 hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-3">
                                                            @if($tarea->estado === 'Completado')
                                                                <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                </div>
                                                            @else
                                                                <div class="w-5 h-5 bg-gray-100 rounded-full"></div>
                                                            @endif
                                                            <div>
                                                                <p class="font-medium text-gray-900">{{ $tarea->nombre }}</p>
                                                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                                                    @if($tarea->responsableUsuario)
                                                                        <span>Responsable: {{ $tarea->responsableUsuario->nombre }}</span>
                                                                    @endif
                                                                    @if($tarea->fecha_inicio && $tarea->fecha_fin)
                                                                        <span>Del {{ \Carbon\Carbon::parse($tarea->fecha_inicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($tarea->fecha_fin)->format('d/m/Y') }}</span>
                                                                    @endif
                                                                    @if($tarea->horas_estimadas)
                                                                        <span>{{ $tarea->horas_estimadas }} horas</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($tarea->prioridad)
                                                            @php
                                                                $prioridadColor = $tarea->prioridad >= 8 ? 'bg-red-100 text-red-800' :
                                                                                 ($tarea->prioridad >= 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                                                            @endphp
                                                            <span class="px-2 py-1 {{ $prioridadColor }} text-xs font-medium rounded-full">
                                                                Prioridad {{ $tarea->prioridad }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <span class="text-gray-600 text-sm font-bold">CA</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay actividades programadas</h3>
                            <p class="text-gray-600 mb-4">Agrega actividades con fechas para visualizar el cronograma</p>
                            <button onclick="modalNuevaTarea.showModal()" class="btn bg-gray-600 text-white hover:bg-gray-700">
                                Agregar Actividades
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Diagrama de Gantt -->
            <div class="bg-white rounded-lg border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">DIAGRAMA DE GANTT</h3>

                    @if($tareas->count() > 0)
                        <!-- Información del proyecto -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-4">
                                    <span class="font-medium text-gray-700">Inicio del proyecto:</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($fechaInicioProyecto)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-medium text-gray-700">Fin planificado:</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($fechaFinProyecto)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-medium text-gray-700">Duración total:</span>
                                    <span class="text-gray-900">{{ $duracionTotal }} días</span>
                                </div>
                            </div>
                        </div>

                        <!-- Diagrama Gantt -->
                        <div class="overflow-x-auto">
                            <div class="min-w-full">
                                <!-- Encabezado con meses -->
                                <div class="mb-4 border-b border-gray-200 pb-2">
                                    <div class="grid grid-cols-12 gap-1 text-xs text-gray-600 font-medium">
                                        @for($i = 0; $i < 12; $i++)
                                            <div class="text-center py-2">
                                                {{ now()->addMonths($i)->format('M Y') }}
                                            </div>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Actividades del Gantt -->
                                <div class="space-y-3">
                                    @foreach($tareas as $tarea)
                                        @php
                                            $inicioTarea = \Carbon\Carbon::parse($tarea->fecha_inicio);
                                            $finTarea = \Carbon\Carbon::parse($tarea->fecha_fin);
                                            $duracionTarea = $inicioTarea->diffInDays($finTarea) + 1;

                                            // Calcular posición en la línea de tiempo (basado en meses)
                                            $mesesDesdeInicio = $inicioTarea->diffInMonths(\Carbon\Carbon::parse($fechaInicioProyecto));
                                            $porcentajeInicio = min(100, ($mesesDesdeInicio / 12) * 100);
                                            $porcentajeDuracion = min(100, ($duracionTarea / 30) * (100/12)); // Aproximadamente 30 días por mes
                                        @endphp

                                        <div class="flex items-center gap-4">
                                            <!-- Nombre de la actividad -->
                                            <div class="w-64 flex-shrink-0">
                                                <div class="flex items-center gap-2">
                                                    @if($tarea->estado === 'Completado')
                                                        <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-4 h-4 bg-gray-300 rounded-full"></div>
                                                    @endif
                                                    <div>
                                                        <p class="font-medium text-gray-900 text-sm">{{ $tarea->nombre }}</p>
                                                        <p class="text-xs text-gray-500">{{ $tarea->fase->nombre_fase }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Barra del Gantt -->
                                            <div class="flex-1 relative">
                                                <div class="h-6 bg-gray-100 rounded relative overflow-hidden">
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <div class="h-4 {{ $tarea->estado === 'Completado' ? 'bg-green-500' : 'bg-blue-500' }} rounded text-xs text-white font-medium flex items-center justify-center min-w-8"
                                                             style="width: {{ $porcentajeDuracion }}%; left: {{ $porcentajeInicio }}%;">
                                                            {{ $duracionTarea }}d
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Información adicional -->
                                            <div class="w-32 text-right text-xs text-gray-600">
                                                @if($tarea->responsableUsuario)
                                                    <p>{{ $tarea->responsableUsuario->nombre }}</p>
                                                @endif
                                                <p>{{ \Carbon\Carbon::parse($tarea->fecha_inicio)->format('d/m') }} - {{ \Carbon\Carbon::parse($tarea->fecha_fin)->format('d/m') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Leyenda -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">LEYENDA</h4>
                            <div class="flex items-center gap-6 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 bg-blue-500 rounded"></div>
                                    <span class="text-gray-700">Actividad en progreso</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                                    <span class="text-gray-700">Actividad completada</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 bg-gray-300 rounded"></div>
                                    <span class="text-gray-700">Actividad pendiente</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay actividades para el diagrama</h3>
                            <p class="text-gray-600 mb-4">Agrega actividades con fechas de inicio y fin para visualizar el diagrama de Gantt</p>
                            <button onclick="modalNuevaTarea.showModal()" class="btn bg-blue-600 text-white hover:bg-blue-700">
                                Crear primera actividad
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Próximos Hitos -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">PRÓXIMOS HITOS</h3>

                    @if(count($hitos) > 0)
                        <div class="space-y-3">
                            @foreach(array_slice($hitos, 0, 4) as $hito)
                                <div class="flex items-center gap-4 p-3 rounded-lg {{ $hito['completado'] ? 'bg-white border border-green-200' : 'bg-white border border-yellow-200' }}">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $hito['completado'] ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                        {{ $hito['completado'] ? '✓' : '⏰' }}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $hito['titulo'] }}</h4>
                                        <p class="text-sm text-gray-600">{{ $hito['descripcion'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium {{ $hito['completado'] ? 'text-green-700' : 'text-yellow-700' }}">
                                            {{ \Carbon\Carbon::parse($hito['fecha'])->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $hito['fase'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <span class="text-gray-600 text-sm font-bold">HI</span>
                            </div>
                            <p>No hay hitos definidos</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Actividad -->
    <dialog id="modalNuevaTarea" class="modal">
        <div class="modal-box w-11/12 max-w-2xl bg-white">
            <h3 class="font-bold text-lg text-black mb-4">Nueva Actividad del Proyecto</h3>

            <form method="POST" action="{{ route('proyectos.tareas.store', $proyecto) }}">
                @csrf

                <!-- Nombre de la actividad -->
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text text-black font-semibold">Nombre de la Actividad</span></label>
                    <input type="text" name="nombre" class="input input-bordered w-full bg-white text-black"
                           placeholder="Ej: Análisis de requisitos funcionales" required>
                </div>

                <!-- Descripción -->
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text text-black font-semibold">Descripción</span></label>
                    <textarea name="descripcion" class="textarea textarea-bordered bg-white text-black" rows="3"
                              placeholder="Detalla el alcance y objetivos de esta actividad..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Fase -->
                    <div class="form-control">
                        <label class="label"><span class="label-text text-black font-semibold">Fase</span></label>
                        <select name="id_fase" class="select select-bordered bg-white text-black" required>
                            @foreach($fases as $fase)
                                <option value="{{ $fase->id_fase }}" {{ $faseActual && $faseActual->id_fase === $fase->id_fase ? 'selected' : '' }}>
                                    {{ $fase->nombre_fase }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Horas estimadas -->
                    <div class="form-control">
                        <label class="label"><span class="label-text text-black font-semibold">Horas Estimadas</span></label>
                        <input type="number" name="horas_estimadas" class="input input-bordered bg-white text-black"
                               placeholder="40" min="0" step="0.5">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <!-- Fecha inicio -->
                    <div class="form-control">
                        <label class="label"><span class="label-text text-black font-semibold">Fecha de Inicio</span></label>
                        <input type="date" name="fecha_inicio" class="input input-bordered bg-white text-black">
                    </div>

                    <!-- Fecha fin -->
                    <div class="form-control">
                        <label class="label"><span class="label-text text-black font-semibold">Fecha de Fin</span></label>
                        <input type="date" name="fecha_fin" class="input input-bordered bg-white text-black">
                    </div>
                </div>

                <!-- Responsable -->
                <div class="form-control mt-4">
                    <label class="label"><span class="label-text text-black font-semibold">Responsable</span></label>
                    <select name="responsable" class="select select-bordered bg-white text-black">
                        <option value="">Sin asignar</option>
                        @foreach($miembrosEquipo as $miembro)
                            <option value="{{ $miembro->id }}">{{ $miembro->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Prioridad -->
                <div class="form-control mt-4">
                    <label class="label"><span class="label-text text-black font-semibold">Prioridad</span></label>
                    <select name="prioridad" class="select select-bordered bg-white text-black">
                        <option value="1">1 - Muy Baja</option>
                        <option value="3">3 - Baja</option>
                        <option value="5" selected>5 - Media</option>
                        <option value="8">8 - Alta</option>
                        <option value="10">10 - Crítica</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="modal-action">
                    <button type="button" onclick="modalNuevaTarea.close()" class="btn btn-ghost">Cancelar</button>
                    <button type="submit" class="btn bg-purple-600 text-white hover:bg-purple-700">Crear Actividad</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>Cerrar</button>
        </form>
    </dialog>

    <script>
        // Validación de fechas
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
            const fechaFin = document.querySelector('input[name="fecha_fin"]');

            if (fechaInicio && fechaFin) {
                fechaInicio.addEventListener('change', function() {
                    fechaFin.min = this.value;
                });

                fechaFin.addEventListener('change', function() {
                    if (this.value < fechaInicio.value) {
                        alert('La fecha de fin no puede ser anterior a la fecha de inicio');
                        this.value = '';
                    }
                });
            }
        });
    </script>

    <style>
        .truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</x-app-layout>
