# 📝 FLUJO COMPLETO: Completar Tareas con Commits y Elementos de Configuración

## 🎯 Resumen del Flujo

Cuando una tarea se marca como **COMPLETADA**, el sistema automáticamente:
1. ✅ Solicita la URL del commit de GitHub
2. ✅ Valida la URL del commit
3. ✅ Crea o actualiza el Elemento de Configuración (EC)
4. ✅ Registra el commit en la base de datos
5. ✅ **Crea una versión en estado EN_REVISION**
6. ✅ Asocia la versión con el commit
7. ✅ El EC queda listo para que el líder lo apruebe

---

## 🔄 Formas de Completar una Tarea

### Opción 1: Arrastrando en el Tablero Kanban (Drag & Drop)

**Paso a paso:**

1. **Usuario arrastra tarea** a la columna "Completado/Done/Finalizado"

2. **Modal aparece automáticamente** solicitando:
   ```
   🔗 Completar Tarea
   
   Para completar esta tarea, necesitas proporcionar la URL 
   del commit de GitHub que representa el trabajo realizado.
   
   [Input: URL del Commit en GitHub]
   
   Ejemplo: https://github.com/usuario/repo/commit/abc123...
   ```

3. **Usuario ingresa URL del commit**, por ejemplo:
   ```
   https://github.com/ea2021072616/sgcs-project/commit/abc123def456789
   ```

4. **Sistema valida** que:
   - La URL contenga "github.com"
   - La URL contenga "/commit/"
   - No esté vacía

5. **Al hacer clic en "Completar Tarea":**
   - Se cierra el modal
   - La tarea se mueve visualmente a "Completado"
   - Se envía petición al servidor con `commit_url`

6. **Backend procesa** (método `cambiarFase()` en TareaProyectoController):
   ```php
   // Detecta que es estado completado
   if ($this->esEstadoCompletado($estadoNuevo)) {
       // Valida que tenga commit_url
       if (empty($validated['commit_url'])) {
           return error: "Se requiere la URL del commit"
       }
       
       // Procesa el commit
       $resultado = $this->procesarCompletarTarea($tarea, $commitUrl, $proyecto);
   }
   ```

7. **El método `procesarCompletarTarea()` hace:**

   a) **Valida URL del commit:**
   ```php
   if (!$commitService->esUrlCommitValida($commitUrl)) {
       return error: "URL inválida"
   }
   ```

   b) **Extrae información del commit:**
   ```php
   $infoCommit = $commitService->extraerInfoCommit($commitUrl);
   // Obtiene: owner, repo, hash
   ```

   c) **Crea o actualiza EC:**
   ```php
   if ($tarea->id_ec) {
       // Ya existe un EC, solo actualizarlo
       $ec = ElementoConfiguracion::find($tarea->id_ec);
   } else {
       // Crear nuevo EC
       $ec = new ElementoConfiguracion();
       $ec->codigo_ec = 'PROYECTO-EC-001';
       $ec->titulo = $tarea->nombre;
       $ec->tipo = 'CODIGO';
   }
   
   $ec->estado = 'EN_REVISION'; // ← Estado para revisión
   $ec->save();
   ```

   d) **Registra el commit:**
   ```php
   $commit = new CommitRepositorio();
   $commit->url_repositorio = 'https://github.com/owner/repo';
   $commit->hash_commit = 'abc123def456';
   $commit->ec_id = $ec->id;
   
   // Intenta obtener metadata desde GitHub API
   $datosCommit = $commitService->obtenerDatosCommit($commitUrl);
   if ($datosCommit) {
       $commit->autor = 'Nombre del autor';
       $commit->mensaje = 'Mensaje del commit';
       $commit->fecha_commit = '2025-11-06 10:30:00';
   }
   
   $commit->save();
   ```

   e) **✨ CREA VERSIÓN EN REVISIÓN (NUEVO):**
   ```php
   // Calcular nueva versión
   $versionAnterior = $ec->versionActual;
   
   if (!$versionAnterior || $versionAnterior->version === '0.0.0') {
       $nuevaVersion = '0.1.0'; // Primera versión funcional
   } else {
       // Incrementar versión minor
       $parts = explode('.', $versionAnterior->version);
       $parts[1] = (int)$parts[1] + 1;
       $parts[2] = 0;
       $nuevaVersion = implode('.', $parts);
   }
   
   // Crear versión
   $version = new VersionEC();
   $version->ec_id = $ec->id;
   $version->version = $nuevaVersion; // Ej: 0.1.0
   $version->estado = 'EN_REVISION'; // ← Esperando aprobación
   $version->registro_cambios = "Generado desde tarea: {$tarea->nombre}";
   $version->commit_id = $commit->id; // ← Vinculado al commit
   $version->creado_por = $tarea->responsable;
   $version->save();
   
   // Actualizar versión actual del EC
   $ec->version_actual_id = $version->id;
   $ec->save();
   ```

   f) **Actualiza la tarea:**
   ```php
   $tarea->id_ec = $ec->id;
   $tarea->commit_id = $commit->id;
   $tarea->commit_url = $commitUrl;
   $tarea->estado = 'COMPLETADA';
   $tarea->save();
   ```

8. **Notificación de éxito:**
   ```
   ✅ Tarea completada y EC creado en revisión
   ```

---

### Opción 2: Editando la Tarea Manualmente

**Paso a paso:**

1. **Usuario hace clic en "Editar" tarea**

2. **En el formulario de edición:**
   - Cambia el estado a "Completada" (select)
   - Campo `commit_url` se muestra automáticamente (JavaScript)
   
3. **Ingresa URL del commit:**
   ```
   URL del Commit en GitHub
   [https://github.com/usuario/repo/commit/abc123...]
   
   Requerido cuando la tarea se marca como completada
   ```

4. **Hace clic en "Guardar cambios"**

5. **Backend procesa** (método `update()` en TareaProyectoController):
   ```php
   $estadoAnterior = $tarea->estado; // Ej: 'EN_PROGRESO'
   $estadoNuevo = $validated['estado']; // Ej: 'COMPLETADA'
   
   if ($this->esEstadoCompletado($estadoNuevo) && !$this->esEstadoCompletado($estadoAnterior)) {
       // Validar que tenga commit_url
       if (empty($validated['commit_url'])) {
           return back()->withErrors(['commit_url' => 'Debes proporcionar la URL del commit para completar la tarea.']);
       }
       
       // Procesar el commit y crear/actualizar EC
       $resultado = $this->procesarCompletarTarea($tarea, $validated['commit_url'], $proyecto);
       
       if (!$resultado['success']) {
           return back()->withErrors(['commit_url' => $resultado['message']]);
       }
       
       $validated['commit_id'] = $resultado['commit_id'];
   }
   
   $tarea->update($validated);
   ```

6. **Mismo flujo que la Opción 1** a partir del paso 7

---

## 📊 Estados de los Elementos

### Estado del EC:
- **BORRADOR** → Creado manualmente, en desarrollo
- **EN_REVISION** → Generado desde tarea completada, esperando aprobación del líder
- **APROBADO** → Líder lo revisó y aprobó
- **LIBERADO** → Incluido en una release oficial
- **OBSOLETO** → Ya no se usa

### Estado de la Versión:
- **BORRADOR** → Versión inicial (0.0.0)
- **EN_REVISION** → Versión generada desde tarea, esperando aprobación
- **APROBADO** → Versión aprobada por el líder
- **LIBERADO** → Versión incluida en release
- **OBSOLETO** → Versión antigua reemplazada

---

## 🔍 Ejemplo Completo

### Situación Inicial:
- Tarea: "Implementar login de usuarios"
- Estado: "En Progreso"
- Responsable: Carmen Ruiz (dev1.scrum@sgcs.com)

### Acción:
1. Carmen arrastra la tarea a "Completado"
2. Aparece modal solicitando commit
3. Carmen ingresa: `https://github.com/ea2021072616/sgcs/commit/a1b2c3d4`
4. Hace clic en "Completar Tarea"

### Resultado en Base de Datos:

**Tabla `tareas_proyecto`:**
```sql
UPDATE tareas_proyecto SET
    estado = 'COMPLETADA',
    commit_url = 'https://github.com/ea2021072616/sgcs/commit/a1b2c3d4',
    commit_id = 'uuid-del-commit',
    id_ec = 'uuid-del-ec'
WHERE id_tarea = 'uuid-de-la-tarea';
```

**Tabla `elementos_configuracion`:**
```sql
INSERT INTO elementos_configuracion (
    id, codigo_ec, titulo, tipo, estado, proyecto_id, creado_por, version_actual_id
) VALUES (
    'uuid-del-ec',
    'ECOM-EC-005',
    'Implementar login de usuarios',
    'CODIGO',
    'EN_REVISION',
    'uuid-del-proyecto',
    'uuid-de-carmen',
    'uuid-de-la-version'
);
```

**Tabla `commits_repositorio`:**
```sql
INSERT INTO commits_repositorio (
    id, url_repositorio, hash_commit, autor, mensaje, fecha_commit, ec_id
) VALUES (
    'uuid-del-commit',
    'https://github.com/ea2021072616/sgcs',
    'a1b2c3d4',
    'Carmen Ruiz',
    'feat: Implementar sistema de login con JWT',
    '2025-11-06 14:30:00',
    'uuid-del-ec'
);
```

**Tabla `versiones_ec`:**
```sql
INSERT INTO versiones_ec (
    id, ec_id, version, estado, registro_cambios, commit_id, creado_por, creado_en
) VALUES (
    'uuid-de-la-version',
    'uuid-del-ec',
    '0.1.0',
    'EN_REVISION',
    'Generado desde tarea: Implementar login de usuarios',
    'uuid-del-commit',
    'uuid-de-carmen',
    '2025-11-06 14:30:05'
);
```

### Vista del Líder:

El líder (María González - Product Owner) ahora puede:

1. **Ver en "Elementos de Configuración":**
   ```
   ECOM-EC-005: Implementar login de usuarios
   Tipo: Código | Estado: 🔍 EN_REVISION
   Versión actual: v0.1.0
   ```

2. **Hacer clic en "Revisar"**

3. **En la vista de revisión verá:**
   ```
   Estado: EN_REVISION
   Este elemento está pendiente de revisión.
   
   Versión Actual: v0.1.0
   Estado: EN_REVISION
   
   Commit asociado: a1b2c3d4
   Autor: Carmen Ruiz
   Fecha: 06/11/2025 14:30
   Mensaje: feat: Implementar sistema de login con JWT
   [Ver en GitHub →]
   ```

4. **Puede aprobar:**
   - Ingresa URL de commit (puede ser el mismo)
   - Agrega descripción: "Login funcional con autenticación JWT"
   - Hace clic en "Aprobar y Versionar"

5. **Sistema crea nueva versión:**
   ```
   Versión: 1.0.0 (incrementa de 0.1.0 → 1.0.0)
   Estado: APROBADO
   Aprobado por: María González
   Aprobado en: 06/11/2025 15:00
   ```

6. **EC cambia a estado APROBADO**

---

## ✅ Validaciones Implementadas

### Frontend (JavaScript):
- ✅ URL no vacía
- ✅ URL contiene "github.com"
- ✅ URL contiene "/commit/"

### Backend (PHP):
- ✅ URL del commit es obligatoria al completar tarea
- ✅ Formato de URL válido (regex)
- ✅ Extracción correcta de owner/repo/hash
- ✅ Transacción de base de datos (rollback si falla)
- ✅ Creación de versión con versionamiento correcto

---

## 🎯 Beneficios del Sistema

1. **Trazabilidad Completa:**
   - Cada tarea completada → tiene commit asociado
   - Cada commit → tiene versión del EC
   - Cada versión → tiene registro de cambios

2. **Control de Calidad:**
   - Líder debe aprobar cada EC antes de liberarlo
   - No se puede completar tarea sin evidencia (commit)
   - Historial completo de versiones

3. **Integración con GitHub:**
   - Extrae metadata del commit automáticamente
   - Autor, mensaje, fecha desde GitHub API
   - Link directo al commit en GitHub

4. **Versionamiento Semántico:**
   - 0.0.0 → Versión inicial (borrador)
   - 0.1.0 → Primera versión en revisión
   - 1.0.0 → Primera versión aprobada
   - 1.1.0 → Siguiente versión (incremento minor)

---

## 🔧 Archivos Modificados

1. ✅ `app/Http/Controllers/gestionProyectos/TareaProyectoController.php`
   - Método `procesarCompletarTarea()` ahora crea versión

2. ✅ `resources/views/gestionProyectos/tareas/index.blade.php`
   - Modal para solicitar commit en drag & drop
   - JavaScript para detectar estado completado
   - Función `confirmarCommit()` para validar URL

3. ✅ `resources/views/gestionProyectos/tareas/edit.blade.php`
   - Campo commit_url requerido al cambiar a completado

---

**Estado:** ✅ FUNCIONAL Y PROBADO  
**Fecha:** 2025-11-06
