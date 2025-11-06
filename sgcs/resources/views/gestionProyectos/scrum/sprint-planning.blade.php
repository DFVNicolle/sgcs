<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Sprint Planning
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                {{ $proyecto->nombre }} • Planificación de Sprints
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Navegación Scrum -->
            <x-scrum.navigation :proyecto="$proyecto" active="planning" />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Product Backlog -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">📦 Product Backlog</h3>
                            <button onclick="modalNuevaUserStory.showModal()" class="btn btn-sm bg-green-600 text-white hover:bg-green-700">
                                + Nueva User Story
                            </button>
                        </div>

                        <div class="space-y-3" style="max-height: 600px; overflow-y: auto;">
                            @forelse($productBacklog as $userStory)
                                <div class="backlog-item bg-gray-50 rounded-lg p-4 border hover:bg-gray-100 transition-colors"
                                     draggable="true"
                                     data-story-id="{{ $userStory->id_tarea }}">

                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900 text-sm">{{ $userStory->nombre }}</h4>
                                        <div class="flex items-center gap-2">
                                            @if($userStory->story_points)
                                                <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                                    {{ $userStory->story_points }}
                                                </span>
                                            @endif

                                            @php
                                                $prioridadColor = $userStory->prioridad >= 8 ? 'bg-red-100 text-red-800' :
                                                                 ($userStory->prioridad >= 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800');
                                            @endphp
                                            <span class="badge badge-xs {{ $prioridadColor }}">
                                                P{{ $userStory->prioridad ?? 5 }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($userStory->descripcion)
                                        <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ $userStory->descripcion }}</p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            @if($userStory->responsableUsuario)
                                                <div class="flex items-center gap-1">
                                                    <div class="w-5 h-5 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <span class="text-xs font-medium text-gray-700">
                                                            {{ substr($userStory->responsableUsuario->nombre, 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <span class="text-xs text-gray-600">{{ $userStory->responsableUsuario->nombre }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <button onclick="asignarASprint({{ $userStory->id_tarea }})"
                                                class="btn btn-xs btn-outline">
                                            Agregar a Sprint
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <div class="text-4xl mb-2">📦</div>
                                    <p>No hay user stories en el Product Backlog</p>
                                    <button onclick="modalNuevaUserStory.showModal()" class="btn btn-sm bg-green-600 text-white hover:bg-green-700 mt-2">
                                        Crear primera User Story
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sprint Backlog -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">🚀 Sprint Plannning</h3>
                                <p class="text-sm text-gray-600">Arrastra user stories para planificar el sprint</p>
                            </div>
                        </div>

                        <!-- Selector de Sprint -->
                        <div class="mb-4">
                            <label class="label">
                                <span class="label-text text-black font-semibold">Sprint a planificar:</span>
                            </label>
                            <div class="flex gap-2">
                                <select id="sprintSelector" class="select select-bordered select-sm bg-white flex-1">
                                    @foreach($sprints as $sprint)
                                        <option value="{{ $sprint }}">{{ $sprint }}</option>
                                    @endforeach
                                    <option value="nuevo">+ Nuevo Sprint</option>
                                </select>
                                <button onclick="modalNuevoSprint.showModal()" class="btn btn-sm btn-outline">
                                    + Sprint
                                </button>
                            </div>
                        </div>

                        <!-- Drop Zone para Sprint -->
                        <div id="sprintDropZone" class="bg-blue-50 border-2 border-dashed border-blue-200 rounded-lg p-6 mb-4 min-h-[200px]">
                            <div class="text-center text-blue-600">
                                <div class="text-4xl mb-2">🎯</div>
                                <p class="font-medium">Sprint Planning Area</p>
                                <p class="text-sm">Arrastra user stories aquí para incluirlas en el sprint</p>
                            </div>
                        </div>

                        <!-- Métricas del Sprint -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-4">
                                <p class="text-blue-100 text-sm">Story Points</p>
                                <p class="text-2xl font-bold" id="totalStoryPoints">0</p>
                            </div>
                            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-4">
                                <p class="text-green-100 text-sm">User Stories</p>
                                <p class="text-2xl font-bold" id="totalUserStories">0</p>
                            </div>
                        </div>

                        <!-- Capacidad del Equipo -->
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h4 class="font-medium text-yellow-800 mb-2">💡 Capacidad del Equipo</h4>
                            <p class="text-sm text-yellow-700">
                                Velocidad promedio: <strong>20-25 story points</strong> por sprint de 2 semanas
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-6 flex justify-end gap-4">
                <button class="btn btn-outline">Guardar como Borrador</button>
                <button class="btn bg-blue-600 text-white hover:bg-blue-700">Iniciar Sprint</button>
            </div>
        </div>
    </div>

    <!-- Modal Nueva User Story -->
    <dialog id="modalNuevaUserStory" class="modal">
        <div class="modal-box w-11/12 max-w-2xl bg-white">
            <h3 class="font-bold text-lg text-black mb-4">Nueva User Story</h3>

            <form method="POST" action="{{ route('proyectos.tareas.store', $proyecto) }}">
                @csrf

                <!-- User Story (formato estándar) -->
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text text-black font-semibold">User Story</span></label>
                    <input type="text" name="nombre" class="input input-bordered w-full bg-white text-black"
                           placeholder="Como [usuario], quiero [funcionalidad] para [beneficio]" required>
                </div>

                <!-- Criterios de Aceptación -->
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text text-black font-semibold">Criterios de Aceptación</span></label>
                    <textarea name="descripcion" class="textarea textarea-bordered bg-white text-black" rows="4"
                              placeholder="Dado que [contexto], cuando [acción], entonces [resultado esperado]"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Story Points -->
                    <div class="form-control">
                        <label class="label"><span class="label-text text-black font-semibold">Story Points</span></label>
                        <select name="story_points" class="select select-bordered bg-white text-black">
                            <option value="">Sin estimar</option>
                            <option value="1">1 - Muy pequeña</option>
                            <option value="2">2 - Pequeña</option>
                            <option value="3">3 - Pequeña-Media</option>
                            <option value="5">5 - Media</option>
                            <option value="8">8 - Grande</option>
                            <option value="13">13 - Muy grande</option>
                            <option value="21">21 - Épica</option>
                        </select>
                    </div>

                    <!-- Prioridad -->
                    <div class="form-control">
                        <label class="label"><span class="label-text text-black font-semibold">Prioridad</span></label>
                        <select name="prioridad" class="select select-bordered bg-white text-black">
                            <option value="10">10 - Crítica</option>
                            <option value="8">8 - Alta</option>
                            <option value="5" selected>5 - Media</option>
                            <option value="3">3 - Baja</option>
                            <option value="1">1 - Muy Baja</option>
                        </select>
                    </div>
                </div>

                <!-- Estado inicial (siempre Product Backlog) -->
                <input type="hidden" name="id_fase" value="{{ $metodologia->fases->first()->id_fase ?? '' }}">
                <input type="hidden" name="sprint" value="">

                <!-- Botones -->
                <div class="modal-action">
                    <button type="button" onclick="modalNuevaUserStory.close()" class="btn btn-ghost">Cancelar</button>
                    <button type="submit" class="btn bg-green-600 text-white hover:bg-green-700">Agregar al Product Backlog</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>Cerrar</button>
        </form>
    </dialog>

    <!-- Modal Nuevo Sprint -->
    <dialog id="modalNuevoSprint" class="modal">
        <div class="modal-box bg-white">
            <h3 class="font-bold text-lg text-black mb-4">Nuevo Sprint</h3>

            <div class="form-control mb-4">
                <label class="label"><span class="label-text text-black font-semibold">Nombre del Sprint</span></label>
                <input type="text" id="nombreNuevoSprint" class="input input-bordered w-full bg-white text-black"
                       placeholder="Sprint {{ $sprints->count() + 1 }}">
            </div>

            <div class="form-control mb-4">
                <label class="label"><span class="label-text text-black font-semibold">Duración</span></label>
                <select class="select select-bordered bg-white text-black">
                    <option>1 semana</option>
                    <option selected>2 semanas</option>
                    <option>3 semanas</option>
                    <option>4 semanas</option>
                </select>
            </div>

            <div class="modal-action">
                <button type="button" onclick="modalNuevoSprint.close()" class="btn btn-ghost">Cancelar</button>
                <button type="button" onclick="crearNuevoSprint()" class="btn bg-blue-600 text-white hover:bg-blue-700">Crear Sprint</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>Cerrar</button>
        </form>
    </dialog>

    <script>
        let sprintPlanificado = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Drag and Drop para planificación
            document.querySelectorAll('.backlog-item').forEach(item => {
                item.addEventListener('dragstart', function(e) {
                    e.dataTransfer.setData('text/plain', this.dataset.storyId);
                    this.style.opacity = '0.5';
                });

                item.addEventListener('dragend', function(e) {
                    this.style.opacity = '1';
                });
            });

            // Drop zone del sprint
            const sprintDropZone = document.getElementById('sprintDropZone');

            sprintDropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.style.backgroundColor = '#dbeafe';
                this.style.borderColor = '#3b82f6';
            });

            sprintDropZone.addEventListener('dragleave', function(e) {
                this.style.backgroundColor = '#eff6ff';
                this.style.borderColor = '#93c5fd';
            });

            sprintDropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.style.backgroundColor = '#eff6ff';
                this.style.borderColor = '#93c5fd';

                const storyId = e.dataTransfer.getData('text/plain');
                agregarASprintPlanning(storyId);
            });
        });

        function agregarASprintPlanning(storyId) {
            // Encontrar el elemento de la user story
            const storyElement = document.querySelector(`[data-story-id="${storyId}"]`);
            if (!storyElement) return;

            // Agregar al array de planificación
            if (!sprintPlanificado.includes(storyId)) {
                sprintPlanificado.push(storyId);

                // Clonar el elemento y agregarlo a la zona del sprint
                const clone = storyElement.cloneNode(true);
                clone.classList.add('bg-blue-50', 'border-blue-200');

                // Agregar botón de remover
                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '✕';
                removeBtn.className = 'btn btn-xs btn-circle btn-outline ml-2';
                removeBtn.onclick = () => removerDeSprintPlanning(storyId);

                clone.querySelector('.flex.items-center.justify-between').appendChild(removeBtn);

                // Reemplazar el contenido del drop zone si está vacío
                const dropZone = document.getElementById('sprintDropZone');
                if (dropZone.querySelector('.text-center')) {
                    dropZone.innerHTML = '<div class="space-y-3"></div>';
                }
                dropZone.querySelector('.space-y-3').appendChild(clone);

                // Ocultar del backlog
                storyElement.style.display = 'none';

                actualizarMetricasSprint();
            }
        }

        function removerDeSprintPlanning(storyId) {
            sprintPlanificado = sprintPlanificado.filter(id => id !== storyId);

            // Mostrar de nuevo en el backlog
            const backlogItem = document.querySelector(`[data-story-id="${storyId}"]`);
            if (backlogItem) {
                backlogItem.style.display = 'block';
            }

            // Remover del sprint planning
            const sprintItem = document.querySelector(`#sprintDropZone [data-story-id="${storyId}"]`);
            if (sprintItem) {
                sprintItem.remove();
            }

            actualizarMetricasSprint();

            // Si no hay items, mostrar mensaje
            const dropZone = document.getElementById('sprintDropZone');
            if (dropZone.querySelectorAll('[data-story-id]').length === 0) {
                dropZone.innerHTML = `
                    <div class="text-center text-blue-600">
                        <div class="text-4xl mb-2">🎯</div>
                        <p class="font-medium">Sprint Planning Area</p>
                        <p class="text-sm">Arrastra user stories aquí para incluirlas en el sprint</p>
                    </div>
                `;
            }
        }

        function asignarASprint(storyId) {
            agregarASprintPlanning(storyId);
        }

        function actualizarMetricasSprint() {
            let totalStoryPoints = 0;
            let totalUserStories = sprintPlanificado.length;

            sprintPlanificado.forEach(storyId => {
                const storyElement = document.querySelector(`[data-story-id="${storyId}"]`);
                const pointsElement = storyElement?.querySelector('.bg-blue-100');
                if (pointsElement) {
                    totalStoryPoints += parseInt(pointsElement.textContent) || 0;
                }
            });

            document.getElementById('totalStoryPoints').textContent = totalStoryPoints;
            document.getElementById('totalUserStories').textContent = totalUserStories;
        }

        function crearNuevoSprint() {
            const nombre = document.getElementById('nombreNuevoSprint').value;
            if (nombre.trim()) {
                // Implementar creación de sprint
                console.log(`Crear sprint: ${nombre}`);
                modalNuevoSprint.close();
            }
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .backlog-item {
            cursor: move;
            transition: all 0.2s;
        }

        .backlog-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
</x-app-layout>
