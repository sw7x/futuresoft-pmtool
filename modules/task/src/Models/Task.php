<?php
namespace Modules\Task\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;


use Modules\Project\Models\Project;
use Modules\Task\Models\DeveloperTaskAssignment;
use Modules\Project\Models\DeveloperProjectEnrollment;
use App\Models\User;

use Modules\Communicate\Models\TaskThread;
use Modules\Communicate\Models\TaskThreadMessage;







class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'estimate_time',
        'deadline',
        'priority',
        'status',
        'parent_task_id',
        'project_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'datetime',
        'estimate_time' => 'integer',
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
        'estimate_time_in_hours',
        'estimate_time_formatted',
    ];

	/**
     * Boot method to register model events
     */
    protected static function boot()
    {
        parent::boot();

        // Validate before creating
        static::creating(function ($task) {
            $task->validateDepth();
        });

        // Validate before updating
        static::updating(function ($task) {
            $task->validateDepth();
        });

        // Business rule: whatever happens to a parent task also happens to
        // its subtasks - soft delete cascades to soft delete, and a
        // permanent (force) delete cascades to a permanent delete too.
        static::deleting(function ($task) {
            $task->childTasks()->get()->each(function ($child) use ($task) {
                if ($task->isForceDeleting()) {
                    $child->forceDelete();
                } else {
                    $child->delete();
                }
            });
        });

    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Get the priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        return ucfirst($this->priority);
    }

    /**
     * Get the priority color for UI.
     */
    public function getPriorityColorAttribute(): string
	{
		switch($this->priority) {
			case 'critical': return 'danger';
			case 'high':     return 'warning';
			case 'medium':   return 'info';
			case 'low':      return 'success';
			default:         return 'secondary';
		}
	}

    /**
     * Get estimate time in hours.
     */
    public function getEstimateTimeInHoursAttribute(): float
    {
        return $this->estimate_time / 60;
    }

    /**
     * Set estimate time from hours.
     */
    public function setEstimateTimeInHoursAttribute(float $hours): void
    {
        $this->attributes['estimate_time'] = $hours * 60;
    }

    /**
     * Get estimate time formatted (e.g., 1h 30m).
     */
    public function getEstimateTimeFormattedAttribute(): string
    {
        $hours = floor($this->estimate_time / 60);
        $minutes = $this->estimate_time % 60;
        
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
     * Get the parent task.
     */
    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    /**
     * Get the child tasks.
     */
    public function childTasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    /**
     * Get the project that owns the task.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the task assignments for this task.
     */
    public function taskAssignments()
    {
        return $this->hasMany(DeveloperTaskAssignment::class);
    }

    /**
     * Get the developers assigned to this task.
     */
    public function developers()
    {
        return $this->belongsToMany(
            User::class,
            'developer_task_assignments',
            'task_id',
            'developer_project_enrollment_id',
            'id',
            'id'
        )->withPivot(['progress', 'assigned_date_time', 'finished_date_time', 'spend_time']);
    }

    /**
     * Get the task threads for this task.
     */
    public function taskThreads()
    {
        return $this->hasMany(TaskThread::class);
    }

    /**
     * Get the latest task thread for this task.
     */
    public function latestTaskThread()
    {
        return $this->hasOne(TaskThread::class)->latest();
    }

    /**
     * Get active task threads for this task.
     */
    public function activeTaskThreads()
    {
        return $this->hasMany(TaskThread::class)->where('status', TaskThread::STATUS_ENABLE);
    }



    // ==================== DEVELOPER PROJECT ENROLLMENT RELATIONSHIP ====================
    /**
     * Get the developer project enrollments for this task through assignments.
     */
    /*
    public function developerProjectEnrollments()
    {
        return $this->hasManyThrough(
            DeveloperProjectEnrollment::class,
            DeveloperTaskAssignment::class,
            'task_id', // Foreign key on developer_task_assignments
            'id', // Foreign key on developer_project_enrollments
            'id', // Local key on tasks
            'developer_project_enrollment_id' // Local key on developer_task_assignments
        );
    }
    */


    /**
     * Get the developers (users) assigned to this task.
     */
    // duplicate of  developers()
    /*
    public function assignedDevelopers()
    {
        return $this->hasManyThrough(
            User::class,
            DeveloperTaskAssignment::class,
            'task_id', // Foreign key on developer_task_assignments
            'id', // Foreign key on users
            'id', // Local key on tasks
            'developer_project_enrollment_id' // Local key on developer_task_assignments
        );
    }*/

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include enabled tasks.
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', 'enable');
    }

    /**
     * Scope a query to only include disabled tasks.
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', 'disable');
    }

    /**
     * Scope a query to only include root tasks (no parent).
     */
    public function scopeRootTasks($query)
    {
        return $query->whereNull('parent_task_id');
    }

    /**
     * Scope a query to only include tasks with specific priority.
     */
    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include tasks with upcoming deadline.
     */
    public function scopeUpcomingDeadline($query, int $days = 7)
    {
        return $query->whereBetween('deadline', [now(), now()->addDays($days)]);
    }

    /**
     * Scope a query to only include overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())->enabled();
    }

    /**
     * Scope a query to only include tasks for a specific project.
     */
    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if task has subtasks.
     */
    public function hasSubTasks(): bool
    {
        return $this->childTasks()->exists();
    }

    /**
     * Get all descendant task IDs (recursive).
     */
    public function getDescendantTaskIds(): array
    {
        $ids = [];
        foreach ($this->childTasks as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getDescendantTaskIds());
        }
        return $ids;
    }

    /**
     * Check if task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->deadline !== null
            && $this->deadline->isPast()
            && $this->status === 'enable';
    }

    /**
     * Check if task is completed.
     */
    public function isCompleted(): bool
    {
        return $this->taskAssignments()
            ->where('progress', 'completed')
            ->exists();
    }

    /**
     * Get the completion percentage based on subtasks or assignments.
     */
    public function getCompletionPercentage(): float
    {
        $totalAssignments = $this->taskAssignments()->count();
        
        if ($totalAssignments === 0) {
            return 0;
        }

        $completedAssignments = $this->taskAssignments()
            ->where('progress', 'completed')
            ->count();

        return ($completedAssignments / $totalAssignments) * 100;
    }

    /**
     * Get the total number of threads for this task.
     */
    public function getTaskThreadsCountAttribute(): int
    {
        return $this->taskThreads()->count();
    }

    /**
     * Create a new thread for this task.
     */
    public function createTaskThread(string $title, string $message, int $postedBy)
    {
        $thread = $this->taskThreads()->create([
            'title' => $title,
            'status' => TaskThread::STATUS_ENABLE,
            'posted_by' => $postedBy
        ]);

        $thread->messages()->create([
            'message' => $message,
            'posted_by' => $postedBy,
            'posted_date_time' => now()
        ]);

        return $thread;
    }

    /**
     * Check if the task has any threads.
     */
    public function hasThreads(): bool
    {
        return $this->taskThreads()->exists();
    }

    /**
     * Get all messages for this task.
     */
    public function getTaskMessages()
    {
        return $this->hasManyThrough(
            TaskThreadMessage::class,
            TaskThread::class,
            'task_id',
            'task_thread_id',
            'id',
            'id'
        );
    }



	/**
     * Validate that the task doesn't exceed maximum depth
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateDepth()
    {
        // If this task has a parent
        if ($this->parent_task_id) {
            $parent = self::find($this->parent_task_id);
            
            if ($parent && $parent->parent_task_id) {
                throw ValidationException::withMessages([
                    'parent_task_id' => 'Maximum hierarchy depth is 1. Parent task cannot have its own parent.'
                ]);
            }
        }

        // If this task is being updated and has children, prevent making it a child
        if ($this->exists && $this->isDirty('parent_task_id')) {
            $hasChildren = self::where('parent_task_id', $this->id)->exists();
            
            if ($hasChildren && $this->parent_task_id !== null) {
                throw ValidationException::withMessages([
                    'parent_task_id' => 'Cannot assign a parent to a task that already has children.'
                ]);
            }
        }
    }

    /**
     * Check if a task can be assigned as parent
     */
    public function canBeParent(): bool
    {
        return is_null($this->parent_task_id);
    }

    /**
     * Check if a task can have children
     */
    public function canHaveChildren(): bool
    {
        return is_null($this->parent_task_id);
    }

}



