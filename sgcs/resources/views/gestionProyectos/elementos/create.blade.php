<x-app-layout>
    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Breadcrumb -->
            <div class="mb-4 flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('proyectos.show', $proyecto) }}" class="hover:text-gray-900">{{ $proyecto->nombre }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900 font-medium">Nuevo Elemento de Configuración</span>
            </div>

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Crear Elemento de Configuración</h1>
                <p class="text-gray-600 mt-1">Proyecto: <span class="font-semibold">{{ $proyecto->nombre }}</span></p>
            </div>

            <form action="{{ route('proyectos.elementos.store', $proyecto) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                    <div class="p-8">

                        <!-- Sección 1 -->
                        <div class="mb-8 bg-blue-50 p-5 rounded-lg border border-blue-100">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    1
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Información Básica</h3>
                            </div>
                            <p class="text-sm text-gray-600 ml-13">
                                Define la información principal del elemento de configuración
                            </p>
                        </div>

                        {{-- Título --}}
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Título del Elemento *
                            </label>
                            <input
                                type="text"
                                name="titulo"
                                value="{{ old('titulo') }}"
                                placeholder="Ej: Documento de Requisitos, Script de Migración, etc."
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 @error('titulo') border-red-500 @enderror"
                                required
                            />
                            @error('titulo')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">El código EC se generará automáticamente</p>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción *
                            </label>
                            <textarea name="descripcion" rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 @error('descripcion') border-red-500 @enderror"
                                      placeholder="Describe el propósito y contenido de este elemento..."
                                      required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipo --}}
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Elemento *
                            </label>
                            <select name="tipo" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 @error('tipo') border-red-500 @enderror" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="DOCUMENTO" {{ old('tipo') === 'DOCUMENTO' ? 'selected' : '' }}>📄 Documento</option>
                                <option value="CODIGO" {{ old('tipo') === 'CODIGO' ? 'selected' : '' }}>💻 Código</option>
                                <option value="SCRIPT_BD" {{ old('tipo') === 'SCRIPT_BD' ? 'selected' : '' }}>🗃️ Script de Base de Datos</option>
                                <option value="CONFIGURACION" {{ old('tipo') === 'CONFIGURACION' ? 'selected' : '' }}>⚙️ Configuración</option>
                                <option value="OTRO" {{ old('tipo') === 'OTRO' ? 'selected' : '' }}>📦 Otro</option>
                            </select>
                            @error('tipo')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección 2 -->
                        <div class="mb-8 bg-orange-50 p-5 rounded-lg border border-orange-100">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    2
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Archivo y Ubicación</h3>
                            </div>
                            <p class="text-sm text-gray-600 ml-13">
                                Sube el archivo del elemento y especifica su ubicación
                            </p>
                        </div>

                        {{-- Archivo --}}
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Archivo del Elemento *
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="archivo" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Subir archivo</span>
                                            <input id="archivo" name="archivo" type="file" class="sr-only" accept=".pdf,.doc,.docx,.txt,.sql,.xml,.json,.js,.php,.py,.java,.cpp,.c,.h,.css,.html,.md,.yml,.yaml,.sh,.bat,.ps1,.zip,.rar,.7z,.tar,.gz" required />
                                        </label>
                                        <p class="pl-1">o arrastra y suelta</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PDF, DOC, DOCX, TXT, SQL, XML, JSON, JS, PHP, PY, JAVA, CPP, C, H, CSS, HTML, MD, YML, YAML, SH, BAT, PS1, ZIP, RAR, 7Z, TAR, GZ hasta 10MB
                                    </p>
                                </div>
                            </div>
                            @error('archivo')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Ubicación --}}
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ubicación del Elemento
                            </label>
                            <input
                                type="text"
                                name="ubicacion"
                                value="{{ old('ubicacion') }}"
                                placeholder="Ej: /src/controllers, /docs/requisitos, /database/migrations"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 @error('ubicacion') border-red-500 @enderror"
                            />
                            @error('ubicacion')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Especifica la ruta o ubicación lógica del elemento en el proyecto</p>
                        </div>

                        <!-- Sección 3 -->
                        <div class="mb-8 bg-green-50 p-5 rounded-lg border border-green-100">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                    3
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Responsable y Estado</h3>
                            </div>
                            <p class="text-sm text-gray-600 ml-13">
                                Asigna un responsable y define el estado inicial
                            </p>
                        </div>

                        {{-- Responsable --}}
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Responsable del Elemento *
                            </label>
                            <select name="responsable_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 @error('responsable_id') border-red-500 @enderror" required>
                                <option value="">Seleccionar responsable...</option>
                                @foreach($miembrosEquipo as $miembro)
                                    <option value="{{ $miembro->id }}" {{ old('responsable_id') == $miembro->id ? 'selected' : '' }}>
                                        {{ $miembro->nombre_completo }} - {{ $miembro->correo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('responsable_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Estado Inicial *
                            </label>
                            <select name="estado" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 @error('estado') border-red-500 @enderror" required>
                                <option value="">Seleccionar estado...</option>
                                <option value="BORRADOR" {{ old('estado') === 'BORRADOR' ? 'selected' : '' }}>📝 Borrador</option>
                                <option value="REVISION" {{ old('estado') === 'REVISION' ? 'selected' : '' }}>🔍 En Revisión</option>
                                <option value="APROBADO" {{ old('estado') === 'APROBADO' ? 'selected' : '' }}>✅ Aprobado</option>
                                <option value="LIBERADO" {{ old('estado') === 'LIBERADO' ? 'selected' : '' }}>🚀 Liberado</option>
                            </select>
                            @error('estado')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Información adicional -->
                        <div class="mb-8 bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-blue-900 mb-2">¿Cómo funciona el versionado?</h4>
                                    <ul class="text-sm text-blue-800 space-y-1">
                                        <li><strong>Paso 1:</strong> Crea el elemento de configuración básico</li>
                                        <li><strong>Paso 2:</strong> Trabaja en GitHub y haz tus commits</li>
                                        <li><strong>Paso 3:</strong> Edita el elemento y vincula commits para crear versiones</li>
                                        <li>Cada commit vinculado genera automáticamente una nueva versión</li>
                                        <li>El sistema obtiene automáticamente metadatos del commit</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('proyectos.elementos.index', $proyecto) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Crear Elemento
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
