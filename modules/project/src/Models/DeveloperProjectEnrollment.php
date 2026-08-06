<?php
namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use App\Models\User;
use Modules\Project\Models\Project;
use Modules\Task\Models\DeveloperTaskAssignment;
use Modules\Task\Models\Task;
use Modules\Task\Models\TaskAssignmentMessage;



class DeveloperProjectEnrollment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'developer_project_enrollments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'developer_id',
        'is_notify',
        'assigned_date_time',
        'unassigned_date_time',
        'message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_notify' => 'boolean',
        'assigned_date_time' => 'datetime',
        'unassigned_date_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'is_currently_assigned',
        'assignment_duration_days',
        'status_label',
        'status_badge',
        'formatted_assigned_date',
        'formatted_unassigned_date',
        'developer_name',
        'project_name',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the project for the enrollment.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the developer (user) for the enrollment.
     */
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include active assignments (not unassigned).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('unassigned_date_time');
    }

    /**
     * Scope a query to only include unassigned (historical) assignments.
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNotNull('unassigned_date_time');
    }

    /**
     * Scope a query to include assignments with notification enabled.
     */
    public function scopeWithNotification($query)
    {
        return $query->where('is_notify', true);
    }

    /**
     * Scope a query to include assignments without notification.
     */
    public function scopeWithoutNotification($query)
    {
        return $query->where('is_notify', false);
    }

    /**
     * Scope a query to filter by developer.
     */
    public function scopeForDeveloper($query, $developerId)
    {
        return $query->where('developer_id', $developerId);
    }

    /**
     * Scope a query to filter by project.
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to filter by date range (assigned date).
     */
    public function scopeAssignedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('assigned_date_time', [$startDate, $endDate]);
    }

    /**
     * Scope a query to get assignments assigned today.
     */
    public function scopeAssignedToday($query)
    {
        return $query->whereDate('assigned_date_time', today());
    }

    /**
     * Scope a query to get assignments assigned this week.
     */
    public function scopeAssignedThisWeek($query)
    {
        return $query->whereBetween('assigned_date_time', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope a query to get assignments assigned this month.
     */
    public function scopeAssignedThisMonth($query)
    {
        return $query->whereMonth('assigned_date_time', now()->month)
                    ->whereYear('assigned_date_time', now()->year);
    }

    /**
     * Scope a query to get recent assignments.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('assigned_date_time', '>=', now()->subDays($days));
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Check if the developer is currently assigned.
     */
    public function getIsCurrentlyAssignedAttribute()
    {
        return is_null($this->unassigned_date_time);
    }

    /**
     * Get the assignment duration in days.
     */
    public function getAssignmentDurationDaysAttribute()
    {
        $endDate = $this->unassigned_date_time ?? now();
        return $this->assigned_date_time->diffInDays($endDate);
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        if (is_null($this->unassigned_date_time)) {
            return 'Active';
        }
        return 'Completed';
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        if (is_null($this->unassigned_date_time)) {
            return 'success';
        }
        return 'secondary';
    }

    /**
     * Get formatted assigned date.
     */
    public function getFormattedAssignedDateAttribute()
    {
        return $this->assigned_date_time->format('Y-m-d H:i:s');
    }

    /**
     * Get formatted assigned date (short format).
     */
    public function getFormattedAssignedDateShortAttribute()
    {
        return $this->assigned_date_time->format('M d, Y');
    }

    /**
     * Get formatted assigned time.
     */
    public function getFormattedAssignedTimeAttribute()
    {
        return $this->assigned_date_time->format('h:i A');
    }

    /**
     * Get formatted unassigned date.
     */
    public function getFormattedUnassignedDateAttribute()
    {
        if ($this->unassigned_date_time) {
            return $this->unassigned_date_time->format('Y-m-d H:i:s');
        }
        return null;
    }

    /**
     * Get formatted unassigned date (short format).
     */
    public function getFormattedUnassignedDateShortAttribute()
    {
        if ($this->unassigned_date_time) {
            return $this->unassigned_date_time->format('M d, Y');
        }
        return null;
    }

    /**
     * Get developer name helper.
     */
    public function getDeveloperNameAttribute()
    {
        return isset($this->developer) ? $this->developer->name : 'Unknown Developer';
		//return $this->developer?->name ?? 'Unknown Developer';

    }

    /**
     * Get developer email helper.
     */
    public function getDeveloperEmailAttribute()
    {
        return isset($this->developer) ? $this->developer->email : 'N/A';
		//return $this->developer?->email ?? 'N/A';
    }

    /**
     * Get project name helper.
     */
    public function getProjectNameAttribute()
    {
        return isset($this->project) ? $this->project->project_name : 'Unknown Project';
		//return $this->project?->project_name ?? 'Unknown Project';
    }

    /**
     * Get the assignment duration in hours.
     */
    public function getAssignmentDurationHoursAttribute()
    {
        $endDate = $this->unassigned_date_time ?? now();
        return $this->assigned_date_time->diffInHours($endDate);
    }

    /**
     * Get the assignment duration in minutes.
     */
    public function getAssignmentDurationMinutesAttribute()
    {
        $endDate = $this->unassigned_date_time ?? now();
        return $this->assigned_date_time->diffInMinutes($endDate);
    }

    /**
     * Get human-readable assignment duration.
     */
    public function getAssignmentDurationHumanAttribute()
    {
        $endDate = $this->unassigned_date_time ?? now();
        return $this->assigned_date_time->diffForHumans($endDate, true);
    }

    /**
     * Get notification status label.
     */
    public function getNotificationStatusAttribute()
    {
        return $this->is_notify ? 'Notified' : 'Not Notified';
    }

    /**
     * Get notification badge color.
     */
    public function getNotificationBadgeAttribute()
    {
        return $this->is_notify ? 'primary' : 'secondary';
    }

    // ==================== CUSTOM METHODS ====================

    /**
     * Unassign the developer from the project.
     */
    public function unassign($message = null)
    {
        $this->unassigned_date_time = now();
        if ($message) {
            $this->message = $message;
        }
        $this->save();
        
        return $this;
    }

    /**
     * Reassign the developer to the project.
     */
    public function reassign()
    {
        $this->unassigned_date_time = null;
        $this->assigned_date_time = now();
        $this->save();
        
        return $this;
    }

    /**
     * Check if the developer is currently assigned.
     */
    public function isActive()
    {
        return $this->is_currently_assigned;
    }

    /**
     * Check if the developer has been unassigned.
     */
    public function isUnassigned()
    {
        return !$this->is_currently_assigned;
    }

    /**
     * Send notification to developer.
     */
    public function sendNotification()
    {
        if ($this->is_notify) {
            // Notification logic here
            // Example: Notification::send($this->developer, new DeveloperAssignedNotification($this));
            return true;
        }
        return false;
    }

    /**
     * Enable notification for this assignment.
     */
    public function enableNotification()
    {
        $this->is_notify = true;
        $this->save();
        
        return $this;
    }

    /**
     * Disable notification for this assignment.
     */
    public function disableNotification()
    {
        $this->is_notify = false;
        $this->save();
        
        return $this;
    }

    /**
     * Update message for this assignment.
     */
    public function updateMessage($message)
    {
        $this->message = $message;
        $this->save();
        
        return $this;
    }

    /**
     * Get assignment summary.
     */
    public function getSummary()
    {
        return [
            'id' => $this->id,
            'project' => [
                'id' => $this->project_id,
                'name' => $this->project_name,
            ],
            'developer' => [
                'id' => $this->developer_id,
                'name' => $this->developer_name,
                'email' => $this->developer_email,
            ],
            'assigned_date' => $this->formatted_assigned_date,
            'assigned_date_short' => $this->formatted_assigned_date_short,
            'assigned_time' => $this->formatted_assigned_time,
            'unassigned_date' => $this->formatted_unassigned_date,
            'unassigned_date_short' => $this->formatted_unassigned_date_short,
            'is_active' => $this->is_currently_assigned,
            'status' => $this->status_label,
            'status_badge' => $this->status_badge,
            'duration_days' => $this->assignment_duration_days,
            'duration_hours' => $this->assignment_duration_hours,
            'duration_human' => $this->assignment_duration_human,
            'is_notify' => $this->is_notify,
            'notification_status' => $this->notification_status,
            'notification_badge' => $this->notification_badge,
            'message' => $this->message,
        ];
    }

    /**
     * Get detailed assignment information.
     */
    public function getDetails()
    {
        return [
            'id' => $this->id,
            'project_name' => $this->project_name,
            'developer_name' => $this->developer_name,
            'developer_email' => $this->developer_email,
            'assigned_date_time' => $this->formatted_assigned_date,
            'unassigned_date_time' => $this->formatted_unassigned_date,
            'is_active' => $this->is_currently_assigned,
            'status' => $this->status_label,
            'duration' => $this->assignment_duration_human,
            'message' => $this->message,
            'notification_enabled' => $this->is_notify,
        ];
    }

    /**
     * Get all active assignments for a developer.
     */
    public static function getActiveAssignmentsForDeveloper($developerId)
    {
        return self::forDeveloper($developerId)->active()->get();
    }

    /**
     * Get all active developers for a project.
     */
    public static function getActiveDevelopersForProject($projectId)
    {
        return self::forProject($projectId)->active()->with('developer')->get();
    }

    /**
     * Check if developer is already assigned to project.
     */
    public static function isDeveloperAssignedToProject($developerId, $projectId)
    {
        return self::forDeveloper($developerId)
                   ->forProject($projectId)
                   ->active()
                   ->exists();
    }

    /**
     * Get the number of active assignments for a developer.
     */
    public static function getActiveAssignmentCountForDeveloper($developerId)
    {
        return self::forDeveloper($developerId)->active()->count();
    }

    /**
     * Get the number of active developers for a project.
     */
    public static function getActiveDeveloperCountForProject($projectId)
    {
        return self::forProject($projectId)->active()->count();
    }

    /**
     * Bulk unassign developers from a project.
     */
    public static function bulkUnassignFromProject($projectId, $developerIds = null, $message = null)
    {
        $query = self::forProject($projectId)->active();
        
        if ($developerIds) {
            $query->whereIn('developer_id', $developerIds);
        }
        
        $assignments = $query->get();
        
        foreach ($assignments as $assignment) {
            $assignment->unassign($message);
        }
        
        return $assignments;
    }

    /**
     * Get assignment statistics for a project.
     */
    public static function getProjectStatistics($projectId)
    {
        $total = self::forProject($projectId)->count();
        $active = self::forProject($projectId)->active()->count();
        $unassigned = self::forProject($projectId)->unassigned()->count();
        $withNotification = self::forProject($projectId)->withNotification()->count();
        
        return [
            'total_assignments' => $total,
            'active_assignments' => $active,
            'unassigned_assignments' => $unassigned,
            'with_notification' => $withNotification,
            'without_notification' => $total - $withNotification,
        ];
    }

    /**
     * Get assignment statistics for a developer.
     */
    public static function getDeveloperStatistics($developerId)
    {
        $total = self::forDeveloper($developerId)->count();
        $active = self::forDeveloper($developerId)->active()->count();
        $unassigned = self::forDeveloper($developerId)->unassigned()->count();
        
        return [
            'total_assignments' => $total,
            'active_assignments' => $active,
            'unassigned_assignments' => $unassigned,
            'projects' => self::forDeveloper($developerId)->active()->with('project')->get()->pluck('project.project_name'),
        ];
    }

    /**
     * Check if assignment is recent (within given days).
     */
    public function isRecent($days = 7)
    {
        return $this->assigned_date_time->diffInDays(now()) <= $days;
    }

    /**
     * Get the week number of assignment.
     */
    public function getAssignmentWeekAttribute()
    {
        return $this->assigned_date_time->weekOfYear;
    }

    /**
     * Get the month of assignment.
     */
    public function getAssignmentMonthAttribute()
    {
        return $this->assigned_date_time->format('F Y');
    }

    /**
     * Get the year of assignment.
     */
    public function getAssignmentYearAttribute()
    {
        return $this->assigned_date_time->year;
    }













    // ==================== TASK ASSIGNMENT RELATIONSHIPS ====================

    /**
     * Get all task assignments for this enrollment.
     */
    public function taskAssignments()
    {
        return $this->hasMany(DeveloperTaskAssignment::class);
    }

    /**
     * Get all tasks assigned to this enrollment.
     */
    public function tasks()
    {
        return $this->hasManyThrough(
            Task::class,
            DeveloperTaskAssignment::class,
            'developer_project_enrollment_id', // Foreign key on developer_task_assignments
            'id', // Foreign key on tasks
            'id', // Local key on developer_project_enrollments
            'task_id' // Local key on developer_task_assignments
        );
    }

    /**
     * Get active task assignments (not completed or cancelled).
     */
    public function activeTaskAssignments()
    {
        return $this->taskAssignments()->active();
    }

    /**
     * Get completed task assignments.
     */
    public function completedTaskAssignments()
    {
        return $this->taskAssignments()->completed();
    }

    /**
     * Get task assignment messages through assignments.
     */
    public function taskAssignmentMessages()
    {
        return $this->hasManyThrough(
            TaskAssignmentMessage::class,
            DeveloperTaskAssignment::class,
            'developer_project_enrollment_id', // Foreign key on developer_task_assignments
            'task_assignment_id', // Foreign key on task_assignment_messages
            'id', // Local key on developer_project_enrollments
            'id' // Local key on developer_task_assignments
        );
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get total tasks count for this enrollment.
     */
    public function getTotalTasksCountAttribute(): int
    {
        return $this->taskAssignments()->count();
    }

    /**
     * Get completed tasks count for this enrollment.
     */
    public function getCompletedTasksCountAttribute(): int
    {
        return $this->completedTaskAssignments()->count();
    }

    /**
     * Get in-progress tasks count for this enrollment.
     */
    public function getInProgressTasksCountAttribute(): int
    {
        return $this->taskAssignments()
            ->where('progress', 'in_progress')
            ->count();
    }

    /**
     * Get total spend time across all assignments.
     */
    public function getTotalSpendTimeAttribute(): int
    {
        return $this->taskAssignments()->sum('spend_time') ?? 0;
    }

    /**
     * Get total spend time in hours.
     */
    public function getTotalSpendTimeInHoursAttribute(): float
    {
        return $this->getTotalSpendTimeAttribute() / 60;
    }

    /**
     * Check if enrollment has any active task assignments.
     */
    public function hasActiveTaskAssignments(): bool
    {
        return $this->activeTaskAssignments()->exists();
    }

    /**
     * Get task completion percentage for this enrollment.
     */
    public function getTaskCompletionPercentageAttribute(): float
    {
        $total = $this->taskAssignments()->count();
        if ($total === 0) {
            return 0;
        }
        
        $completed = $this->completedTaskAssignments()->count();
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Get assignments by progress status.
     */
    public function getAssignmentsByProgress()
    {
        return $this->taskAssignments()
            ->select('progress', \DB::raw('count(*) as total'))
            ->groupBy('progress')
            ->get()
            ->pluck('total', 'progress')
            ->toArray();
    }

    /**
     * Get the latest task assignment.
     */
    public function getLatestTaskAssignmentAttribute()
    {
        return $this->taskAssignments()->latest()->first();
    }

    /**
     * Get tasks with upcoming deadlines.
     */
    public function getUpcomingTasks($days = 7)
    {
        return $this->tasks()
            ->whereHas('taskAssignments', function ($query) {
                $query->where('progress', '!=', 'completed')
                    ->where('progress', '!=', 'cancelled');
            })
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays($days))
            ->get();
    }

    /**
     * Get overdue tasks for this enrollment.
     */
    public function getOverdueTasks()
    {
        return $this->tasks()
            ->whereHas('taskAssignments', function ($query) {
                $query->where('progress', '!=', 'completed')
                    ->where('progress', '!=', 'cancelled');
            })
            ->where('deadline', '<', now())
            ->get();
    }

    /**
     * Get total estimated time for all tasks in this enrollment.
     */
    public function getTotalEstimatedTimeAttribute(): int
    {
        return $this->tasks()->sum('estimate_time') ?? 0;
    }

    /**
     * Get total estimated time in hours.
     */
    public function getTotalEstimatedTimeInHoursAttribute(): float
    {
        return $this->getTotalEstimatedTimeAttribute() / 60;
    }

    /**
     * Get productivity ratio (spend time / estimated time).
     */
    public function getProductivityRatioAttribute(): float
    {
        $estimated = $this->getTotalEstimatedTimeAttribute();
        if ($estimated === 0) {
            return 0;
        }
        
        return round(($this->getTotalSpendTimeAttribute() / $estimated) * 100, 2);
    }

    /**
     * Get task assignment summary.
     */
    public function getTaskAssignmentSummary()
    {
        return [
            'total_assignments' => $this->getTotalTasksCountAttribute(),
            'completed' => $this->getCompletedTasksCountAttribute(),
            'in_progress' => $this->getInProgressTasksCountAttribute(),
            'completion_percentage' => $this->getTaskCompletionPercentageAttribute(),
            'total_spend_time_hours' => $this->getTotalSpendTimeInHoursAttribute(),
            'total_estimated_time_hours' => $this->getTotalEstimatedTimeInHoursAttribute(),
            'productivity_ratio' => $this->getProductivityRatioAttribute(),
            'overdue_tasks' => $this->getOverdueTasks()->count(),
            'upcoming_tasks' => $this->getUpcomingTasks()->count(),
        ];
    }
    // ==================== =============================== ====================


}