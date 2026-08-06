<?php
namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\Task\Models\Task;
use App\Models\User;
use Modules\Project\Models\DeveloperProjectEnrollment;
use Modules\Communicate\Models\TaskAssignmentMessage;
use Modules\Timesheet\Models\TimesheetEntry;




class DeveloperTaskAssignment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'developer_project_enrollment_id',
        'is_notify',
        'assigned_date_time',
        'finished_date_time',
		'stopped_date_time',
        'spend_time',
        'progress',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_notify' => 'boolean',
        'assigned_date_time' => 'datetime',
        'finished_date_time' => 'datetime',        
		'stopped_date_time' => 'datetime',
        'spend_time' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'spent_time_in_hours',
        'spent_time_formatted',
    ];


    /**
     * Get the progress label.
     */
    public function getProgressLabelAttribute(): string
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
     * Get the progress color for UI.
     */
    public function getProgressColorAttribute(): string
	{
		switch($this->progress) {
			case 'not_started': return 'secondary';
			case 'in_progress': return 'primary';
			case 'completed':   return 'success';
			case 'blocked':     return 'danger';
			case 'cancelled':   return 'warning';
			default:            return 'secondary';
		}
	}

    /**
     * Get spend time in hours.
     */
    public function getSpendTimeInHoursAttribute(): float
    {
        return $this->spend_time / 60;
    }

    /**
     * Get spend time formatted as hours and minutes.
     */
    public function getSpendTimeFormattedAttribute(): string
    {
        if (!$this->spend_time) {
            return '0h 0m';
        }
        
        $hours = floor($this->spend_time / 60);
        $minutes = $this->spend_time % 60;
        
        return "{$hours}h {$minutes}m";
    }

    /**
     * Check if assignment is active (not completed or cancelled).
     */
    public function isActive(): bool
    {
        return !in_array($this->progress, ['completed', 'cancelled']);
    }

    /**
     * Check if assignment is overdue.
     */
    public function isOverdue(): bool
    {
        $task = $this->task;
        return $task && $task->deadline < now() && $this->isActive();
    }

    /**
     * Get spent time in hours.
     */
    public function getSpentTimeInHoursAttribute(): float
    {
        return round($this->spend_time / 60, 2);
    }

    /**
     * Get spent time formatted (e.g., 1h 30m).
     */
    public function getSpentTimeFormattedAttribute(): string
    {
        $hours = floor($this->spend_time / 60);
        $minutes = $this->spend_time % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }

    // ==================== RELATIONSHIPS ====================


    /**
     * Get the task for this assignment.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the developer project enrollment for this assignment.
     */
    public function developerProjectEnrollment()
    {
        return $this->belongsTo(DeveloperProjectEnrollment::class);
    }

    /**
     * Get the user (developer) through enrollment.
     */
    public function developer()
    {
        return $this->hasOneThrough(
            User::class,
            DeveloperProjectEnrollment::class,
            'id', // Foreign key on developer_project_enrollments
            'id', // Foreign key on users
            'developer_project_enrollment_id', // Local key on developer_task_assignments
            'developer_id' // Local key on developer_project_enrollments
        );
    }

    /**
     * Get the messages for this assignment.
     */
    public function messages()
    {
        return $this->hasMany(TaskAssignmentMessage::class);
    }

    /**
     * Get the timesheet entries for this assignment.
     */
    public function timesheetEntries()
    {
        return $this->hasMany(TimesheetEntry::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include assignments with specific progress.
     */
    public function scopeProgress($query, string $progress)
    {
        return $query->where('progress', $progress);
    }

    /**
     * Scope a query to only include active assignments.
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('progress', ['completed', 'cancelled']);
    }

    /**
     * Scope a query to only include completed assignments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('progress', 'completed');
    }

    /**
     * Scope a query to only include assignments for a specific task.
     */
    public function scopeForTask($query, int $taskId)
    {
        return $query->where('task_id', $taskId);
    }

    /**
     * Scope a query to only include assignments for a specific developer.
     */
    public function scopeForDeveloper($query, int $developerId)
    {
        return $query->whereHas('developerProjectEnrollment', function ($q) use ($developerId) {
            $q->where('developer_id', $developerId);
        });
    }

    // ==================== HELPER METHODS ====================

    /**
     * Start the assignment (mark as in_progress).
     */
    public function start(): void
    {
        if ($this->progress === 'not_started') {
            $this->update([
                'progress' => 'in_progress',
                'assigned_date_time' => now(),
            ]);
        }
    }

    /**
     * Complete the assignment.
     */
    public function complete(): void
    {
        $this->update([
            'progress' => 'completed',
            'finished_date_time' => now(),
        ]);
    }

    /**
     * Block the assignment.
     */
    public function block(string $reason = null): void
    {
        $this->update([
            'progress' => 'blocked',
        ]);
    }

    /**
     * Cancel the assignment.
     */
    public function cancel(): void
    {
        $this->update([
            'progress' => 'cancelled',
        ]);
    }

    /**
     * Add spend time to the assignment.
     */
    public function addSpendTime(int $minutes): void
    {
        $this->increment('spend_time', $minutes);
    }

    /**
     * Get total time spent on this assignment including timesheet entries.
     */
    public function getTotalSpendTimeAttribute(): int
    {
        $timesheetTotal = $this->timesheetEntries()->sum('spend_time') ?? 0;
        return ($this->spend_time ?? 0) + $timesheetTotal;
    }
}