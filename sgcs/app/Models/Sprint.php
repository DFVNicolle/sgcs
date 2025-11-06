<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sprint extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'sprints';

    /**
     * La clave primaria de la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'id_sprint';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nombre',
        'id_proyecto',
        'fecha_inicio',
        'fecha_fin',
        'objetivo',
        'velocidad_estimada',
        'velocidad_real',
        'estado',
        'observaciones',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'velocidad_estimada' => 'integer',
        'velocidad_real' => 'integer',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    /**
     * Nombres de las columnas de timestamps personalizados.
     */
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    /**
     * Estados posibles del sprint.
     */
    const ESTADOS = [
        'planificado' => 'Planificado',
        'activo' => 'Activo',
        'completado' => 'Completado',
        'cancelado' => 'Cancelado',
    ];

    /**
     * Relación: Un sprint pertenece a un proyecto.
     */
    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id');
    }

    /**
     * Relación: Un sprint tiene muchas user stories (tareas).
     */
    public function userStories(): HasMany
    {
        return $this->hasMany(TareaProyecto::class, 'id_sprint', 'id_sprint');
    }

    /**
     * Relación: Un sprint tiene muchos registros de daily scrum.
     */
    public function dailyScrums(): HasMany
    {
        return $this->hasMany(DailyScrum::class, 'id_sprint', 'id_sprint');
    }

    /**
     * Calcular la duración del sprint en días.
     */
    public function getDuracionAttribute()
    {
        if ($this->fecha_inicio && $this->fecha_fin) {
            return $this->fecha_inicio->diffInDays($this->fecha_fin) + 1;
        }
        return 0;
    }

    /**
     * Calcular el progreso del sprint en porcentaje.
     */
    public function getProgresoAttribute()
    {
        $totalStoryPoints = $this->userStories->sum('story_points');
        if ($totalStoryPoints === 0) {
            return 0;
        }

        $storyPointsCompletados = $this->userStories
            ->where('estado', 'Completado')
            ->sum('story_points');

        return round(($storyPointsCompletados / $totalStoryPoints) * 100);
    }

    /**
     * Verificar si el sprint está activo.
     */
    public function isActivo()
    {
        return $this->estado === 'activo';
    }

    /**
     * Verificar si el sprint está completado.
     */
    public function isCompletado()
    {
        return $this->estado === 'completado';
    }

    /**
     * Obtener el sprint activo de un proyecto.
     */
    public static function sprintActivo($proyectoId)
    {
        return static::where('id_proyecto', $proyectoId)
            ->where('estado', 'activo')
            ->first();
    }

    /**
     * Calcular burndown data para el sprint.
     */
    public function getBurndownData()
    {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            return [];
        }

        $totalStoryPoints = $this->userStories->sum('story_points');
        $duracion = $this->duracion;
        $burndownData = [];

        for ($dia = 0; $dia <= $duracion; $dia++) {
            $fecha = $this->fecha_inicio->copy()->addDays($dia);

            // Calcular story points restantes hasta esa fecha
            $storyPointsCompletados = $this->userStories
                ->where('estado', 'Completado')
                ->where('fecha_completado', '<=', $fecha->format('Y-m-d'))
                ->sum('story_points');

            $storyPointsRestantes = $totalStoryPoints - $storyPointsCompletados;

            // Línea ideal
            $idealRestante = max(0, $totalStoryPoints - ($totalStoryPoints / $duracion * $dia));

            $burndownData[] = [
                'dia' => $dia,
                'fecha' => $fecha->format('Y-m-d'),
                'ideal' => $idealRestante,
                'actual' => max(0, $storyPointsRestantes),
            ];
        }

        return $burndownData;
    }
}
