<?php
namespace Modules\Communicate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use Modules\Task\Models\DeveloperTaskAssignment;



class TaskAssignmentMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_assignment_id',
        'message',
        'posted_date_time',
        'posted_by',
        'is_edited',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'posted_date_time' => 'datetime',
        'is_edited' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the message preview (truncated).
     */
    public function getPreviewAttribute(): string
    {
        return strlen($this->message) > 100 
            ? substr($this->message, 0, 100) . '...' 
            : $this->message;
    }

    /**
     * Get the time ago posted.
     */
    public function getPostedAtAttribute(): string
    {
        return $this->posted_date_time->diffForHumans();
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the task assignment for this message.
     */
    public function taskAssignment()
    {
        return $this->belongsTo(DeveloperTaskAssignment::class);
    }

    /**
     * Get the user who posted this message.
     */
    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include messages for a specific assignment.
     */
    public function scopeForAssignment($query, int $assignmentId)
    {
        return $query->where('task_assignment_id', $assignmentId);
    }

    /**
     * Scope a query to only include messages posted by a specific user.
     */
    public function scopePostedBy($query, int $userId)
    {
        return $query->where('posted_by', $userId);
    }

    /**
     * Scope a query to order by posted date.
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('posted_date_time', 'desc');
    }

    /**
     * Scope a query to order by posted date (oldest first).
     */
    public function scopeOldestFirst($query)
    {
        return $query->orderBy('posted_date_time', 'asc');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Mark the message as edited.
     */
    public function markAsEdited(): void
    {
        $this->update(['is_edited' => true]);
    }

    /**
     * Check if message is editable (within time limit, e.g., 5 minutes).
     */
    public function isEditable(int $minutesLimit = 5): bool
    {
        if ($this->is_edited) {
            return false;
        }

        return $this->posted_date_time->diffInMinutes(now()) <= $minutesLimit;
    }

    /**
     * Check if message can be deleted by user.
     */
    public function canBeDeletedBy(User $user): bool
    {
        return $user->id === $this->posted_by;
        //return $user->id === $this->posted_by || $user->hasRole('admin');
    }
}