<?php
namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Project\Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use Modules\Project\Models\Client;
use Modules\Project\Models\ProjectPhase;
use Modules\Project\Models\Invoice;
use Modules\Project\Models\DeveloperProjectEnrollment;
use Modules\Task\Models\Task;
use Modules\Task\Models\DeveloperTaskAssignment;
use Modules\Task\Models\TaskAssignmentMessage;



/*
class Project extends Model
{
    use HasFactory;

    //Create a new factory instance for the model.
    protected static function newFactory()
    {
        return ProjectFactory::new();
    }
}
*/

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'start_at',
        'planned_to_delivery_at',
        'actual_delivery_at',
        'deadline',
        'currency',
        'estimated_cost',
        'revenue',
        'billing_type',
        'payment_status',
        'locality',
        'project_type',
        'project_category',
        'priority',
        'status',
        'progress',
        'documentation',
        'pm_id',
        'client_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',
        'planned_to_delivery_at' => 'datetime',
        'actual_delivery_at' => 'datetime',
        'deadline' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'revenue' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // when using toArray(), toJson(), or returning the model from an API).
    // The frontend usually doesn't need to know about deleted records
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'is_overdue',
        'progress_percentage',
        'budget_variance',
        'formatted_estimated_cost',
        'formatted_revenue',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the project manager (PM) associated with the project.
     */
    public function projectManager()
    {
        return $this->belongsTo(User::class, 'pm_id');
    }

    /**
     * Get the client associated with the project.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the phases for the project.
     */
    public function phases()
    {
        return $this->hasMany(ProjectPhase::class)->orderBy('order', 'asc');
    }

    /**
     * Get the invoices for the project.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    
    /**
     * Get the developer enrollments for the project.
     */
    public function developerEnrollments()
    {
        return $this->hasMany(DeveloperProjectEnrollment::class);
    }

    /**
     * Get the current active developers assigned to the project.
     */
    public function activeDeveloperEnrollments()
    {
        return $this->developerEnrollments()->whereNull('unassigned_date_time');
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include active projects.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'enable');
    }

    /**
     * Scope a query to only include inactive projects.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'disable');
    }

    /**
     * Scope a query to only include projects by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include projects by progress.
     */
    public function scopeByProgress($query, $progress)
    {
        return $query->where('progress', $progress);
    }

    /**
     * Scope a query to only include projects by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('project_type', $type);
    }

    /**
     * Scope a query to only include projects by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include projects by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('project_category', $category);
    }

    /**
     * Scope a query to only include projects with upcoming deadlines.
     */
    public function scopeUpcomingDeadlines($query, $days = 7)
    {
        return $query->where('deadline', '>=', now())
                     ->where('deadline', '<=', now()->addDays($days))
                     ->where('status', 'enable')
                     ->where('progress', '!=', 'completed');
    }

    /**
     * Scope a query to only include overdue projects.
     */
    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
                     ->where('status', 'enable')
                     ->where('progress', '!=', 'completed');
    }

    /**
     * Scope a query to only include projects with completed progress.
     */
    public function scopeCompleted($query)
    {
        return $query->where('progress', 'completed');
    }

    /**
     * Scope a query to only include projects in progress.
     */
    public function scopeInProgress($query)
    {
        return $query->where('progress', 'in_progress');
    }

    /**
     * Scope a query to only include projects by locality.
     */
    public function scopeByLocality($query, $locality)
    {
        return $query->where('locality', $locality);
    }

    /**
     * Scope a query to only include projects by payment status.
     */
    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Scope a query to only include projects by billing type.
     */
    public function scopeByBillingType($query, $billingType)
    {
        return $query->where('billing_type', $billingType);
    }

    /**
     * Scope a query to get projects managed by a specific PM.
     */
    public function scopeManagedBy($query, $pmId)
    {
        return $query->where('pm_id', $pmId);
    }

    /**
     * Scope a query to get projects for a specific client.
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope a query to get projects within a date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_at', [$startDate, $endDate]);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get the project's overdue status.
     */
    public function getIsOverdueAttribute()
    {
        return $this->deadline && $this->deadline->isPast() && $this->progress !== 'completed';
    }

    /**
     * Get the project's progress percentage.
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
     * Get the budget variance (estimated - revenue).
     */
    public function getBudgetVarianceAttribute()
    {
        if ($this->estimated_cost && $this->revenue) {
            return $this->estimated_cost - $this->revenue;
        }
        return null;
    }

    /**
     * Get formatted estimated cost.
     */
    public function getFormattedEstimatedCostAttribute()
    {
        if ($this->estimated_cost) {
            return $this->currency . ' ' . number_format($this->estimated_cost, 2);
        }
        return null;
    }

    /**
     * Get formatted revenue
     */
    public function getFormattedRevenueAttribute()
    {
        if ($this->revenue) {
            return $this->currency . ' ' . number_format($this->revenue, 2);
        }
        return null;
    }

    /**
     * Get the project's status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'enable' => 'success',
            'disable' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get the project's progress badge color.
     */
    public function getProgressBadgeAttribute()
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
     * Get the project's priority badge color.
     */
    public function getPriorityBadgeAttribute()
    {
        $colors = [
            'critical' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            'low' => 'success',
        ];

        return $colors[$this->priority] ?? 'secondary';
    }

    /**
     * Get the total days from start to deadline.
     */
    public function getTotalDaysAttribute()
    {
        if ($this->start_at && $this->deadline) {
            return $this->start_at->diffInDays($this->deadline);
        }
        return null;
    }

    /**
     * Get the days remaining until deadline.
     */
    public function getDaysRemainingAttribute()
    {
        if ($this->deadline) {
            $days = now()->diffInDays($this->deadline, false);
            return $days < 0 ? 0 : $days;
        }
        return null;
    }

    // ==================== CUSTOM METHODS ====================

    /**
     * Check if the project is active.
     */
    public function isActive()
    {
        return $this->status === 'enable';
    }

    /**
     * Check if the project is completed.
     */
    public function isCompleted()
    {
        return $this->progress === 'completed';
    }

    /**
     * Check if the project is in progress.
     */
    public function isInProgress()
    {
        return $this->progress === 'in_progress';
    }

    /**
     * Check if the project is blocked.
     */
    public function isBlocked()
    {
        return $this->progress === 'blocked';
    }

    /**
     * Check if the project is cancelled.
     */
    public function isCancelled()
    {
        return $this->progress === 'cancelled';
    }

    /**
     * Get the developers (User models) currently assigned to the project.
    */
    public function activeDevelopers()
    {
        return $this->belongsToMany(User::class, 'developer_project_enrollments', 'project_id', 'developer_id')
                    ->whereNull('developer_project_enrollments.unassigned_date_time')
                    ->withPivot('assigned_date_time', 'message', 'is_notify')
                    ->withTimestamps();
    }

    /**
     * Get all developers ever assigned to the project (including historical).
     */
    public function allDevelopers()
    {
        return $this->belongsToMany(User::class, 'developer_project_enrollments', 'project_id', 'developer_id')
                    ->withPivot('assigned_date_time', 'unassigned_date_time', 'message', 'is_notify')
                    ->withTimestamps();
    }

    /**
     * Get the current active developers for the project with their enrollment details.
     * REPLACES: currentDevelopers()
     */
    public function currentDevelopers()
    {
        return $this->activeDeveloperEnrollments()->with('developer');
    }

    /**
     * Check if a developer is currently assigned to this project.
     */
    public function hasDeveloper($userId)
    {
        return $this->activeDeveloperEnrollments()
                    ->where('developer_id', $userId)
                    ->exists();
    }

    /**
     * Check if a developer was ever assigned to this project.
     */
    public function hasEverHadDeveloper($userId)
    {
        return $this->developerEnrollments()
                    ->where('developer_id', $userId)
                    ->exists();
    }

    /**
     * Get all developers assigned to this project (as User models).
     */
    public function getDevelopers()
    {
        return $this->activeDeveloperEnrollments()
                    ->with('developer')
                    ->get()
                    ->pluck('developer');
    }

    /**
     * Get developer count for the project.
     */
    public function getDeveloperCountAttribute()
    {
        return $this->activeDeveloperEnrollments()->count();
    }




    /**
     * Get the project's total cost (including all invoices).
     */
    public function getTotalCost()
    {
        return $this->invoices()->where('invoice_type', 'cost')->sum('amount');
    }

    /**
     * Get the project's total income (including all invoices).
     */
    public function getTotalIncome()
    {
        return $this->invoices()->where('invoice_type', 'income')->sum('amount');
    }

    /**
     * Get the project's net profit/loss.
     */
    public function getNetProfit()
    {
        return $this->getTotalIncome() - $this->getTotalCost();
    }

    /**
     * Update project progress based on phases.
     */
    public function updateProgressFromPhases()
    {
        $phases = $this->phases;
        
        if ($phases->isEmpty()) {
            return;
        }

        $totalPhases = $phases->count();
        $completedPhases = $phases->where('progress', 'completed')->count();
        
        if ($completedPhases === 0) {
            $this->progress = 'not_started';
        } elseif ($completedPhases === $totalPhases) {
            $this->progress = 'completed';
        } else {
            $this->progress = 'in_progress';
        }
        
        $this->save();
    }

    /**
     * Calculate project completion percentage based on phases.
     */
    public function calculateCompletionPercentage()
    {
        $phases = $this->phases;
        
        if ($phases->isEmpty()) {
            return 0;
        }

        $totalPhases = $phases->count();
        $completedPhases = $phases->where('progress', 'completed')->count();
        
        return round(($completedPhases / $totalPhases) * 100);
    }

    /**
     * Mark project as completed.
     */
    public function markAsCompleted()
    {
        $this->progress = 'completed';
        $this->actual_delivery_at = $this->actual_delivery_at ?? now();
        $this->save();
    }

    /**
     * Mark project as in progress.
     */
    public function markAsInProgress()
    {
        $this->progress = 'in_progress';
        $this->save();
    }

    /**
     * Get project summary data.
     */
    public function getSummary()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'progress' => $this->progress,
            'progress_percentage' => $this->progress_percentage,
            //'deadline' => $this->deadline?->format('Y-m-d'),
			'deadline' => isset($this->deadline) ? $this->deadline->format('Y-m-d') : null,
            'is_overdue' => $this->is_overdue,
            'estimated_cost' => $this->formatted_estimated_cost,
            'revenue' => $this->formatted_revenue,
            'budget_variance' => $this->budget_variance,
            //'pm' => $this->projectManager?->name,
			'pm' => isset($this->projectManager) ? $this->projectManager->name : null,
            //'client' => $this->client?->name,
			'client' => isset($this->client) ? $this->client->name : null,
            'total_phases' => $this->phases()->count(),
            'completed_phases' => $this->phases()->where('progress', 'completed')->count(),
            'total_developers' => $this->activeDeveloperEnrollments()->count(),
        ];
    }

    /**
     * Get upcoming milestones/tasks for the project.
     */
    public function getUpcomingMilestones()
    {
        return $this->phases()
                    ->where('scheduled_end', '>=', now())
                    ->where('scheduled_end', '<=', now()->addDays(14))
                    ->where('progress', '!=', 'completed')
                    ->get();
    }

    /**
     * Check if project budget is exceeded.
     */
    public function isBudgetExceeded()
    {
        if ($this->estimated_cost && $this->revenue) {
            return $this->revenue > $this->estimated_cost;
        }
        return false;
    }

    /**
     * Get budget utilization percentage.
     */
    public function getBudgetUtilization()
    {
        if ($this->estimated_cost && $this->revenue) {
            return round(($this->revenue / $this->estimated_cost) * 100);
        }
        return 0;
    }

    /**
     * Duplicate the project with all relationships.
     */
    public function duplicate($newName = null)
    {
        $newProject = $this->replicate();
        $newProject->name = $newName ?? $this->name . ' (Copy)';
        $newProject->status = 'disable';
        $newProject->progress = 'not_started';
        $newProject->created_at = now();
        $newProject->updated_at = now();
        $newProject->save();

        // Duplicate phases
        foreach ($this->phases as $phase) {
            $newPhase = $phase->replicate();
            $newPhase->project_id = $newProject->id;
            $newPhase->save();
        }

        return $newProject;
    }






    // ==================== TASK RELATIONSHIPS ====================

    /**
     * Get all tasks for the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all active tasks for the project.
     */
    public function activeTasks()
    {
        return $this->tasks()->enabled();
    }

    /**
     * Get all completed tasks for the project.
     */
    public function completedTasks()
    {
        return $this->tasks()->whereHas('taskAssignments', function ($query) {
            $query->where('progress', 'completed');
        });
    }

    /**
     * Get all overdue tasks for the project.
     */
    public function overdueTasks()
    {
        return $this->tasks()->overdue();
    }

    /**
     * Get task assignments through tasks.
     */
    public function taskAssignments()
    {
        return $this->hasManyThrough(
            DeveloperTaskAssignment::class,
            Task::class,
            'project_id', // Foreign key on tasks table
            'task_id', // Foreign key on developer_task_assignments table
            'id', // Local key on projects table
            'id' // Local key on tasks table
        );
    }

    /**
     * Get task assignment messages through tasks and assignments.
     */
    public function taskAssignmentMessages()
    {
        return $this->hasManyThrough(
            TaskAssignmentMessage::class,
            DeveloperTaskAssignment::class,
            'task_id', // Foreign key on developer_task_assignments
            'task_assignment_id', // Foreign key on task_assignment_messages
            'id', // Local key on projects
            'id' // Local key on developer_task_assignments
        );
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get total task count for the project.
     */
    public function getTotalTasksCountAttribute(): int
    {
        return $this->tasks()->count();
    }

    /**
     * Get completed tasks count for the project.
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->completedTasks()->count();
    }

    /**
     * Get overdue tasks count for the project.
     */
    public function getOverdueTasksCountAttribute(): int
    {
        return $this->overdueTasks()->count();
    }

    /**
     * Get task completion percentage for the project.
     */
    public function getTaskCompletionPercentageAttribute(): float
    {
        $total = $this->tasks()->count();
        if ($total === 0) {
            return 0;
        }
        
        $completed = $this->completedTasks()->count();
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Check if project has any active tasks.
     */
    public function hasActiveTasks(): bool
    {
        return $this->activeTasks()->exists();
    }

    /**
     * Get tasks grouped by priority.
     */
    public function getTasksByPriority()
    {
        return $this->tasks()
            ->select('priority', \DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->get()
            ->pluck('total', 'priority')
            ->toArray();
    }

    /**
     * Get tasks grouped by status.
     */
    public function getTasksByStatus()
    {
        return $this->tasks()
            ->select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();
    }

    /**
     * Get the total estimated time for all tasks in the project.
     */
    public function getTotalEstimatedTimeAttribute(): int
    {
        return $this->tasks()->sum('estimate_time') ?? 0;
    }

    /**
     * Get the total estimated time in hours.
     */
    public function getTotalEstimatedTimeInHoursAttribute(): float
    {
        return $this->getTotalEstimatedTimeAttribute() / 60;
    }
    // ==================== ==================== ====================


}

