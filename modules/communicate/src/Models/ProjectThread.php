<?php
namespace Modules\Communicate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class ProjectThread extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'status',
        'project_id',
        'posted_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * The status constants.
     */
    const STATUS_ENABLE = 'enable';
    const STATUS_DISABLE = 'disable';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Add global scope for enable status by default
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', self::STATUS_ENABLE);
        });
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the project that owns the thread.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who posted the thread.
     */
    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Get all messages in this thread.
     */
    public function messages()
    {
        return $this->hasMany(ProjectThreadMessage::class)->orderBy('posted_date_time', 'asc');
    }

    /**
     * Get the first message in this thread.
     */
    public function firstMessage()
    {
        return $this->hasOne(ProjectThreadMessage::class)->oldest('posted_date_time');
    }

    /**
     * Get the latest message in this thread.
     */
    public function latestMessage()
    {
        return $this->hasOne(ProjectThreadMessage::class)->latest('posted_date_time');
    }

    /**
     * Get all messages with replies.
     */
    public function messagesWithReplies()
    {
        return $this->messages()->with('replies');
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include enabled threads.
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', self::STATUS_ENABLE);
    }

    /**
     * Scope a query to only include disabled threads.
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', self::STATUS_DISABLE);
    }

    /**
     * Scope a query to filter by project.
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if the thread is enabled.
     */
    public function isEnabled(): bool
    {
        return $this->status === self::STATUS_ENABLE;
    }

    /**
     * Check if the thread is disabled.
     */
    public function isDisabled(): bool
    {
        return $this->status === self::STATUS_DISABLE;
    }

    /**
     * Enable the thread.
     */
    public function enable(): bool
    {
        return $this->update(['status' => self::STATUS_ENABLE]);
    }

    /**
     * Disable the thread.
     */
    public function disable(): bool
    {
        return $this->update(['status' => self::STATUS_DISABLE]);
    }

    /**
     * Get the total number of messages in this thread.
     */
    public function getMessageCountAttribute(): int
    {
        return $this->messages()->count();
    }

    /**
     * Get the total number of unique users who posted in this thread.
     */
    public function getParticipantCountAttribute(): int
    {
        return $this->messages()->distinct('posted_by')->count('posted_by');
    }

    /**
     * Check if the thread has any messages.
     */
    public function hasMessages(): bool
    {
        return $this->messages()->exists();
    }

    /**
     * Add a message to the thread.
     */
    public function addMessage(string $message, int $postedBy, ?int $repliedToMessageId = null)
    {
        return $this->messages()->create([
            'message' => $message,
            'posted_by' => $postedBy,
            'posted_date_time' => now(),
            'replied_to_message_id' => $repliedToMessageId
        ]);
    }

    /**
     * Get the thread age in days.
     */
    public function getAgeInDaysAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get last activity time.
     */
    public function getLastActivityAtAttribute()
    {
        $latestMessage = $this->latestMessage;
        return $latestMessage ? $latestMessage->posted_date_time : $this->created_at;
    }
}