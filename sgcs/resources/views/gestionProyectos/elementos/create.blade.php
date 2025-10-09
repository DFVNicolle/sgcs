<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Crear Elemento de Configuración
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Proyecto: <span class="font-semibold">{{ $proyecto->nombre }}</span>
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
                    <form action="{{ route('proyectos.elementos.store', $proyecto) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Título -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Título del Elemento <span class="text-error">*</span></span>
                            </label>
                            <input
                                type="text"
                                name="titulo"
                                value="{{ old('titulo') }}"
                                placeholder="Ej: Documento de Requisitos, Script de Migración, etc."
                                class="input input-bordered w-full @error('titulo') input-error @enderror"
                                required
                            />
                            @error('titulo')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                            <label class="label">
                                <span class="label-text-alt">El código EC se generará automáticamente</span>
                            </label>
                        </div>

                        <!-- Descripción -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Descripción <span class="text-error">*</span></span>
                            </label>
                            <textarea
                                name="descripcion"
                                rows="4"
                                placeholder="Describe el propósito y contenido de este elemento de configuración..."
                                class="textarea textarea-bordered w-full @error('descripcion') textarea-error @enderror"
                                required
                            >{{ old('descripcion') }}</textarea>
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
                                <option value="" disabled selected>Selecciona el tipo</option>
                                <option value="DOCUMENTO" {{ old('tipo') == 'DOCUMENTO' ? 'selected' : '' }}>
                                    📄 Documento
                                </option>
                                <option value="CODIGO" {{ old('tipo') == 'CODIGO' ? 'selected' : '' }}>
                                    💻 Código Fuente
                                </option>
                                <option value="SCRIPT_BD" {{ old('tipo') == 'SCRIPT_BD' ? 'selected' : '' }}>
                                    🗄️ Script de Base de Datos
                                </option>
                                <option value="CONFIGURACION" {{ old('tipo') == 'CONFIGURACION' ? 'selected' : '' }}>
                                    ⚙️ Configuración
                                </option>
                                <option value="OTRO" {{ old('tipo') == 'OTRO' ? 'selected' : '' }}>
                                    📦 Otro
                                </option>
                            </select>
                            @error('tipo')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- URL del Commit de GitHub -->
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">URL del Commit en GitHub</span>
                                <span class="badge badge-ghost badge-sm">Opcional</span>
                            </label>
                            <input
                                type="text"
                                name="commit_url"
                                value="{{ old('commit_url') }}"
                                placeholder="https://github.com/user/repo/commit/abc123def456..."
                                class="input input-bordered w-full @error('commit_url') input-error @enderror"
                                id="commit_url_input"
                            />
                            @error('commit_url')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                            <label class="label">
                                <span class="label-text-alt">
                                    💡 <strong>Deja este campo vacío si aún no tienes código en GitHub.</strong> Puedes agregarlo después al editar el elemento.
                                </span>
                            </label>
                        </div>

                        <!-- Vista previa de datos del commit (se llenará automáticamente) -->
                        <div class="alert alert-success mb-4" id="commit-preview" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm">
                                <strong>Commit válido detectado:</strong>
                                <div id="commit-info" class="mt-1 font-mono text-xs"></div>
                            </div>
                        </div>

                        <script>
                            // Detectar y validar URL de commit
                            document.getElementById('commit_url_input').addEventListener('input', function(e) {
                                const url = e.target.value.trim();
                                const preview = document.getElementById('commit-preview');
                                const info = document.getElementById('commit-info');

                                // Patrón para URL de commit de GitHub
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

                        <!-- Información adicional -->
                        <div class="alert alert-info mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-bold">📋 ¿Cómo funciona?</h3>
                                <div class="text-xs">
                                    <ul class="list-disc list-inside mt-1 space-y-1">
                                        <li><strong>Paso 1:</strong> Crea el elemento de configuración (solo descripción, sin código)</li>
                                        <li><strong>Paso 2:</strong> Trabaja en GitHub y haz tus commits</li>
                                        <li><strong>Paso 3:</strong> Vuelve aquí, edita el elemento y vincula el commit de GitHub</li>
                                        <li>Cada vez que vincules un commit, se creará una nueva versión automáticamente</li>
                                        <li>El sistema obtendrá automáticamente los metadatos del commit (autor, fecha, mensaje)</li>
                                    </ul>
                                </div>
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
                                Crear Elemento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
