<?php
namespace Modules\Communicate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
//use Illuminate\Database\Eloquent\SoftDeletes;

class PrivateMessage extends Model
{
    //use SoftDeletes;
    
    // Disable automatic timestamps
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message',
        'posted_date_time',
        'private_message_thread_id',
        'replied_to_private_message_id',
        'posted_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'message' => 'string',
        'posted_date_time' => 'datetime',
        //'created_at' => 'datetime',
        //'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //'deleted_at',
    ];

    /**
     * Get the thread that this message belongs to.
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(PrivateMessageThread::class, 'private_message_thread_id');
    }

    /**
     * Get the user who posted this message.
     */
    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Get the message that this message is replying to.
     */
    public function repliedTo(): BelongsTo
    {
        return $this->belongsTo(PrivateMessage::class, 'replied_to_private_message_id');
    }

    /**
     * Get all replies to this message.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(PrivateMessage::class, 'replied_to_private_message_id');
    }

    /**
     * Get the recipient of this message (the other user in the thread).
     */
    public function getRecipientAttribute()
    {
        if (!$this->thread) {
            return null;
        }

        return $this->posted_by === $this->thread->created_by 
            ? $this->thread->sendTo 
            : $this->thread->createdBy;
    }

    /**
     * Check if this message is a reply to another message.
     */
    public function isReply(): bool
    {
        return !is_null($this->replied_to_private_message_id);
    }

    /**
     * Check if this message is the first message in its thread.
     */
    public function isFirstMessage(): bool
    {
        if (!$this->thread) {
            return false;
        }

        $firstMessage = $this->thread->firstMessage;
        return $firstMessage && $firstMessage->id === $this->id;
    }

    
    /**
     * Scope a query to only include messages for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('posted_by', $userId)
                     ->orWhereHas('thread', function ($q) use ($userId) {
                         $q->where('created_by', $userId)
                           ->orWhere('send_to', $userId);
                     });
    }

    /**
     * Scope a query to get messages sent to a specific user.
     */
    public function scopeSentTo($query, int $userId)
    {
        return $query->whereHas('thread', function ($q) use ($userId) {
            $q->where('send_to', $userId);
        });
    }

    /**
     * Scope a query to get messages sent by a specific user.
     */
    public function scopeSentBy($query, int $userId)
    {
        return $query->where('posted_by', $userId);
    }

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
     * Get the message age in human readable format.
     */
    public function getPostedAgoAttribute(): string
    {
        return $this->posted_date_time->diffForHumans();
    }

    /**
     * Check if the message was posted today.
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->posted_date_time->isToday();
    }

    /**
     * Get the formatted posted date time.
     */
    public function getFormattedPostedDateTimeAttribute(): string
    {
        return $this->posted_date_time->format('M d, Y g:i A');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set posted_date_time if not set
            if (!$model->posted_date_time) {
                $model->posted_date_time = now();
            }            
        });
    }
}