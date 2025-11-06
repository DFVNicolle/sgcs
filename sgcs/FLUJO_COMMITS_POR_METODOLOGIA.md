# 🔄 FLUJO DE COMPLETAR TAREAS POR METODOLOGÍA

## 📊 Diferencias entre Scrum y Cascada

El sistema ahora detecta automáticamente la metodología del proyecto y aplica las reglas correspondientes para solicitar el commit.

---

## 🏃 METODOLOGÍA SCRUM

### Fases de Scrum:
1. **Product Backlog** - Historias pendientes
2. **Sprint Planning** - Planificación del sprint
3. **In Progress** - En desarrollo ⚡
4. **In Review** - En revisión 👀
5. **Done** - ✅ COMPLETADO (requiere commit)

### ¿Cuándo se solicita el commit?

**Solo cuando se arrastra/mueve a la fase "Done":**

```javascript
if (metodologia === 'Scrum') {
    // Solo "Done" requiere commit obligatorio
    esEstadoCompletado = faseNombre.includes('Done');
}
```

### Flujo en Scrum:

```
Historia en "In Progress"
    ↓
Usuario trabaja en el código
    ↓
Hace commit en GitHub: abc123
    ↓
Arrastra historia a "Done"
    ↓
🔔 Modal aparece: "Ingresa URL del commit"
    ↓
Usuario pega: https://github.com/user/repo/commit/abc123
    ↓
Sistema crea:
  - Elemento de Configuración (EC)
  - Versión v0.1.0 en EN_REVISION
  - Registro del commit
    ↓
Product Owner revisa y aprueba → v1.0.0
```

### Ejemplo Scrum:

**Historia de Usuario:** "Como usuario quiero poder hacer login"

**Flujo:**
1. **Product Backlog** → Historia creada
2. **Sprint Planning** → Historia seleccionada para Sprint 1
3. **In Progress** → Desarrollador codifica
4. **In Review** → Code review (NO requiere commit aún)
5. **Done** → 🔔 **REQUIERE COMMIT** → Crea EC v0.1.0

---

## 🏔️ METODOLOGÍA CASCADA

### Fases de Cascada:
1. **Requisitos** - Definición de requerimientos
2. **Análisis** - Análisis detallado
3. **Diseño** - Diseño arquitectónico
4. **Implementación** - Codificación ⚡
5. **Pruebas** - Testing 🧪
6. **Despliegue** - ✅ COMPLETADO (requiere commit)
7. **Mantenimiento** - ✅ Post-producción (requiere commit)

### ¿Cuándo se solicita el commit?

**Cuando se arrastra/mueve a "Despliegue" o "Mantenimiento":**

```javascript
if (metodologia === 'Cascada') {
    // "Despliegue" y "Mantenimiento" requieren commit
    esEstadoCompletado = faseNombre.includes('Despliegue') || 
                         faseNombre.includes('Mantenimiento');
}
```

### Flujo en Cascada:

```
Tarea en "Implementación"
    ↓
Desarrollador codifica el módulo
    ↓
Hace commit en GitHub: xyz789
    ↓
Tarea pasa a "Pruebas" (NO requiere commit)
    ↓
QA valida y mueve a "Despliegue"
    ↓
🔔 Modal aparece: "Ingresa URL del commit"
    ↓
Usuario pega: https://github.com/user/repo/commit/xyz789
    ↓
Sistema crea:
  - Elemento de Configuración (EC)
  - Versión v0.1.0 en EN_REVISION
  - Registro del commit
    ↓
Líder de Proyecto aprueba → v1.0.0
```

### Ejemplo Cascada:

**Tarea:** "Implementar módulo de facturación"

**Flujo:**
1. **Requisitos** → Especificaciones definidas
2. **Análisis** → Casos de uso documentados
3. **Diseño** → Diagramas UML creados
4. **Implementación** → Código desarrollado
5. **Pruebas** → Testing unitario e integración (NO requiere commit)
6. **Despliegue** → 🔔 **REQUIERE COMMIT** → Crea EC v0.1.0
7. **Mantenimiento** → Correcciones → 🔔 **REQUIERE COMMIT** → v0.2.0

---

## 🎯 Estados Backend Reconocidos

El método `esEstadoCompletado()` en el backend ahora considera:

```php
private function esEstadoCompletado($estado): bool
{
    // Estados genéricos
    $estadosGenericos = [
        'COMPLETADA', 'COMPLETADO', 
        'Completado', 'Finalizado', 'FINALIZADO'
    ];
    
    // Estados específicos de Scrum
    $estadosScrum = ['Done', 'DONE'];
    
    // Estados específicos de Cascada
    $estadosCascada = [
        'Despliegue', 'DESPLIEGUE',
        'Mantenimiento', 'MANTENIMIENTO'
    ];
    
    // Combinar todos
    $estadosCompletados = array_merge(
        $estadosGenericos, 
        $estadosScrum, 
        $estadosCascada
    );
    
    return in_array($estado, $estadosCompletados);
}
```

---

## 📋 Comparativa Rápida

| Aspecto | Scrum | Cascada |
|---------|-------|---------|
| **Fases totales** | 5 fases | 7 fases |
| **Fase que requiere commit** | Done (última) | Despliegue, Mantenimiento (2 últimas) |
| **¿Pruebas requiere commit?** | ❌ No (In Review) | ❌ No (Pruebas) |
| **¿Implementación requiere commit?** | ❌ No (In Progress) | ❌ No (Implementación) |
| **Momento del commit** | Al finalizar historia | Al desplegar o mantener |
| **Versionamiento** | Incremental por sprint | Por entrega/release |

---

## 💡 Casos de Uso Reales

### Caso 1: Proyecto Scrum (E-Commerce)

**Historia:** "Implementar carrito de compras"

- **Product Backlog** → Historia creada con 8 story points
- **Sprint Planning** → Asignada al Sprint 3
- **In Progress** → Carmen (desarrollador) codifica durante 3 días
  - Hace commits: `abc123`, `def456`, `ghi789`
- **In Review** → Code review del Scrum Master
  - NO se pide commit aquí
- **Done** → 🔔 **Modal aparece**
  - Carmen ingresa: `https://github.com/ea2021072616/ecommerce/commit/ghi789` (último commit)
  - Sistema crea EC: `ECOM-EC-015` v0.1.0 en EN_REVISION
- **Product Owner aprueba** → EC pasa a v1.0.0 APROBADO

---

### Caso 2: Proyecto Cascada (Sistema ERP)

**Tarea:** "Módulo de contabilidad"

- **Requisitos** → SRS documentado (30 páginas)
- **Análisis** → Diagramas de casos de uso
- **Diseño** → Arquitectura de 3 capas diseñada
- **Implementación** → Andrés (dev senior) codifica durante 2 semanas
  - Hace commits: `xyz111`, `xyz222`, `xyz333`
- **Pruebas** → QA ejecuta 150 casos de prueba
  - NO se pide commit aquí
- **Despliegue** → 🔔 **Modal aparece**
  - Andrés ingresa: `https://github.com/ea2021072616/erp/commit/xyz333` (commit de producción)
  - Sistema crea EC: `ERP-EC-008` v0.1.0 en EN_REVISION
- **Líder de Proyecto aprueba** → EC pasa a v1.0.0 APROBADO
- **Mantenimiento** → Corrección de bug crítico
  - 🔔 **Modal aparece nuevamente**
  - Se ingresa commit de hotfix: `xyz444`
  - Sistema crea versión v1.0.1

---

## 🔍 Validaciones por Metodología

### Frontend (JavaScript):

```javascript
// Detecta metodología desde Blade
const metodologia = '{{ $metodologia->nombre }}';

// Scrum
if (metodologia === 'Scrum') {
    esEstadoCompletado = faseNombre.includes('Done');
}

// Cascada  
if (metodologia === 'Cascada') {
    esEstadoCompletado = faseNombre.includes('Despliegue') || 
                         faseNombre.includes('Mantenimiento');
}
```

### Backend (PHP):

```php
// Acepta múltiples variantes de nombres
$estadosCascada = [
    'Despliegue',    // Nombre exacto de la fase
    'DESPLIEGUE',    // Mayúsculas
    'Mantenimiento', // Nombre exacto
    'MANTENIMIENTO'  // Mayúsculas
];
```

---

## ✅ Ventajas de esta Implementación

### 1. **Flexibilidad por Metodología**
- Scrum: Solo requiere commit al finalizar la historia completa
- Cascada: Permite commits en despliegue y mantenimiento

### 2. **No Interrumpe el Flujo de Trabajo**
- En Scrum: Code review (In Review) no requiere commit
- En Cascada: Testing (Pruebas) no requiere commit

### 3. **Documentación Automática**
- Cada fase de completado genera su EC con versión
- Trazabilidad completa del código a producción

### 4. **Consistencia**
- Ambas metodologías generan ECs de la misma forma
- Mismo proceso de aprobación por el líder

---

## 🚀 Próximos Pasos

### Para Scrum:
1. Desarrollador trabaja en historia
2. Mueve a "In Review" para code review (sin commit)
3. Scrum Master revisa y mueve a "Done"
4. 🔔 Sistema pide commit final
5. Product Owner aprueba EC

### Para Cascada:
1. Desarrollador implementa módulo
2. Mueve a "Pruebas" para QA (sin commit)
3. QA valida y mueve a "Despliegue"
4. 🔔 Sistema pide commit de producción
5. Líder de Proyecto aprueba EC

---

**Estado:** ✅ IMPLEMENTADO CON SOPORTE MULTI-METODOLOGÍA  
**Fecha:** 2025-11-06  
**Metodologías Soportadas:** Scrum, Cascada
