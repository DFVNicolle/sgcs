# 📅 Sistema de Cronograma Dinámico - Metodología Cascada

## 🎯 Visión General

El sistema de cronograma implementado en el SGCS (Sistema de Gestión de Configuración de Software) para proyectos con metodología Cascada incluye **ajustes automáticos** cuando se presentan retrasos o cambios aprobados.

---

## 📊 Componentes del Cronograma

### 1. **Estructura Base**
```
Proyecto
├── Fases de Metodología (orden secuencial)
│   ├── Análisis de Requisitos
│   ├── Diseño del Sistema
│   ├── Implementación
│   ├── Pruebas
│   ├── Integración
│   ├── Despliegue
│   └── Mantenimiento
└── Tareas por Fase
    ├── Fecha inicio
    ├── Fecha fin
    ├── Responsable
    ├── Estado (Pendiente/En Progreso/Completado)
    └── Prioridad
```

### 2. **Cálculo de Fechas Críticas**
- **Fecha Inicio del Proyecto**: Primera tarea de la primera fase
- **Fecha Fin del Proyecto**: Última tarea de la última fase
- **Duración Total**: Suma de días de todas las fases
- **Ruta Crítica**: Secuencia de tareas que determinan la duración mínima

---

## 🔄 Ajuste Automático por Retrasos

### **Escenario 1: Retraso en una Tarea**

#### Detección del Retraso
```php
// El sistema detecta automáticamente:
if ($tarea->fecha_fin_real > $tarea->fecha_fin_planificada) {
    $diasRetraso = $tarea->fecha_fin_real->diffInDays($tarea->fecha_fin_planificada);
    ajustarCronograma($tarea, $diasRetraso);
}
```

#### Propagación del Retraso
```
┌─────────────────────────────────────────────────┐
│ ANTES DEL RETRASO                               │
├─────────────────────────────────────────────────┤
│ Tarea A: 01/11 - 05/11 (5 días)                │
│ Tarea B: 06/11 - 10/11 (5 días)                │
│ Tarea C: 11/11 - 15/11 (5 días)                │
└─────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────┐
│ DESPUÉS DEL RETRASO (Tarea A: +3 días)         │
├─────────────────────────────────────────────────┤
│ Tarea A: 01/11 - 08/11 (8 días) ⚠️             │
│ Tarea B: 09/11 - 13/11 (5 días) ↪️ desplazada  │
│ Tarea C: 14/11 - 18/11 (5 días) ↪️ desplazada  │
└─────────────────────────────────────────────────┘
```

### **Lógica de Ajuste**

```php
function ajustarCronograma($tareaRetrasada, $diasRetraso) {
    // 1. Identificar fase afectada
    $fase = $tareaRetrasada->fase;
    
    // 2. Obtener todas las tareas posteriores en la misma fase
    $tareasPosteriores = TareaProyecto::where('id_fase', $fase->id_fase)
        ->where('fecha_inicio', '>=', $tareaRetrasada->fecha_fin_real)
        ->get();
    
    // 3. Desplazar cada tarea posterior
    foreach ($tareasPosteriores as $tarea) {
        $tarea->fecha_inicio = $tarea->fecha_inicio->addDays($diasRetraso);
        $tarea->fecha_fin = $tarea->fecha_fin->addDays($diasRetraso);
        $tarea->save();
        
        // Registrar en historial
        HistorialCambio::create([
            'tipo' => 'AJUSTE_CRONOGRAMA',
            'motivo' => "Retraso en tarea: {$tareaRetrasada->nombre}",
            'dias_desplazados' => $diasRetraso
        ]);
    }
    
    // 4. Si el retraso afecta el fin de la fase, ajustar fases siguientes
    if ($fase->fecha_fin_real > $fase->fecha_fin_planificada) {
        ajustarFasesSiguientes($fase, $diasRetraso);
    }
}
```

---

## ✅ Ajuste por Cambio Aprobado

### **Flujo de Solicitud de Cambio**

```
1. Usuario crea Solicitud de Cambio
   ↓
2. Comité de Control de Cambios (CCB) revisa
   ↓
3. Miembros votan (Aprobar/Rechazar)
   ↓
4. Si APROBADO → Impacto en Cronograma
   │
   ├─ Cambio afecta Elementos de Configuración
   │  └─ Identifica tareas asociadas
   │
   └─ Análisis de Impacto
      ├─ Días adicionales estimados
      ├─ Recursos necesarios
      └─ Fases afectadas
```

### **Escenario 2: Cambio Aprobado con Impacto**

#### Ejemplo Práctico

**Solicitud de Cambio: "Agregar módulo de reportes avanzados"**

```json
{
  "id_solicitud": "SC-2024-001",
  "titulo": "Módulo de Reportes Avanzados",
  "impacto": {
    "elementos_afectados": [
      "EC-Backend-API",
      "EC-Frontend-Dashboard"
    ],
    "fases_afectadas": ["Diseño", "Implementación", "Pruebas"],
    "dias_adicionales": 10,
    "prioridad": "Alta"
  },
  "estado": "APROBADO"
}
```

#### Ajuste Automático

```php
function aplicarCambioAprobado($solicitudCambio) {
    // 1. Analizar elementos afectados
    $elementosAfectados = $solicitudCambio->itemsCambio->pluck('elemento_id');
    
    // 2. Identificar tareas relacionadas con esos elementos
    $tareasAfectadas = TareaProyecto::whereIn('id_ec', $elementosAfectados)->get();
    
    // 3. Calcular impacto en tiempo
    $impacto = ImpactoService::calcular($solicitudCambio);
    
    // 4. Ajustar cronograma
    foreach ($impacto['fases_afectadas'] as $faseId) {
        $fase = FaseMetodologia::find($faseId);
        
        // Extender duración de la fase
        $fase->fecha_fin_estimada = $fase->fecha_fin_estimada
            ->addDays($impacto['dias_adicionales']);
        $fase->save();
        
        // Ajustar tareas dentro de la fase
        redistribuirTareas($fase, $impacto['dias_adicionales']);
    }
    
    // 5. Propagar cambios a fases posteriores
    propagarCambiosAFasesSiguientes($fase, $impacto['dias_adicionales']);
    
    // 6. Notificar a stakeholders
    notificarCambiosEnCronograma($solicitudCambio);
}
```

---

## 🎨 Visualización en el Sistema

### **1. Diagrama de Gantt Dinámico**

```
█████████████ Verde    = Tarea completada
▓▓▓▓▓▓▓▓▓▓▓▓▓ Azul     = Tarea en progreso
░░░░░░░░░░░░░ Gris     = Tarea pendiente
▒▒▒▒▒▒▒▒▒▒▒▒▒ Amarillo = Tarea con retraso
```

**Características:**
- **Línea Vertical Actual**: Marca la fecha de hoy
- **Barras Extendidas**: Muestra visualmente los retrasos (color ámbar)
- **Tooltips**: Al pasar el mouse, muestra:
  - Fecha original vs. fecha ajustada
  - Motivo del cambio
  - Responsable
  - Estado actual

### **2. Cronología de Cambios**

```
┌──────────────────────────────────────────────┐
│ HISTORIAL DE AJUSTES                         │
├──────────────────────────────────────────────┤
│ ✓ 25/10/2024 - Inicio del proyecto           │
│ ⚠️ 05/11/2024 - Retraso: Tarea "Análisis DB" │
│   └─ +3 días por cambio de proveedor         │
│ ✅ 10/11/2024 - Cambio aprobado: SC-001      │
│   └─ +10 días en fase Implementación         │
│ ⏰ HOY                                        │
│ 📅 20/12/2024 - Fin planificado (nuevo)      │
└──────────────────────────────────────────────┘
```

---

## 🚨 Alertas y Notificaciones

### **Sistema de Alertas Automáticas**

#### 1. **Alerta de Retraso Inminente**
```
🟡 ALERTA: Tarea "Diseño de Base de Datos"
   - Planificada: 01/11 - 05/11
   - Estado: Solo 40% completado
   - Días restantes: 1
   - Acción recomendada: Reasignar recursos
```

#### 2. **Notificación de Ajuste**
```
🔔 CRONOGRAMA ACTUALIZADO
   - Cambio aprobado: SC-2024-001
   - Nueva fecha de entrega: 20/12/2024 (+10 días)
   - Fases afectadas: Implementación, Pruebas
   - Ver detalles →
```

#### 3. **Reporte Semanal**
```
📊 RESUMEN SEMANAL - Proyecto ERP
   ✅ Tareas completadas: 12
   ⏳ Tareas en progreso: 5
   ⚠️ Tareas retrasadas: 2
   📈 Progreso general: 45%
   🎯 En ruta crítica: Sí
```

---

## 🔧 Estrategias de Mitigación

### **1. Buffer de Tiempo**
```php
// Agregar 20% de buffer a cada fase
$duracionBase = $fase->calcularDuracion();
$duracionConBuffer = $duracionBase * 1.2;
```

### **2. Paralelización de Tareas**
```
ANTES (Secuencial):
Tarea A (5d) → Tarea B (5d) → Tarea C (5d) = 15 días

DESPUÉS (Paralelo):
Tarea A (5d) ┐
              ├→ Tarea C (5d) = 10 días
Tarea B (5d) ┘
```

### **3. Reasignación de Recursos**
```
Si (retraso > 2 días):
   - Agregar desarrollador adicional
   - Reducir alcance de tareas no críticas
   - Extender jornada laboral (previa aprobación)
```

---

## 📈 Métricas de Seguimiento

### **Indicadores Clave (KPIs)**

1. **SPI (Schedule Performance Index)**
   ```
   SPI = Valor Ganado / Valor Planificado
   
   > 1.0 = Adelantado
   = 1.0 = En tiempo
   < 1.0 = Retrasado
   ```

2. **Variación de Cronograma**
   ```
   SV = Valor Ganado - Valor Planificado
   
   Positivo = Adelanto
   Negativo = Retraso
   ```

3. **Tasa de Cambios Aprobados**
   ```
   TCR = (Cambios Aprobados / Total Solicitudes) × 100
   ```

4. **Impacto Promedio de Cambios**
   ```
   IPC = Σ(Días Adicionales) / Cambios Aprobados
   ```

---

## 🎯 Ejemplo Completo: Caso de Uso Real

### **Proyecto: Sistema ERP Empresarial**

#### **Estado Inicial**
```
Fase 1: Análisis (15 días)     → 01/11 - 15/11
Fase 2: Diseño (20 días)       → 16/11 - 05/12
Fase 3: Implementación (30 d)  → 06/12 - 04/01
Fase 4: Pruebas (15 días)      → 05/01 - 19/01
TOTAL: 80 días
```

#### **Evento 1: Retraso en Análisis (+5 días)**
```
Motivo: Cliente solicitó reuniones adicionales
Fecha detección: 10/11
Acción automática:
  ├─ Fase 1: Nueva fecha fin → 20/11
  ├─ Fase 2: Desplazada → 21/11 - 10/12
  ├─ Fase 3: Desplazada → 11/12 - 09/01
  └─ Fase 4: Desplazada → 10/01 - 24/01
NUEVO TOTAL: 85 días
```

#### **Evento 2: Cambio Aprobado - Módulo de Reportes (+10 días)**
```
Solicitud: SC-2024-001
Fecha aprobación: 25/11
Fases afectadas: Diseño, Implementación
Acción automática:
  ├─ Fase 2: +5 días → 21/11 - 15/12
  ├─ Fase 3: +5 días → 16/12 - 14/01
  └─ Fase 4: Desplazada → 15/01 - 29/01
NUEVO TOTAL: 95 días
```

#### **Mitigación Aplicada**
```
Estrategia:
1. Paralelizar pruebas unitarias (ahorro: -3 días)
2. Agregar 1 desarrollador en Implementación (ahorro: -4 días)
3. Reducir documentación no crítica (ahorro: -2 días)

TOTAL FINAL: 86 días (vs. 80 días planificados originalmente)
Variación: +7.5% (aceptable según estándares PMI)
```

---

## 🛠️ Configuración en el Sistema

### **Variables de Configuración**

```php
// config/cronograma.php
return [
    'ajuste_automatico' => true,
    'buffer_default' => 0.20, // 20% de buffer
    'alerta_retraso_dias' => 2,
    'propagacion_automatica' => true,
    'notificaciones' => [
        'retraso' => ['email', 'dashboard'],
        'cambio_aprobado' => ['email', 'dashboard', 'slack'],
        'ajuste_cronograma' => ['email', 'dashboard']
    ],
    'umbrales' => [
        'retraso_critico' => 5, // días
        'variacion_aceptable' => 10 // porcentaje
    ]
];
```

---

## 📚 Referencias y Mejores Prácticas

### **Estándares Aplicados**
- ✅ PMI - Project Management Institute
- ✅ PMBOK Guide (Project Management Body of Knowledge)
- ✅ IEEE 828 (Configuration Management Plan)
- ✅ ISO/IEC 12207 (Software Lifecycle Processes)

### **Mejores Prácticas**
1. **Revisión Semanal**: Reuniones de seguimiento cada viernes
2. **Baseline del Proyecto**: Guardar versión original del cronograma
3. **Control de Cambios**: Todo cambio debe pasar por CCB
4. **Documentación**: Registrar motivo de cada ajuste
5. **Comunicación**: Notificar a todos los stakeholders

---

## 🎓 Conclusión

El sistema de cronograma dinámico del SGCS proporciona:

✅ **Ajustes Automáticos** en tiempo real  
✅ **Trazabilidad Completa** de todos los cambios  
✅ **Visualización Clara** con Gantt y métricas  
✅ **Notificaciones Proactivas** para prevenir retrasos  
✅ **Mitigación Inteligente** de impactos  
✅ **Cumplimiento de Estándares** internacionales  

---

**Última actualización**: 30 de Octubre, 2025  
**Versión del documento**: 1.0  
**Autor**: Sistema SGCS - Gestión de Configuración de Software
