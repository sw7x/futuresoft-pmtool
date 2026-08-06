<?php
namespace Modules\Timesheet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

use Modules\Core\Models\Casts\HumanDuration;

class TimesheetEntry extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'timesheet_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_assignment_id',
        'date',
        'spend_time',
        'comment',
        'reviewer_comment',
        'timesheet_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        
        'spend_time' => 'integer',
        //'spend_time' => HumanDuration::class,

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
     * @var array
     */
    protected $appends = [
        'spent_time_in_hours',
        'spent_time_formatted',
    ];

    /**
     * Get the timesheet that owns the entry.
     */
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class);
    }

    /**
     * Get the task assignment that owns the entry.
     */
    public function taskAssignment()
    {
        return $this->belongsTo(TaskAssignment::class);
    }

    /**
     * Get the user through task assignment.
     */
    public function user()
    {
        return $this->hasOneDeep(
            User::class,
            [TaskAssignment::class],
            ['id', 'id'],
            ['task_assignment_id', 'assigned_to']
        );
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

    /**
     * Get spent time in hours and minutes array.
     */
    public function getSpentTimeArrayAttribute(): array
    {
        return [
            'hours' => floor($this->spend_time / 60),
            'minutes' => $this->spend_time % 60,
            'total_minutes' => $this->spend_time,
            'total_hours' => round($this->spend_time / 60, 2),
        ];
    }

    /**
     * Set spend time in hours (mutator).
     */
    public function setSpentTimeInHoursAttribute(float $hours): void
    {
        $this->attributes['spend_time'] = (int) ($hours * 60);
    }

    /**
     * Check if entry has comment.
     */
    public function hasComment(): bool
    {
        return !empty($this->comment);
    }

    /**
     * Check if entry has reviewer comment.
     */
    public function hasReviewerComment(): bool
    {
        return !empty($this->reviewer_comment);
    }

    /**
     * Get the total spend time for a specific task assignment.
     */
    public static function getTotalForTaskAssignment(int $taskAssignmentId): int
    {
        return self::where('task_assignment_id', $taskAssignmentId)->sum('spend_time');
    }

    /**
     * Get the total spend time for a specific date.
     */
    public static function getTotalForDate(string $date): int
    {
        return self::whereDate('date', $date)->sum('spend_time');
    }

    /**
     * Get the total spend time for a specific user.
     */
    public static function getTotalForUser(int $userId): int
    {
        return self::whereHas('taskAssignment', function ($query) use ($userId) {
            $query->where('assigned_to', $userId);
        })->sum('spend_time');
    }

    /**
     * Scope a query to only include entries for a specific timesheet.
     */
    public function scopeForTimesheet(Builder $query, int $timesheetId): Builder
    {
        return $query->where('timesheet_id', $timesheetId);
    }

    /**
     * Scope a query to only include entries for a specific date.
     */
    public function scopeForDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope a query to only include entries for a date range.
     */
    public function scopeForDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include entries for a specific task assignment.
     */
    public function scopeForTaskAssignment(Builder $query, int $taskAssignmentId): Builder
    {
        return $query->where('task_assignment_id', $taskAssignmentId);
    }

    /**
     * Scope a query to only include entries for a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereHas('taskAssignment', function ($query) use ($userId) {
            $query->where('assigned_to', $userId);
        });
    }

    /**
     * Scope a query to only include entries with spend time greater than.
     */
    public function scopeWithTimeGreaterThan(Builder $query, int $minutes): Builder
    {
        return $query->where('spend_time', '>', $minutes);
    }

    /**
     * Scope a query to only include entries with spend time less than.
     */
    public function scopeWithTimeLessThan(Builder $query, int $minutes): Builder
    {
        return $query->where('spend_time', '<', $minutes);
    }
}