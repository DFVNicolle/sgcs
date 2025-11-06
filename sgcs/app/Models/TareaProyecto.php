<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TareaProyecto extends Model
{
    protected $table = 'tareas_proyecto';
    protected $primaryKey = 'id_tarea';
    public $timestamps = false; // Usamos creado_en y actualizado_en personalizados

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'id_proyecto',
        'id_fase',
        'id_ec',
        'id_sprint',
        'nombre',
        'descripcion',
        'responsable',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'prioridad',
        // Campos específicos de Scrum
        'story_points',
        'sprint',
        // Campos específicos de Cascada
        'horas_estimadas',
        'entregable',
        // Campos comunes
        'criterios_aceptacion',
        'notas',
        'creado_por',
        // Campos de commit
        'commit_url',
        'commit_id',
        // Campos de cronograma inteligente
        'duracion_minima',
        'es_ruta_critica',
        'holgura_dias',
        'fecha_inicio_original',
        'fecha_fin_original',
        'puede_paralelizarse',
        'dependencias',
        'progreso_real',
    ];

    protected $casts = [
        'criterios_aceptacion' => 'array',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'story_points' => 'integer',
        'horas_estimadas' => 'decimal:2',
        'prioridad' => 'integer',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
        // Casts de cronograma inteligente
        'duracion_minima' => 'integer',
        'es_ruta_critica' => 'boolean',
        'holgura_dias' => 'integer',
        'fecha_inicio_original' => 'date',
        'fecha_fin_original' => 'date',
        'puede_paralelizarse' => 'boolean',
        'dependencias' => 'array',
        'progreso_real' => 'decimal:2',
    ];

    /**
     * Relación con Proyecto
     */
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id');
    }

    /**
     * Relación con Fase de Metodología
     */
    public function fase(): BelongsTo
    {
        return $this->belongsTo(FaseMetodologia::class, 'id_fase', 'id_fase');
    }

    /**
     * Relación con Elemento de Configuración
     */
    public function elementoConfiguracion(): BelongsTo
    {
        return $this->belongsTo(ElementoConfiguracion::class, 'id_ec', 'id');
    }

    /**
     * Relación con Usuario (responsable)
     */
    public function responsableUsuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'responsable', 'id');
    }

    /**
     * Relación con Sprint (para metodología Scrum)
     */
    public function sprintModel(): BelongsTo
    {
        return $this->belongsTo(Sprint::class, 'id_sprint', 'id_sprint');
    }

    /**
     * Relación con Commit de GitHub
     */
    public function commit(): BelongsTo
    {
        return $this->belongsTo(CommitRepositorio::class, 'commit_id', 'id');
    }

    /**
     * Scope para filtrar por estado
     */
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar por fase
     */
    public function scopeFase($query, $idFase)
    {
        return $query->where('id_fase', $idFase);
    }

    /**
     * Scope para filtrar por sprint
     */
    public function scopeSprint($query, $sprint)
    {
        return $query->where('sprint', $sprint);
    }

    /**
     * Verifica si la tarea está en revisión
     */
    public function estaEnRevision(): bool
    {
        return in_array($this->estado, ['EN_REVISION', 'In Review', 'Review']);
    }

    /**
     * Verifica si la tarea está completada
     */
    public function estaCompletada(): bool
    {
        return in_array($this->estado, ['COMPLETADA', 'Done', 'DONE']);
    }
}
