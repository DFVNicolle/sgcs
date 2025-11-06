<?php

namespace App\Http\Controllers\GestionProyectos;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Models\TareaProyecto;
use App\Models\FaseMetodologia;
use App\Models\ElementoConfiguracion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScrumController extends Controller
{
    /**
     * Verifica que el proyecto use metodología Scrum
     */
    private function verificarMetodologiaScrum(Proyecto $proyecto)
    {
        if (strtolower($proyecto->metodologia->nombre) !== 'scrum') {
            abort(403, 'Este controlador solo funciona con proyectos Scrum.');
        }
    }

    /**
     * Verifica permisos del usuario en el proyecto
     */
    private function verificarPermisos(Proyecto $proyecto)
    {
        $usuario = Auth::user();

        // El creador siempre tiene permisos
        if ($proyecto->creado_por === $usuario->id) {
            return;
        }

        // Verificar si es miembro del equipo
        $esMiembro = $proyecto->equipos()
            ->whereHas('miembros', function($query) use ($usuario) {
                $query->where('usuario_id', $usuario->id);
            })->exists();

        if (!$esMiembro) {
            abort(403, 'No tienes permisos para acceder a este proyecto Scrum.');
        }
    }

    /**
     * Dashboard principal de Scrum - Vista del tablero con sprints
     */
    public function dashboard(Proyecto $proyecto)
    {
        $this->verificarMetodologiaScrum($proyecto);
        $this->verificarPermisos($proyecto);

        // Obtener fases de Scrum
        $metodologia = $proyecto->metodologia;
        $fases = FaseMetodologia::where('id_metodologia', $metodologia->id_metodologia)
            ->orderBy('orden')
            ->get();

        // Obtener tareas agrupadas por sprint
        $tareas = TareaProyecto::where('id_proyecto', $proyecto->id)
            ->with(['fase', 'elementoConfiguracion', 'responsableUsuario'])
            ->orderBy('sprint')
            ->orderBy('prioridad', 'desc')
            ->get();

        // Agrupar por sprint y fase
        $tareasPorSprint = $tareas->groupBy('sprint');
        $tareasPorFase = $tareas->groupBy('id_fase');

        // Obtener sprints únicos
        $sprints = $tareas->pluck('sprint')->filter()->unique()->sort()->values();

        // Sprint actual (último sprint con tareas)
        $sprintActual = $sprints->last() ?? 'Sprint 1';

        // Métricas de sprint
        $tareasSprintActual = $tareasPorSprint->get($sprintActual, collect());
        $totalStoryPoints = $tareasSprintActual->sum('story_points');
        $storyPointsCompletados = $tareasSprintActual->where('estado', 'Completado')->sum('story_points');

        // Burndown chart data (simulado)
        $burndownData = $this->calcularBurndown($tareasSprintActual);

        // Obtener miembros del equipo
        $miembrosEquipo = collect();
        foreach ($proyecto->equipos as $equipo) {
            $miembrosEquipo = $miembrosEquipo->merge($equipo->miembros);
        }
        $miembrosEquipo = $miembrosEquipo->unique('id');

        return view('gestionProyectos.scrum.dashboard', compact(
            'proyecto',
            'metodologia',
            'fases',
            'tareas',
            'tareasPorSprint',
            'tareasPorFase',
            'sprints',
            'sprintActual',
            'totalStoryPoints',
            'storyPointsCompletados',
            'burndownData',
            'miembrosEquipo'
        ));
    }

    /**
     * Vista de planificación de sprint
     */
    public function sprintPlanning(Proyecto $proyecto)
    {
        $this->verificarMetodologiaScrum($proyecto);
        $this->verificarPermisos($proyecto);

        $metodologia = $proyecto->metodologia;

        // Product Backlog (tareas sin sprint asignado)
        $productBacklog = TareaProyecto::where('id_proyecto', $proyecto->id)
            ->whereNull('sprint')
            ->with(['fase', 'elementoConfiguracion', 'responsableUsuario'])
            ->orderBy('prioridad', 'desc')
            ->get();

        // Sprints existentes
        $sprints = TareaProyecto::where('id_proyecto', $proyecto->id)
            ->whereNotNull('sprint')
            ->pluck('sprint')
            ->unique()
            ->sort()
            ->values();

        return view('gestionProyectos.scrum.sprint-planning', compact(
            'proyecto',
            'metodologia',
            'productBacklog',
            'sprints'
        ));
    }

    /**
     * Vista de Daily Scrum
     */
    public function dailyScrum(Proyecto $proyecto)
    {
        $this->verificarMetodologiaScrum($proyecto);
        $this->verificarPermisos($proyecto);

        $sprintActual = request('sprint', $this->obtenerSprintActual($proyecto));

        // Tareas del sprint actual agrupadas por miembro
        $tareasDelSprint = TareaProyecto::where('id_proyecto', $proyecto->id)
            ->where('sprint', $sprintActual)
            ->with(['responsableUsuario', 'fase'])
            ->get()
            ->groupBy('responsable');

        // Miembros del equipo
        $miembrosEquipo = collect();
        foreach ($proyecto->equipos as $equipo) {
            $miembrosEquipo = $miembrosEquipo->merge($equipo->miembros);
        }
        $miembrosEquipo = $miembrosEquipo->unique('id');

        return view('gestionProyectos.scrum.daily-scrum', compact(
            'proyecto',
            'sprintActual',
            'tareasDelSprint',
            'miembrosEquipo'
        ));
    }

    /**
     * Vista de Sprint Review
     */
    public function sprintReview(Proyecto $proyecto)
    {
        $this->verificarMetodologiaScrum($proyecto);
        $this->verificarPermisos($proyecto);

        $sprintActual = request('sprint', $this->obtenerSprintActual($proyecto));

        $tareasDelSprint = TareaProyecto::where('id_proyecto', $proyecto->id)
            ->where('sprint', $sprintActual)
            ->with(['fase', 'elementoConfiguracion', 'responsableUsuario'])
            ->get();

        // Métricas del sprint
        $totalTareas = $tareasDelSprint->count();
        $tareasCompletadas = $tareasDelSprint->where('estado', 'Completado')->count();
        $totalStoryPoints = $tareasDelSprint->sum('story_points');
        $storyPointsCompletados = $tareasDelSprint->where('estado', 'Completado')->sum('story_points');

        return view('gestionProyectos.scrum.sprint-review', compact(
            'proyecto',
            'sprintActual',
            'tareasDelSprint',
            'totalTareas',
            'tareasCompletadas',
            'totalStoryPoints',
            'storyPointsCompletados'
        ));
    }

    /**
     * Vista de Sprint Retrospective
     */
    public function sprintRetrospective(Proyecto $proyecto)
    {
        $this->verificarMetodologiaScrum($proyecto);
        $this->verificarPermisos($proyecto);

        $sprintActual = request('sprint', $this->obtenerSprintActual($proyecto));

        return view('gestionProyectos.scrum.sprint-retrospective', compact(
            'proyecto',
            'sprintActual'
        ));
    }

    /**
     * Obtener el sprint actual del proyecto
     */
    private function obtenerSprintActual(Proyecto $proyecto)
    {
        $ultimoSprint = TareaProyecto::where('id_proyecto', $proyecto->id)
            ->whereNotNull('sprint')
            ->orderBy('sprint', 'desc')
            ->value('sprint');

        return $ultimoSprint ?? 'Sprint 1';
    }

    /**
     * Calcular datos para burndown chart
     */
    private function calcularBurndown($tareasSprintActual)
    {
        $totalStoryPoints = $tareasSprintActual->sum('story_points');
        $dias = 14; // Sprint de 2 semanas

        // Datos simulados para el burndown chart
        $burndownData = [];
        for ($dia = 0; $dia <= $dias; $dia++) {
            $progreso = ($dia / $dias) * 0.8; // 80% de avance ideal
            $storyPointsRestantes = max(0, $totalStoryPoints * (1 - $progreso));
            $burndownData[] = [
                'dia' => $dia,
                'ideal' => max(0, $totalStoryPoints - ($totalStoryPoints / $dias * $dia)),
                'actual' => $storyPointsRestantes
            ];
        }

        return $burndownData;
    }
}
