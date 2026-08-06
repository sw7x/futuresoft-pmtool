<?php
namespace Modules\Leave\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


class Leave extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leaves';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_date',
        'end_date',
        'leave_type',
        'progress',
        'time_period',
        'from_time',
        'to_time',
        'applied_at',
        'applied_by',
        'reason',
        'responded_at',
        'responded_by',
        'response_message',
        //'created_at',
        //'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'from_time' => 'datetime:H:i',
        'to_time' => 'datetime:H:i',
        'applied_at' => 'datetime',
        'responded_at' => 'datetime',
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
        'total_days',
        'status_label',
        'time_period_label',
        'leave_type_label',
        'is_full_day',
        'is_half_day',
        'is_short_leave',
        'formatted_date_range',
    ];

    // ==================== CONSTANTS ====================
    
    const LEAVE_TYPES = ['annual', 'casual', 'medical', 'other'];
    const PROGRESS_STATUSES = ['pending', 'approved', 'rejected', 'cancelled'];
    const TIME_PERIODS = ['full_day', 'half_day_morning', 'half_day_afternoon', 'short_leave', 'custom_time'];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    const TYPE_ANNUAL = 'annual';
    const TYPE_CASUAL = 'casual';
    const TYPE_MEDICAL = 'medical';
    const TYPE_OTHER = 'other';

    const PERIOD_FULL_DAY = 'full_day';
    const PERIOD_HALF_DAY_MORNING = 'half_day_morning';
    const PERIOD_HALF_DAY_AFTERNOON = 'half_day_afternoon';
    const PERIOD_SHORT_LEAVE = 'short_leave';
    const PERIOD_CUSTOM_TIME = 'custom_time';

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user who applied for the leave.
     */
    public function appliedBy()
    {
        return $this->belongsTo(User::class, 'applied_by');
    }

    /**
     * Get the user who responded to the leave request.
     */
    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include pending leaves.
     */
    public function scopePending($query)
    {
        return $query->where('progress', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include approved leaves.
     */
    public function scopeApproved($query)
    {
        return $query->where('progress', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include rejected leaves.
     */
    public function scopeRejected($query)
    {
        return $query->where('progress', self::STATUS_REJECTED);
    }

    /**
     * Scope a query to only include cancelled leaves.
     */
    public function scopeCancelled($query)
    {
        return $query->where('progress', self::STATUS_CANCELLED);
    }

    /**
     * Scope a query to only include leaves for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('applied_by', $userId);
    }

    /**
     * Scope a query to only include leaves for a specific leave type.
     */
    public function scopeOfType($query, $leaveType)
    {
        return $query->where('leave_type', $leaveType);
    }

    /**
     * Scope a query to only include leaves within date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              	->orWhereBetween('end_date', [$startDate, $endDate])
              	->orWhere(function ($subQ) use ($startDate, $endDate) {
                  	$subQ->where('start_date', '<=', $startDate)
                    	->where('end_date', '>=', $endDate);
              });
        });
    }

    /**
     * Scope a query to only include leaves applied by a specific user.
     */
    public function scopeAppliedBy($query, $userId)
    {
        return $query->where('applied_by', $userId);
    }

    /**
     * Scope a query to only include leaves that need response (pending).
     */
    public function scopeNeedsResponse($query)
    {
        return $query->where('progress', self::STATUS_PENDING);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get the total days for the leave.
     */
    public function getTotalDaysAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 1; // Default to 1 day if end_date is null
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->progress);
    }

    /**
     * Get the time period label.
     */
    public function getTimePeriodLabelAttribute()
    {
        $labels = [
            'full_day' => 'Full Day',
            'half_day_morning' => 'Half Day (Morning)',
            'half_day_afternoon' => 'Half Day (Afternoon)',
            'short_leave' => 'Short Leave',
            'custom_time' => 'Custom Time'
        ];
        
        return $labels[$this->time_period] ?? ucfirst(str_replace('_', ' ', $this->time_period));
    }

    /**
     * Get the leave type label.
     */
    public function getLeaveTypeLabelAttribute()
    {
        return ucfirst($this->leave_type);
    }

    /**
     * Check if the leave is a full day.
     */
    public function getIsFullDayAttribute()
    {
        return $this->time_period === self::PERIOD_FULL_DAY;
    }

    /**
     * Check if the leave is a half day.
     */
    public function getIsHalfDayAttribute()
    {
        return in_array($this->time_period, [
            self::PERIOD_HALF_DAY_MORNING,
            self::PERIOD_HALF_DAY_AFTERNOON
        ]);
    }

    /**
     * Check if the leave is a short leave.
     */
    public function getIsShortLeaveAttribute()
    {
        return $this->time_period === self::PERIOD_SHORT_LEAVE;
    }

    /**
     * Get formatted date range.
     */
    public function getFormattedDateRangeAttribute()
    {
        if ($this->start_date && $this->end_date) {
            if ($this->start_date->format('Y-m-d') === $this->end_date->format('Y-m-d')) {
                return $this->start_date->format('M d, Y');
            }
            return $this->start_date->format('M d, Y') . ' - ' . $this->end_date->format('M d, Y');
        }
        return $this->start_date ? $this->start_date->format('M d, Y') : 'N/A';
    }

    /**
     * Set the from_time attribute.
     */
    public function setFromTimeAttribute($value)
    {
        $this->attributes['from_time'] = $value ? date('H:i', strtotime($value)) : null;
    }

    /**
     * Set the to_time attribute.
     */
    public function setToTimeAttribute($value)
    {
        $this->attributes['to_time'] = $value ? date('H:i', strtotime($value)) : null;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if the leave is pending.
     */
    public function isPending(): bool
    {
        return $this->progress === self::STATUS_PENDING;
    }

    /**
     * Check if the leave is approved.
     */
    public function isApproved(): bool
    {
        return $this->progress === self::STATUS_APPROVED;
    }

    /**
     * Check if the leave is rejected.
     */
    public function isRejected(): bool
    {
        return $this->progress === self::STATUS_REJECTED;
    }

    /**
     * Check if the leave is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->progress === self::STATUS_CANCELLED;
    }

    /**
     * Check if the leave can be edited.
     */
    public function isEditable(): bool
    {
        return in_array($this->progress, [self::STATUS_PENDING, self::STATUS_CANCELLED]);
    }

    /**
     * Check if the leave can be cancelled.
     */
    public function isCancellable(): bool
    {
        return $this->isPending() || $this->isApproved();
    }

    /**
     * Approve the leave.
     */
    public function approve($userId, $message = null): bool
    {
        $this->progress = self::STATUS_APPROVED;
        $this->responded_at = now();
        $this->responded_by = $userId;
        $this->response_message = $message;
        return $this->save();
    }

    /**
     * Reject the leave.
     */
    public function reject($userId, $message = null): bool
    {
        $this->progress = self::STATUS_REJECTED;
        $this->responded_at = now();
        $this->responded_by = $userId;
        $this->response_message = $message;
        return $this->save();
    }

    /**
     * Cancel the leave.
     */
    public function cancel(): bool
    {
        if (!$this->isCancellable()) {
            return false;
        }
        $this->progress = self::STATUS_CANCELLED;
        return $this->save();
    }

    /**
     * Check if the leave has a response.
     */
    public function hasResponse(): bool
    {
        return !is_null($this->responded_at) && !is_null($this->responded_by);
    }

    /**
     * Get the time period details as string.
     */
    public function getTimePeriodDetails(): string
    {
        switch ($this->time_period) {
            case self::PERIOD_FULL_DAY:
                return 'Full Day';
            case self::PERIOD_HALF_DAY_MORNING:
                return 'Half Day (Morning)';
            case self::PERIOD_HALF_DAY_AFTERNOON:
                return 'Half Day (Afternoon)';
            case self::PERIOD_SHORT_LEAVE:
                return 'Short Leave (' . ($this->from_time ? $this->from_time->format('h:i A') : 'N/A') . ' - ' . ($this->to_time ? $this->to_time->format('h:i A') : 'N/A') . ')';
            case self::PERIOD_CUSTOM_TIME:
                return 'Custom Time (' . ($this->from_time ? $this->from_time->format('h:i A') : 'N/A') . ' - ' . ($this->to_time ? $this->to_time->format('h:i A') : 'N/A') . ')';
            default:
                return 'N/A';
        }
    }

    /**
     * Check if two leaves overlap.
     */
    public function overlapsWith(Leave $leave): bool
    {
        return $this->applied_by === $leave->applied_by &&
               $this->start_date <= $leave->end_date &&
               $leave->start_date <= $this->end_date;
    }

    /**
     * Get the number of working days in the leave period.
     */
    public function getWorkingDays(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $start = $this->start_date->copy();
        $end = $this->end_date->copy();
        $days = 0;

        while ($start <= $end) {
            if ($start->isWeekday()) {
                $days++;
            }
            $start->addDay();
        }

        return $days;
    }

    /**
     * Check if the leave is in the past.
     */
    public function isPast(): bool
    {
        return $this->end_date ? $this->end_date->isPast() : $this->start_date->isPast();
    }

    /**
     * Check if the leave is in the future.
     */
    public function isFuture(): bool
    {
        return $this->start_date->isFuture();
    }

    /**
     * Check if the leave is currently active (today is within leave period).
     */
    public function isActive(): bool
    {
        $today = now()->startOfDay();
        $startDate = $this->start_date->startOfDay();
        $endDate = $this->end_date ? $this->end_date->startOfDay() : $startDate;
        
        return $today->between($startDate, $endDate) && $this->isApproved();
    }

    // ==================== STATIC METHODS ====================

    /**
     * Get all pending leaves for a user.
     */
    public static function getPendingForUser($userId)
    {
        return self::forUser($userId)->pending()->get();
    }

    /**
     * Get all approved leaves for a user within a date range.
     */
    public static function getApprovedForUserInRange($userId, $startDate, $endDate)
    {
        return self::forUser($userId)
            ->approved()
            ->inDateRange($startDate, $endDate)
            ->get();
    }

    /**
     * Check if a user has overlapping leave with a new request.
     */
    public static function hasOverlappingLeave($userId, $startDate, $endDate, $excludeId = null)
    {
        $query = self::forUser($userId)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($sub) use ($startDate, $endDate) {
                      $sub->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                  });
            })
            ->whereIn('progress', [self::STATUS_PENDING, self::STATUS_APPROVED]);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}








