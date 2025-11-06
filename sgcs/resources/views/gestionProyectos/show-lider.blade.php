<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumb -->
            <div class="mb-4 flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('proyectos.index') }}" class="hover:text-gray-900">Proyectos</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900 font-medium">{{ $proyecto->nombre }}</span>
            </div>

            <!-- Header del Proyecto -->
            <div class="card bg-white shadow-sm mb-6">
                <div class="card-body">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="avatar placeholder">
                                <div class="bg-gray-200 text-black rounded-xl w-16 h-16 flex items-center justify-center">
                                    <span class="text-xl font-bold">{{ strtoupper(substr($proyecto->codigo ?? $proyecto->nombre, 0, 2)) }}</span>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $proyecto->nombre }}</h1>
                                <p class="text-sm text-gray-600 mt-1">{{ $proyecto->codigo }} • {{ $proyecto->metodologia->nombre ?? 'Sin metodología' }}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs text-gray-600">
                                        Creado por: <span class="font-medium">{{ $proyecto->creador->nombre_completo }}</span>
                                    </span>
                                    <span class="text-xs text-gray-600">
                                        {{ $proyecto->creado_en->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($esLider)
                                <span class="badge bg-blue-100 text-blue-800 border-0">Líder del Equipo</span>
                            @endif
                            <span class="badge bg-green-100 text-green-800 border-0">Activo</span>
                        </div>
                    </div>

                    @if($proyecto->descripcion)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-700">{{ $proyecto->descripcion }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabs de navegación según metodología (LÍDER tiene acceso completo) -->
            <div class="mb-6">
                <div class="card bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-3 py-2">
                        @php
                            $metodologia = strtolower($proyecto->metodologia->nombre ?? '');
                            $tabActual = request()->get('tab', 'dashboard');
                        @endphp

                        <nav class="project-tabs grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">

                            <!-- Tab Dashboard (siempre primero) -->
                            <a href="{{ route('proyectos.show', $proyecto) }}?tab=dashboard" class="{{ $tabActual === 'dashboard' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Dashboard
                            </a>

                            @if($metodologia === 'cascada')
                                <!-- Tab Gestión Cascada (solo para proyectos Cascada) -->
                                <a href="{{ route('proyectos.show', $proyecto) }}?tab=cascada" class="{{ $tabActual === 'cascada' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Gestión Cascada
                                    </a>
                            @endif

                                <a href="{{ route('proyectos.miembros.index', $proyecto) }}" class="{{ $tabActual === 'miembros' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                Miembros
                            </a>

                            @if($metodologia === 'scrum')
                                <!-- Navegación específica para Scrum -->
                                <a href="{{ route('scrum.dashboard', $proyecto) }}" class="{{ $tabActual === 'scrum' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Gestión Scrum
                                    </a>
                            @else
                                <!-- Navegación genérica -->
                                    <a href="{{ route('proyectos.tareas.index', $proyecto) }}?vista=gantt" class="{{ $tabActual === 'tareas' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Cronograma
                                </a>
                            @endif

                            <!-- Navegación común para todos -->
                            <a href="{{ route('proyectos.elementos.index', $proyecto) }}" class="{{ $tabActual === 'elementos' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Elementos Config.
                            </a>
                            <a href="{{ route('proyectos.trazabilidad', $proyecto) }}" class="{{ $tabActual === 'trazabilidad' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Trazabilidad
                            </a>
                            <a href="{{ route('proyectos.solicitudes.index', $proyecto) }}" class="{{ $tabActual === 'solicitudes' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Solicitudes Cambio
                            </a>
                            <a href="{{ route('proyectos.ccb.dashboard', $proyecto) }}" class="{{ $tabActual === 'ccb' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Dashboard CCB
                            </a>
                            <a href="{{ route('proyectos.cronograma.dashboard', $proyecto) }}" class="{{ $tabActual === 'cronograma' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Cronograma Inteligente
                            </a>
                            <a href="{{ route('proyectos.informes.dashboard', $proyecto) }}" class="{{ $tabActual === 'informes' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Informes
                            </a>
                            <a href="{{ route('proyectos.liberaciones.index', $proyecto) }}" class="{{ $tabActual === 'liberaciones' ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900' }} inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Liberaciones
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <style>
                /* Tabs grid: make each tab a full-width cell and style icon/text spacing */
                .project-tabs a { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; border-radius: 0.375rem; width: 100%; justify-content: flex-start; }
                .project-tabs a svg { flex: 0 0 auto; }
                .project-tabs a .tab-text { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            </style>

            <!-- Contenido: Dashboard del LÍDER según tab activo -->
            @php
                $tabActual = request()->get('tab', 'dashboard');
            @endphp

            @if($tabActual === 'dashboard')
                <!-- ===== TAB: DASHBOARD ===== -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Dashboard</h2>
                    <p class="text-sm text-gray-600">Resumen general del proyecto</p>
                </div>

                <!-- Métricas Generales -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Equipos</span>
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $proyecto->equipos->count() }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Miembros</span>
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $proyecto->equipos->sum(function($equipo) { return $equipo->miembros->count(); }) }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Elementos</span>
                        <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Cambios</span>
                        <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                </div>
            </div>

            @if($metodologia === 'scrum')
                <!-- Métricas Scrum adicionales -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Sprint Activo -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-blue-700 font-medium">Sprint Activo</p>
                                    <p class="text-3xl font-bold text-blue-800 mt-1">Sprint #1</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Story Points -->
                    <div class="bg-purple-50 border border-purple-200 rounded-lg shadow-sm">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-purple-700 font-medium">Story Points</p>
                                    <p class="text-3xl font-bold text-purple-800 mt-1">0/0</p>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Equipos Scrum -->
                    <div class="bg-green-50 border border-green-200 rounded-lg shadow-sm">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-green-700 font-medium">Equipos Dev</p>
                                    <p class="text-3xl font-bold text-green-800 mt-1">{{ $proyecto->equipos->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Impedimentos -->
                    <div class="bg-red-50 border border-red-200 rounded-lg shadow-sm">
                        <div class="card-body">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-red-700 font-medium">Impedimentos</p>
                                    <p class="text-3xl font-bold text-red-800 mt-1">0</p>
                                </div>
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($metodologia === 'cascada' && isset($fases))
                <!-- Tarjetas de Cascada en el Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Fase Actual</span>
                            <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                                <span class="text-indigo-600 text-xs font-bold">{{ substr($faseActual->nombre_fase ?? 'N/A', 0, 1) }}</span>
                            </div>
                        </div>
                        <p class="text-xl font-bold text-gray-900">{{ $faseActual->nombre_fase ?? 'Sin definir' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Progreso</span>
                            @php
                                $totalFases = $fases->count();
                                $fasesCompletadas = collect($progresoPorFase)->where('fase_completada', true)->count();
                                $progresoGeneral = $totalFases > 0 ? round(($fasesCompletadas / $totalFases) * 100) : 0;
                            @endphp
                            <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                                <span class="text-green-600 text-xs font-bold">{{ $progresoGeneral }}%</span>
                            </div>
                        </div>
                        <p class="text-xl font-bold text-gray-900">{{ $progresoGeneral }}%</p>
                        <div class="mt-3">
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all progress-bar" data-width="{{ $progresoGeneral }}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Duración</span>
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <span class="text-blue-600 text-xs font-bold">{{ $duracionTotal }}</span>
                            </div>
                        </div>
                        <p class="text-xl font-bold text-gray-900">{{ $duracionTotal }} <span class="text-sm font-normal text-gray-500">días</span></p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Hitos</span>
                            <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                                <span class="text-amber-600 text-xs font-bold">{{ count($hitos) }}</span>
                            </div>
                        </div>
                        <p class="text-xl font-bold text-gray-900">{{ count($hitos) }}</p>
                    </div>
                </div>
            @endif

            <!-- Acciones Rápidas -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-5">Acciones Rápidas</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button class="px-4 py-3 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Crear Elemento
                    </button>
                    <button class="px-4 py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Nueva Solicitud
                    </button>
                    <button class="px-4 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Programar Liberación
                    </button>
                </div>
            </div>

            <!-- Equipos del Proyecto -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-5">Equipos del Proyecto</h3>
                @if($proyecto->equipos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($proyecto->equipos as $equipo)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-2">{{ $equipo->nombre }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ $equipo->miembros->count() }} miembros</p>
                                <div class="flex -space-x-2">
                                    @foreach($equipo->miembros->take(5) as $miembro)
                                        @if($miembro->usuario)
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-xs font-semibold text-indigo-700">
                                                {{ substr($miembro->usuario->nombre, 0, 1) }}
                                            </div>
                                        @endif
                                    @endforeach
                                    @if($equipo->miembros->count() > 5)
                                        <div class="w-8 h-8 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-xs font-semibold text-gray-700">
                                            +{{ $equipo->miembros->count() - 5 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">No hay equipos asignados</p>
                    </div>
                @endif
            </div>

            @elseif($tabActual === 'cascada' && $metodologia === 'cascada' && isset($fases))
                <!-- ===== TAB: GESTIÓN CASCADA ===== -->

                <!-- Header Principal -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                Gestión Cascada
                            </h1>
                            <p class="text-gray-600 mt-2">Metodología Waterfall - Vista completa del proyecto en cascada</p>
                        </div>
                        <button onclick="modalNuevaTarea.showModal()" class="px-6 py-3 bg-indigo-600 text-black font-semibold rounded-lg hover:bg-indigo-700 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Nueva Actividad
                        </button>
                    </div>
                </div>

                <!-- Métricas Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 rounded-xl p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-black uppercase tracking-wider">Fase Actual</span>
                            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                                <span class="text-white text-lg font-bold">{{ substr($faseActual->nombre_fase ?? 'N', 0, 1) }}</span>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $faseActual->nombre_fase ?? 'Sin definir' }}</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Progreso General</span>
                            @php
                                $totalFases = $fases->count();
                                $fasesCompletadas = collect($progresoPorFase)->where('fase_completada', true)->count();
                                $progresoGeneral = $totalFases > 0 ? round(($fasesCompletadas / $totalFases) * 100) : 0;
                            @endphp
                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center shadow-md">
                                <span class="text-white text-sm font-bold">{{ $progresoGeneral }}%</span>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $progresoGeneral }}% Completado</p>
                        <div class="mt-3">
                            <div class="w-full bg-green-200 rounded-full h-3">
                                <div class="bg-green-600 h-3 rounded-full transition-all shadow-sm progress-bar" data-width="{{ $progresoGeneral }}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Duración Total</span>
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ $duracionTotal }} <span class="text-base font-normal text-gray-600">días</span></p>
                    </div>

                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-black uppercase tracking-wider">Hitos</span>
                            <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900">{{ count($hitos) }} Hitos</p>
                    </div>
                </div>

                <!-- Cronología del Proyecto -->
@if($fechaInicioProyecto && $fechaFinProyecto)
<div class="bg-white border-2 border-gray-200 rounded-xl p-6 mb-8 shadow-sm">
    <div class="flex items-center gap-2 mb-6">
        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Cronología del Proyecto</h3>
    </div>

    <!-- Fila de información de fechas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="text-center">
            <p class="text-sm font-semibold text-gray-700 mb-1">Fecha Inicio</p>
            <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($fechaInicioProyecto)->format('d/m/Y') }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm font-semibold text-gray-700 mb-1">Fecha Actual</p>
            <p class="text-lg font-bold text-gray-900">{{ now()->format('d/m/Y') }}</p>
        </div>
        <div class="text-center">
            <p class="text-sm font-semibold text-gray-700 mb-1">Fecha Fin</p>
            <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($fechaFinProyecto)->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Tarjetas principales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Inicio -->
        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border-2 border-green-200">
            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-green-700 uppercase tracking-wider mb-1">Inicio del Proyecto</p>
            <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($fechaInicioProyecto)->format('d/m/Y') }}</p>
        </div>

        <!-- Actual -->
        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border-2 border-blue-200">
            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-1">Fecha Actual</p>
            <p class="text-lg font-bold text-gray-900">{{ now()->format('d/m/Y') }}</p>
        </div>

        <!-- Fin -->
        <div class="text-center p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl border-2 border-amber-200">
            <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">Fin Planificado</p>
            <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($fechaFinProyecto)->format('d/m/Y') }}</p>
        </div>
    </div>
</div>
@endif
                <!-- ========== SECCIÓN 1: GESTIÓN CASCADA ========== -->
                <div class="bg-white border-2 border-indigo-200 rounded-xl p-6 mb-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-indigo-100">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                            <span class="text-black text-xl font-bold">1</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Progreso por Fases</h2>
                            <p class="text-sm text-gray-600">Seguimiento del avance en cada fase de la metodología</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($fases as $index => $fase)
                            @php
                                $progreso = $progresoPorFase[$fase->id_fase];
                                $esFaseActual = $faseActual && $faseActual->id_fase === $fase->id_fase;
                                $faseCompletada = $progreso['fase_completada'];
                                $porcentaje = $progreso['porcentaje'];
                            @endphp

                            <div class="relative">
                                @if($index < $fases->count() - 1)
                                    <div class="absolute left-5 top-12 w-0.5 h-8 {{ $faseCompletada ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                                @endif

                                <div class="flex items-start gap-4 p-4 rounded-lg border {{ $esFaseActual ? 'bg-indigo-50 border-indigo-200' : ($faseCompletada ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200') }}">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ $faseCompletada ? 'bg-green-500 text-white' : ($esFaseActual ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-500') }}">
                                        @if($faseCompletada)
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <span class="text-sm font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold text-gray-900">{{ $fase->nombre_fase }}</h4>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-semibold {{ $faseCompletada ? 'text-green-600' : ($esFaseActual ? 'text-indigo-600' : 'text-gray-500') }}">
                                                    {{ $porcentaje }}%
                                                </span>
                                                @if($esFaseActual)
                                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded">En progreso</span>
                                                @elseif($faseCompletada)
                                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Completada</span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="text-sm text-gray-600 mb-3">{{ $fase->descripcion }}</p>

                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                            <div class="h-2 rounded-full {{ $faseCompletada ? 'bg-green-500' : ($esFaseActual ? 'bg-indigo-500' : 'bg-gray-400') }} transition-all progress-bar"
                                                 data-width="{{ $porcentaje }}"></div>
                                        </div>

                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">{{ $progreso['completadas'] }}/{{ $progreso['total'] }} actividades</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ========== SECCIÓN 2: CRONOGRAMA MAESTRO ========== -->
                <div class="bg-white border-2 border-green-200 rounded-xl p-6 mb-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-green-100">
                        <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center shadow-md">
                            <span class="text-white text-xl font-bold">2</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Cronograma Maestro</h2>
                            <p class="text-sm text-gray-600">Detalle de todas las actividades organizadas por fase</p>
                        </div>
                    </div>

                    @if($tareas->count() > 0)
                        <div class="space-y-5">
                            @foreach($fases as $fase)
                                @php
                                    $tareasDelaFase = $tareas->where('id_fase', $fase->id_fase);
                                @endphp

                                @if($tareasDelaFase->count() > 0)
                                    <div class="border-2 border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                        <div class="bg-gradient-to-r from-green-50 to-green-100 px-5 py-4 border-b-2 border-green-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                        </svg>
                                                    </div>
                                                    <h4 class="font-bold text-gray-900 text-lg">{{ $fase->nombre_fase }}</h4>
                                                </div>
                                                <span class="px-3 py-1 bg-green-600 text-white text-sm font-bold rounded-full">{{ $tareasDelaFase->count() }} actividades</span>
                                            </div>
                                        </div>

                                        <div class="divide-y divide-gray-100">
                                            @foreach($tareasDelaFase as $tarea)
                                                <div class="px-4 py-3 hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-start justify-between gap-4">
                                                        <div class="flex items-start gap-3 flex-1">
                                                            <div class="mt-1">
                                                                @if($tarea->estado === 'Completado')
                                                                    <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                    </div>
                                                                @else
                                                                    <div class="w-5 h-5 bg-gray-200 rounded-full"></div>
                                                                @endif
                                                            </div>
                                                            <div class="flex-1">
                                                                <p class="font-medium text-gray-900">{{ $tarea->nombre }}</p>
                                                                <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-600">
                                                                    @if($tarea->responsableUsuario)
                                                                        <span class="flex items-center gap-1">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                            </svg>
                                                                            {{ $tarea->responsableUsuario->nombre }}
                                                                        </span>
                                                                    @endif
                                                                    @if($tarea->fecha_inicio && $tarea->fecha_fin)
                                                                        <span class="flex items-center gap-1">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                            </svg>
                                                                            {{ \Carbon\Carbon::parse($tarea->fecha_inicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tarea->fecha_fin)->format('d/m/Y') }}
                                                                        </span>
                                                                    @endif
                                                                    @if($tarea->horas_estimadas)
                                                                        <span class="flex items-center gap-1">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                            </svg>
                                                                            {{ $tarea->horas_estimadas }}h
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($tarea->prioridad)
                                                            @php
                                                                $prioridadConfig = $tarea->prioridad >= 8
                                                                    ? ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Alta']
                                                                    : ($tarea->prioridad >= 5
                                                                        ? ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Media']
                                                                        : ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Baja']);
                                                            @endphp
                                                            <span class="px-2 py-1 {{ $prioridadConfig['bg'] }} {{ $prioridadConfig['text'] }} text-xs font-semibold rounded">
                                                                {{ $prioridadConfig['label'] }}
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
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay actividades</h3>
                            <p class="text-gray-600 mb-4">Agrega actividades con fechas para visualizar el cronograma</p>
                            <button onclick="modalNuevaTarea.showModal()" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                Crear Actividad
                            </button>
                        </div>
                    @endif
                </div>

                <!-- ========== SECCIÓN 3: DIAGRAMA DE GANTT ========== -->
                <div class="bg-white border-2 border-blue-200 rounded-xl p-6 mb-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-blue-100">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-md">
                            <span class="text-white text-xl font-bold">3</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Diagrama de Gantt</h2>
                            <p class="text-sm text-gray-600">Visualización temporal de todas las actividades del proyecto</p>
                        </div>
                    </div>

                    @if($tareas->count() > 0)
                        <div class="mb-5 grid grid-cols-3 gap-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <p class="text-xs text-blue-700 font-medium mb-1">Fecha de Inicio</p>
                                <p class="text-lg font-bold text-blue-500">{{ \Carbon\Carbon::parse($fechaInicioProyecto)->format('d/m/Y') }}</p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <p class="text-xs text-green-700 font-medium mb-1">Fecha de Fin</p>
                                <p class="text-lg font-bold text-green-900">{{ \Carbon\Carbon::parse($fechaFinProyecto)->format('d/m/Y') }}</p>
                            </div>
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3">
                                <p class="text-xs text-black font-medium mb-1">Duración Total</p>
                                <p class="text-lg font-bold text-black">{{ $duracionTotal }} días</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="min-w-[800px]">
                                <div class="space-y-3">
                                    @foreach($tareas as $tarea)
                                        @php
                                            $inicioTarea = \Carbon\Carbon::parse($tarea->fecha_inicio);
                                            $finTarea = \Carbon\Carbon::parse($tarea->fecha_fin);
                                            $duracionTarea = $inicioTarea->diffInDays($finTarea) + 1;
                                            $diasDesdeInicio = \Carbon\Carbon::parse($fechaInicioProyecto)->diffInDays($inicioTarea);
                                            $porcentajeInicio = min(100, ($diasDesdeInicio / max(1, $duracionTotal)) * 100);
                                            $porcentajeDuracion = min(100, ($duracionTarea / max(1, $duracionTotal)) * 100);
                                        @endphp

                                        <div class="bg-white rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="w-48 flex-shrink-0">
                                                    <div class="flex items-center gap-2">
                                                        @if($tarea->estado === 'Completado')
                                                            <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center shadow-sm">
                                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <div class="w-5 h-5 bg-gray-300 rounded-full shadow-sm"></div>
                                                        @endif
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-bold text-gray-900 truncate">{{ $tarea->nombre }}</p>
                                                            <p class="text-xs text-gray-600">{{ $tarea->fase->nombre_fase }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex-1 relative h-10 bg-gradient-to-r from-gray-100 to-gray-50 rounded-lg border border-gray-200">
                                                    <div class="absolute top-1/2 transform -translate-y-1/2 h-6 {{ $tarea->estado === 'Completado' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-indigo-500 to-indigo-600' }} rounded-md text-xs text-white font-bold flex items-center justify-center px-2 shadow-md gantt-bar"
                                                         data-width="{{ max(8, $porcentajeDuracion) }}" data-left="{{ $porcentajeInicio }}">
                                                        {{ $duracionTarea }}d
                                                    </div>
                                                </div>

                                                <div class="w-32 text-right flex-shrink-0">
                                                    @if($tarea->responsableUsuario)
                                                        <p class="text-xs font-semibold text-gray-900 truncate">{{ $tarea->responsableUsuario->nombre }}</p>
                                                    @endif
                                                    <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($tarea->fecha_inicio)->format('d/m') }} - {{ \Carbon\Carbon::parse($tarea->fecha_fin)->format('d/m') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-center gap-8 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded shadow-md"></div>
                                    <span class="font-medium text-gray-700">En progreso</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 bg-gradient-to-r from-green-500 to-green-600 rounded shadow-md"></div>
                                    <span class="font-medium text-gray-700">Completada</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 bg-gray-300 rounded shadow-md"></div>
                                    <span class="font-medium text-gray-700">Pendiente</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay actividades</h3>
                            <p class="text-gray-600 mb-4">Agrega actividades con fechas para visualizar el diagrama</p>
                            <button onclick="modalNuevaTarea.showModal()" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                                Crear Actividad
                            </button>
                        </div>
                    @endif
                </div>

                <!-- ========== SECCIÓN BONUS: HITOS DEL PROYECTO ========== -->
                <div class="bg-white border-2 border-amber-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-amber-100">
                        <div class="w-8 h-8 bg-amber-600 rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Hitos del Proyecto</h2>
                            <p class="text-sm text-gray-600">Puntos clave y eventos importantes del proyecto</p>
                        </div>
                    </div>

                    @if(count($hitos) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach(array_slice($hitos, 0, 4) as $hito)
                                <div class="flex items-center gap-4 p-5 rounded-xl border-2 {{ $hito['completado'] ? 'bg-gradient-to-br from-green-50 to-green-100 border-green-300' : 'bg-gradient-to-br from-amber-50 to-amber-100 border-amber-300' }} shadow-sm hover:shadow-md transition-shadow">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-md {{ $hito['completado'] ? 'bg-green-600' : 'bg-amber-600' }}">
                                        @if($hito['completado'])
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900">{{ $hito['titulo'] }}</h4>
                                        <p class="text-sm text-gray-700 mt-1">{{ $hito['descripcion'] }}</p>
                                        <div class="flex items-center gap-3 mt-2">
                                            <span class="text-xs font-bold {{ $hito['completado'] ? 'text-green-700' : 'text-amber-700' }}">
                                                📅 {{ \Carbon\Carbon::parse($hito['fecha'])->format('d/m/Y') }}
                                            </span>
                                            <span class="text-xs px-2 py-1 bg-white rounded-full font-semibold text-gray-700">{{ $hito['fase'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-600">No hay hitos definidos</p>
                        </div>
                    @endif
                </div>

            @elseif($tabActual === 'dashboard')
            <!-- Si NO es Cascada pero está en Dashboard, mostrar contenido vacío o por defecto -->
            @endif
            <!-- Fin de tabs condicionales -->

            @if($tabActual === 'dashboard')
            <!-- Módulos de Gestión (disponibles solo en Dashboard) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <div class="card bg-white shadow-sm border-l-4 border-blue-500">
                    <div class="card-body">
                        <h3 class="font-semibold text-gray-900 mb-2">Crear Elemento de Configuración</h3>
                        <p class="text-sm text-gray-600 mb-3">Agrega un nuevo elemento al proyecto</p>
                        <a href="{{ route('proyectos.elementos.create', $proyecto) }}" class="btn btn-sm bg-blue-500 text-white hover:bg-blue-600 border-0">
                            Crear Elemento
                        </a>
                    </div>
                </div>

                <div class="card bg-white shadow-sm border-l-4 border-orange-500">
                    <div class="card-body">
                        <h3 class="font-semibold text-gray-900 mb-2">Nueva Solicitud de Cambio</h3>
                        <p class="text-sm text-gray-600 mb-3">Registra una solicitud de cambio</p>
                        <a href="{{ route('proyectos.solicitudes.create', $proyecto) }}" class="btn btn-sm bg-orange-500 text-white hover:bg-orange-600 border-0">Crear Solicitud</a>
                    </div>
                </div>

                <div class="card bg-white shadow-sm border-l-4 border-green-500">
                    <div class="card-body">
                        <h3 class="font-semibold text-gray-900 mb-2">Programar Liberación</h3>
                        <p class="text-sm text-gray-600 mb-3">Planifica una nueva liberación</p>
                        <button class="btn btn-sm bg-green-500 text-white hover:bg-green-600 border-0">Crear Liberación</button>
                    </div>
                </div>
            </div>

            <!-- Lista de Equipos (solo en Dashboard) -->
            <div class="card bg-white shadow-sm mt-6">
                <div class="card-body">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Equipos del Proyecto</h2>
                        <a href="{{ route('proyectos.miembros.index', $proyecto) }}" class="btn btn-sm bg-gradient-to-r from-indigo-500 to-purple-600 text-white border-0 hover:shadow-lg transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Gestionar Miembros
                        </a>
                    </div>

                    <div class="space-y-4">
                        @foreach($proyecto->equipos as $equipo)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $equipo->nombre }}</h3>
                                        <p class="text-sm text-gray-600">{{ $equipo->miembros->count() }} miembros</p>
                                    </div>
                                    @if($equipo->lider)
                                        <span class="badge bg-orange-100 text-orange-800 border-0">
                                            Líder: {{ $equipo->lider->nombre_completo }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Miembros del equipo -->
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach($equipo->miembros as $miembro)
                                        <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-full">
                                            <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-bold text-gray-700">{{ substr($miembro->nombre_completo, 0, 1) }}</span>
                                            </div>
                                            <span class="text-sm text-gray-900">{{ $miembro->nombre_completo }}</span>
                                            <span class="text-xs text-gray-500">• {{ $miembro->rol_proyecto->nombre ?? 'Sin rol' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Modal Nueva Actividad -->
    @if(isset($fases) && isset($miembrosEquipo))
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
    @endif

    <script>
        // Validación de fechas
        document.addEventListener('DOMContentLoaded', function() {
            const modalNuevaTarea = document.getElementById('modalNuevaTarea');
            if (modalNuevaTarea) {
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
            }
        });

        // Set progress bar and gantt bar widths
        document.querySelectorAll('.progress-bar').forEach(function(bar) {
            const width = bar.getAttribute('data-width');
            bar.style.width = width + '%';
        });

        document.querySelectorAll('.gantt-bar').forEach(function(bar) {
            const width = bar.getAttribute('data-width');
            const left = bar.getAttribute('data-left');
            bar.style.width = width + '%';
            bar.style.left = left + '%';
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
