<?php
namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Modules\Project\Models\Project;


class ProjectPhase extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_phases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'name',
        'description',
        'progress',
        'scheduled_start',
        'scheduled_end',
        'actual_start',
        'actual_end',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'is_overdue',
        'is_completed',
        'progress_percentage',
        'status_badge',
        'formatted_scheduled_start',
        'formatted_scheduled_end',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the project that owns the phase.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include completed phases.
     */
    public function scopeCompleted($query)
    {
        return $query->where('progress', 'completed');
    }

    /**
     * Scope a query to only include in-progress phases.
     */
    public function scopeInProgress($query)
    {
        return $query->where('progress', 'in_progress');
    }

    /**
     * Scope a query to only include not started phases.
     */
    public function scopeNotStarted($query)
    {
        return $query->where('progress', 'not_started');
    }

    /**
     * Scope a query to only include blocked phases.
     */
    public function scopeBlocked($query)
    {
        return $query->where('progress', 'blocked');
    }

    /**
     * Scope a query to only include cancelled phases.
     */
    public function scopeCancelled($query)
    {
        return $query->where('progress', 'cancelled');
    }

    /**
     * Scope a query to only include overdue phases.
     */
    public function scopeOverdue($query)
    {
        return $query->where('scheduled_end', '<', now())
                     ->where('progress', '!=', 'completed')
                     ->where('progress', '!=', 'cancelled');
    }

    /**
     * Scope a query to sort by order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Scope a query to filter by progress status.
     */
    public function scopeByProgress($query, $progress)
    {
        return $query->where('progress', $progress);
    }

    /**
     * Scope a query to get phases within a date range.
     */
    public function scopeScheduledBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('scheduled_start', [$startDate, $endDate])
                     ->orWhereBetween('scheduled_end', [$startDate, $endDate]);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Check if the phase is overdue.
     */
    public function getIsOverdueAttribute()
    {
        if (!$this->scheduled_end) {
            return false;
        }
        return $this->scheduled_end->isPast() && 
               $this->progress !== 'completed' && 
               $this->progress !== 'cancelled';
    }

    /**
     * Check if the phase is completed.
     */
    public function getIsCompletedAttribute()
    {
        return $this->progress === 'completed';
    }

    /**
     * Get progress percentage.
     */
    public function getProgressPercentageAttribute()
    {
        $progressMap = [
            'not_started' => 0,
            'in_progress' => 50,
            'completed' => 100,
            'blocked' => 25,
            'cancelled' => 0,
        ];

        return $progressMap[$this->progress] ?? 0;
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'not_started' => 'secondary',
            'in_progress' => 'primary',
            'completed' => 'success',
            'blocked' => 'danger',
            'cancelled' => 'warning',
        ];

        return $colors[$this->progress] ?? 'secondary';
    }

    /**
     * Get formatted scheduled start.
     */
    public function getFormattedScheduledStartAttribute()
    {
        //return $this->scheduled_start?->format('Y-m-d H:i');
		return isset($this->scheduled_start) ? $this->scheduled_start->format('Y-m-d H:i') : null;
    }

    /**
     * Get formatted scheduled end.
     */
    public function getFormattedScheduledEndAttribute()
    {
        //return $this->scheduled_end?->format('Y-m-d H:i');
		return isset($this->scheduled_end) ? $this->scheduled_end->format('Y-m-d H:i') : null;
    }

    /**
     * Get formatted actual start.
     */
    public function getFormattedActualStartAttribute()
    {
        //return $this->actual_start?->format('Y-m-d H:i');
		return isset($this->scheduled_start) ? $this->scheduled_start->format('Y-m-d H:i') : null;
    }

    /**
     * Get formatted actual end.
     */
    public function getFormattedActualEndAttribute()
    {
        //return $this->actual_end?->format('Y-m-d H:i');
		return isset($this->actual_end) ? $this->actual_end->format('Y-m-d H:i') : null;

    }

    /**
     * Get human-readable progress label.
     */
    public function getProgressLabelAttribute()
    {
        $labels = [
            'not_started' => 'Not Started',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'blocked' => 'Blocked',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$this->progress] ?? ucfirst($this->progress);
    }

    /**
     * Get duration in days (scheduled).
     */
    public function getScheduledDurationAttribute()
    {
        if ($this->scheduled_start && $this->scheduled_end) {
            return $this->scheduled_start->diffInDays($this->scheduled_end);
        }
        return null;
    }

    /**
     * Get duration in days (actual).
     */
    public function getActualDurationAttribute()
    {
        if ($this->actual_start && $this->actual_end) {
            return $this->actual_start->diffInDays($this->actual_end);
        }
        return null;
    }

    // ==================== CUSTOM METHODS ====================

    /**
     * Mark the phase as completed.
     */
    public function markAsCompleted()
    {
        $this->progress = 'completed';
        $this->actual_end = $this->actual_end ?? now();
        $this->save();
        
        // Update project progress
        $this->project->updateProgressFromPhases();
    }

    /**
     * Mark the phase as in progress.
     */
    public function markAsInProgress()
    {
        $this->progress = 'in_progress';
        $this->actual_start = $this->actual_start ?? now();
        $this->save();
        
        // Update project progress
        $this->project->updateProgressFromPhases();
    }

    /**
     * Mark the phase as blocked.
     */
    public function markAsBlocked()
    {
        $this->progress = 'blocked';
        $this->save();
    }

    /**
     * Mark the phase as not started.
     */
    public function markAsNotStarted()
    {
        $this->progress = 'not_started';
        $this->save();
    }

    /**
     * Mark the phase as cancelled.
     */
    public function markAsCancelled()
    {
        $this->progress = 'cancelled';
        $this->save();
    }

    /**
     * Check if phase is active (not completed or cancelled).
     */
    public function isActive()
    {
        return !in_array($this->progress, ['completed', 'cancelled']);
    }

    /**
     * Get phase summary.
     */
    public function getSummary()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'progress' => $this->progress_label,
            'progress_percentage' => $this->progress_percentage,
            'status_badge' => $this->status_badge,
            'scheduled_start' => $this->formatted_scheduled_start,
            'scheduled_end' => $this->formatted_scheduled_end,
            'actual_start' => $this->formatted_actual_start,
            'actual_end' => $this->formatted_actual_end,
            'is_overdue' => $this->is_overdue,
            'is_completed' => $this->is_completed,
            'scheduled_duration_days' => $this->scheduled_duration,
            'actual_duration_days' => $this->actual_duration,
            'order' => $this->order,
        ];
    }

    /**
     * Calculate delay in days.
     */
    public function getDelayDays()
    {
        if ($this->is_overdue) {
            return $this->scheduled_end->diffInDays(now());
        }
        return 0;
    }

    /**
     * Check if phase has actual start date.
     */
    public function hasActualStart()
    {
        return !is_null($this->actual_start);
    }

    /**
     * Check if phase has actual end date.
     */
    public function hasActualEnd()
    {
        return !is_null($this->actual_end);
    }

    /**
     * Get phase completion status for project.
     */
    public static function getProjectCompletionStats($projectId)
    {
        $phases = self::where('project_id', $projectId)->get();
        
        return [
            'total' => $phases->count(),
            'completed' => $phases->where('progress', 'completed')->count(),
            'in_progress' => $phases->where('progress', 'in_progress')->count(),
            'not_started' => $phases->where('progress', 'not_started')->count(),
            'blocked' => $phases->where('progress', 'blocked')->count(),
            'cancelled' => $phases->where('progress', 'cancelled')->count(),
            'overdue' => $phases->filter->is_overdue->count(),
            'completion_percentage' => $phases->isEmpty() ? 0 : 
                round(($phases->where('progress', 'completed')->count() / $phases->count()) * 100),
        ];
    }

    /**
     * Move phase order up.
     */
    public function moveUp()
    {
        $this->order = $this->order - 1;
        $this->save();
    }

    /**
     * Move phase order down.
     */
    public function moveDown()
    {
        $this->order = $this->order + 1;
        $this->save();
    }
}