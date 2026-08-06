<?php
namespace Modules\Communicate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectThreadMessage extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message',
        'posted_date_time',
        'project_thread_id',
        'replied_to_message_id',
        'posted_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'posted_date_time' => 'datetime',
        //'created_at' => 'datetime',
        //'updated_at' => 'datetime',
        //'deleted_at' => 'datetime'
    ];

    /**
     * The attributes that should be appended.
     */
    protected $appends = [
        'time_ago',
        'is_reply'
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the thread that this message belongs to.
     */
    public function thread()
    {
        return $this->belongsTo(ProjectThread::class, 'project_thread_id');
    }

    /**
     * Get the user who posted this message.
     */
    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Get the parent message that this message is replying to.
     */
    public function parentMessage()
    {
        return $this->belongsTo(ProjectThreadMessage::class, 'replied_to_message_id');
    }

    /**
     * Get all replies to this message.
     */
    public function replies()
    {
        return $this->hasMany(ProjectThreadMessage::class, 'replied_to_message_id')
                    ->orderBy('posted_date_time', 'asc');
    }

    /**
     * Get all replies recursively (nested).
     */
    public function repliesRecursive()
    {
        return $this->replies()->with('repliesRecursive');
    }

    /**
     * Get the project through the thread.
     */
    public function project()
    {
        return $this->hasOneThrough(
            Project::class,
            ProjectThread::class,
            'id', // Foreign key on project_threads table
            'id', // Foreign key on projects table
            'project_thread_id', // Local key on project_thread_messages table
            'project_id' // Local key on project_threads table
        );
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include first messages of threads.
     */
    public function scopeFirstMessages($query)
    {
        return $query->whereIn('id', function ($subQuery) {
            $subQuery->selectRaw('MIN(id)')
                    ->from('project_thread_messages')
                    ->groupBy('project_thread_id');
        });
    }

    /**
     * Scope a query to only include root messages (not replies).
     */
    public function scopeRootMessages($query)
    {
        return $query->whereNull('replied_to_message_id');
    }

    /**
     * Scope a query to only include replies.
     */
    public function scopeReplies($query)
    {
        return $query->whereNotNull('replied_to_message_id');
    }

    /**
     * Scope a query to filter by thread.
     */
    public function scopeForThread($query, $threadId)
    {
        return $query->where('project_thread_id', $threadId);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopePostedBy($query, $userId)
    {
        return $query->where('posted_by', $userId);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('posted_date_time', [$startDate, $endDate]);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if this message is the first in its thread.
     */
    public function isFirstMessage(): bool
    {
        return $this->thread->firstMessage->id === $this->id;
    }

    /**
     * Check if this message is a reply to another message.
     */
    public function isReply(): bool
    {
        return !is_null($this->replied_to_message_id);
    }

    /**
     * Check if this message has replies.
     */
    public function hasReplies(): bool
    {
        return $this->replies()->exists();
    }

    /**
     * Get the reply count.
     */
    public function getReplyCountAttribute(): int
    {
        return $this->replies()->count();
    }

    /**
     * Get the message depth (0 for root, 1 for reply, etc.).
     */
    public function getDepthAttribute(): int
    {
        if (!$this->isReply()) {
            return 0;
        }

        $depth = 0;
        $current = $this;
        
        while ($current->parentMessage) {
            $depth++;
            $current = $current->parentMessage;
            
            // Prevent infinite loops
            if ($depth > 100) break;
        }
        
        return $depth;
    }

    /**
     * Check if the message was posted by a specific user.
     */
    public function isPostedBy($userId): bool
    {
        return $this->posted_by == $userId;
    }

    /**
     * Format the message with mentions and links.
     */
    public function getFormattedMessageAttribute(): string
    {
        $message = $this->message;
        
        // Convert URLs to links
        $message = preg_replace(
            '/((https?:\/\/)?([\w\-]+(\.[\w\-]+)+)(\/[\w\-.,@?^=%&:\/~+#]*)?)/',
            '<a href="$1" target="_blank">$1</a>',
            $message
        );
        
        // Convert @mentions to links
        $message = preg_replace(
            '/@(\w+)/',
            '<a href="/users/$1">@$1</a>',
            $message
        );
        
        return $message;
    }

    /**
     * Get the time elapsed since the message was posted.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->posted_date_time->diffForHumans();
    }

    /**
     * Check if the message was posted today.
     */
    public function isPostedToday(): bool
    {
        return $this->posted_date_time->isToday();
    }

    /**
     * Get the message thread hierarchy as a string.
     */
    public function getThreadPathAttribute(): string
    {
        $path = [];
        $current = $this;
        
        while ($current) {
            $path[] = $current->id;
            $current = $current->parentMessage;
        }
        
        return implode(' > ', array_reverse($path));
    }

    /**
     * Check if this message is a reply.
     */
    public function getIsReplyAttribute(): bool
    {
        return !is_null($this->replied_to_message_id);
    }
}




