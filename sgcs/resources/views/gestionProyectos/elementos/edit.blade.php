<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Editar Elemento de Configuración
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Código: <span class="font-mono font-bold">{{ $elemento->codigo_ec }}</span>
                </p>
            </div>
            <a href="{{ route('proyectos.elementos.index', $proyecto) }}" class="btn btn-ghost btn-sm">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <form action="{{ route('proyectos.elementos.update', [$proyecto, $elemento]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Código EC (solo lectura) -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Código EC</span>
                            </label>
                            <input
                                type="text"
                                value="{{ $elemento->codigo_ec }}"
                                class="input input-bordered w-full bg-base-200"
                                disabled
                            />
                            <label class="label">
                                <span class="label-text-alt">El código EC no se puede modificar</span>
                            </label>
                        </div>

                        <!-- Título -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Título del Elemento <span class="text-error">*</span></span>
                            </label>
                            <input
                                type="text"
                                name="titulo"
                                value="{{ old('titulo', $elemento->titulo) }}"
                                placeholder="Título del elemento"
                                class="input input-bordered w-full @error('titulo') input-error @enderror"
                                required
                            />
                            @error('titulo')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Descripción <span class="text-error">*</span></span>
                            </label>
                            <textarea
                                name="descripcion"
                                rows="4"
                                placeholder="Descripción del elemento..."
                                class="textarea textarea-bordered w-full @error('descripcion') textarea-error @enderror"
                                required
                            >{{ old('descripcion', $elemento->descripcion) }}</textarea>
                            @error('descripcion')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Tipo -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Tipo de Elemento <span class="text-error">*</span></span>
                            </label>
                            <select
                                name="tipo"
                                class="select select-bordered w-full @error('tipo') select-error @enderror"
                                required
                            >
                                <option value="DOCUMENTO" {{ old('tipo', $elemento->tipo) == 'DOCUMENTO' ? 'selected' : '' }}>
                                    📄 Documento
                                </option>
                                <option value="CODIGO" {{ old('tipo', $elemento->tipo) == 'CODIGO' ? 'selected' : '' }}>
                                    💻 Código Fuente
                                </option>
                                <option value="SCRIPT_BD" {{ old('tipo', $elemento->tipo) == 'SCRIPT_BD' ? 'selected' : '' }}>
                                    🗄️ Script de Base de Datos
                                </option>
                                <option value="CONFIGURACION" {{ old('tipo', $elemento->tipo) == 'CONFIGURACION' ? 'selected' : '' }}>
                                    ⚙️ Configuración
                                </option>
                                <option value="OTRO" {{ old('tipo', $elemento->tipo) == 'OTRO' ? 'selected' : '' }}>
                                    📦 Otro
                                </option>
                            </select>
                            @error('tipo')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Estado <span class="text-error">*</span></span>
                            </label>
                            <select
                                name="estado"
                                class="select select-bordered w-full @error('estado') select-error @enderror"
                                required
                            >
                                <option value="BORRADOR" {{ old('estado', $elemento->estado) == 'BORRADOR' ? 'selected' : '' }}>
                                    📝 Borrador
                                </option>
                                <option value="EN_REVISION" {{ old('estado', $elemento->estado) == 'EN_REVISION' ? 'selected' : '' }}>
                                    🔍 En Revisión
                                </option>
                                <option value="APROBADO" {{ old('estado', $elemento->estado) == 'APROBADO' ? 'selected' : '' }}>
                                    ✅ Aprobado
                                </option>
                                <option value="LIBERADO" {{ old('estado', $elemento->estado) == 'LIBERADO' ? 'selected' : '' }}>
                                    🚀 Liberado
                                </option>
                                <option value="OBSOLETO" {{ old('estado', $elemento->estado) == 'OBSOLETO' ? 'selected' : '' }}>
                                    ❌ Obsoleto
                                </option>
                            </select>
                            @error('estado')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Commit Actual en GitHub -->
                        @if($elemento->versionActual?->commit)
                            <div class="alert alert-success mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex-1">
                                    <h3 class="font-bold">Commit Actual en GitHub</h3>
                                    <div class="text-sm font-mono">
                                        � {{ Str::limit($elemento->versionActual->commit->hash_commit, 12) }}
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1">
                                        👤 {{ $elemento->versionActual->commit->autor }}
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        📅 {{ \Carbon\Carbon::parse($elemento->versionActual->commit->fecha_commit)->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-600 mt-1 italic">
                                        � {{ Str::limit($elemento->versionActual->commit->mensaje, 60) }}
                                    </div>
                                </div>
                                <a href="{{ $elemento->versionActual->commit->url_repositorio }}/commit/{{ $elemento->versionActual->commit->hash_commit }}"
                                   target="_blank"
                                   class="btn btn-sm btn-ghost">
                                    Ver en GitHub →
                                </a>
                            </div>
                        @endif

                        <!-- Nueva URL de Commit (opcional) -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Nueva URL del Commit en GitHub (Opcional)</span>
                            </label>
                            <input
                                type="text"
                                name="commit_url"
                                value="{{ old('commit_url') }}"
                                placeholder="https://github.com/user/repo/commit/abc123def456..."
                                class="input input-bordered w-full @error('commit_url') input-error @enderror"
                                id="commit_url_edit_input"
                            />
                            @error('commit_url')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                            <label class="label">
                                <span class="label-text-alt">
                                    Si ingresas una nueva URL de commit, se creará automáticamente una nueva versión
                                </span>
                            </label>
                        </div>

                        <!-- Vista previa de commit -->
                        <div class="alert alert-info mb-4" id="commit-preview-edit" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm">
                                <strong>Nuevo commit detectado:</strong>
                                <div id="commit-info-edit" class="mt-1 font-mono text-xs"></div>
                            </div>
                        </div>

                        <script>
                            // Detectar y validar URL de commit
                            document.getElementById('commit_url_edit_input')?.addEventListener('input', function(e) {
                                const url = e.target.value.trim();
                                const preview = document.getElementById('commit-preview-edit');
                                const info = document.getElementById('commit-info-edit');

                                const pattern = /github\.com\/([^\/]+)\/([^\/]+)\/commit\/([a-f0-9]+)/i;
                                const match = url.match(pattern);

                                if (match) {
                                    const [, owner, repo, hash] = match;
                                    info.textContent = `${owner}/${repo} - Commit: ${hash.substring(0, 7)}...`;
                                    preview.style.display = 'flex';
                                } else {
                                    preview.style.display = 'none';
                                }
                            });
                        </script>

                        <!-- Descripción de cambios (opcional) -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Descripción de Cambios (Opcional)</span>
                            </label>
                            <textarea
                                name="descripcion_cambios"
                                rows="3"
                                placeholder="Describe los cambios realizados en esta versión..."
                                class="textarea textarea-bordered w-full"
                            >{{ old('descripcion_cambios') }}</textarea>
                            <label class="label">
                                <span class="label-text-alt">Si realizas cambios significativos, se creará una nueva versión automáticamente</span>
                            </label>
                        </div>

                        <!-- Información de versión -->
                        <div class="stats shadow mb-6 w-full">
                            <div class="stat">
                                <div class="stat-title">Versión Actual</div>
                                <div class="stat-value text-primary">v{{ $elemento->versionActual?->version ?? '1.0' }}</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title">Total Versiones</div>
                                <div class="stat-value text-secondary">{{ $elemento->versiones->count() }}</div>
                            </div>
                            <div class="stat">
                                <div class="stat-title">Última Modificación</div>
                                <div class="stat-value text-sm">{{ $elemento->actualizado_en?->format('d/m/Y H:i') ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="card-actions justify-end pt-4 border-t">
                            <a href="{{ route('proyectos.elementos.index', $proyecto) }}" class="btn btn-ghost">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
