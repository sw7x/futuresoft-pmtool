<?php
namespace Modules\Timesheet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


class Timesheet extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'timesheet_type',
        'week_start_date',
        'week_end_date',
        'progress',
        'submitted_at',
        'submitted_by',
        'reviewed_at',
        'reviewed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'week_start_date' => 'date',
        'week_end_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
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
     * Get the user who submitted the timesheet.
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by')->withTrashed();
    }

    /**
     * Get the user who reviewed the timesheet.
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by')->withTrashed();
    }

    /**
     * Get the timesheet entries for this timesheet.
     */
    public function entries()
    {
        return $this->hasMany(TimesheetEntry::class);
    }

    /**
     * Get total hours for this timesheet.
     */
    public function getTotalHoursAttribute(): float
    {
        return $this->entries()->sum('spend_time') / 60;
    }

    /**
     * Get total minutes for this timesheet.
     */
    public function getTotalMinutesAttribute(): int
    {
        return $this->entries()->sum('spend_time');
    }

    /**
     * Get the week range formatted.
     */
    public function getWeekRangeAttribute(): string
    {
        return $this->week_start_date->format('Y/m/d') . ' - ' . 
               $this->week_end_date->format('Y/m/d');
    }

    /**
     * Check if timesheet is submitted.
     */
    public function isSubmitted(): bool
    {
        return $this->progress === 'submitted' || 
               $this->progress === 'approved' || 
               $this->progress === 'rejected';
    }

    /**
     * Check if timesheet is approved.
     */
    public function isApproved(): bool
    {
        return $this->progress === 'approved';
    }

    /**
     * Check if timesheet is rejected.
     */
    public function isRejected(): bool
    {
        return $this->progress === 'rejected';
    }

    /**
     * Check if timesheet is pending.
     */
    public function isPending(): bool
    {
        return $this->progress === 'pending';
    }

    /**
     * Check if timesheet is draft.
     */
    public function isDraft(): bool
    {
        return $this->progress === 'draft';
    }

    /**
     * Submit the timesheet.
     */
    public function submit(int $userId): bool
    {
        return $this->update([
            'progress' => 'submitted',
            'submitted_at' => now(),
            'submitted_by' => $userId,
        ]);
    }

    /**
     * Approve the timesheet.
     */
    public function approve(int $userId): bool
    {
        return $this->update([
            'progress' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $userId,
        ]);
    }

    /**
     * Reject the timesheet.
     */
    public function reject(int $userId): bool
    {
        return $this->update([
            'progress' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $userId,
        ]);
    }

    /**
     * Scope a query to only include timesheets of a given type.
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('timesheet_type', $type);
    }

    /**
     * Scope a query to only include timesheets with a given progress status.
     */
    public function scopeWithProgress(Builder $query, string $progress): Builder
    {
        return $query->where('progress', $progress);
    }

    /**
     * Scope a query to only include pending timesheets.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('progress', 'pending');
    }

    /**
     * Scope a query to only include approved timesheets.
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('progress', 'approved');
    }

    /**
     * Scope a query to only include submitted timesheets.
     */
    public function scopeSubmitted(Builder $query): Builder
    {
        return $query->whereIn('progress', ['submitted', 'approved', 'rejected']);
    }

    /**
     * Scope a query to only include timesheets for a specific week.
     */
    public function scopeForWeek(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->where('week_start_date', $startDate)
                     ->where('week_end_date', $endDate);
    }

    /**
     * Scope a query to only include timesheets for a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->whereHas('entries', function ($query) use ($userId) {
            $query->whereHas('taskAssignment', function ($query) use ($userId) {
                $query->where('assigned_to', $userId);
            });
        });
    }

    /**
     * Get the status badge class.
     */    
	public function getStatusBadgeClassAttribute(): string
	{
		switch($this->progress) {
			case 'pending':  return 'bg-warning';
			case 'approved': return 'bg-success';
			case 'rejected': return 'bg-danger';
			case 'draft':    return 'bg-secondary';
			default:         return 'bg-info';
		}
	}

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->progress);
    }
}






