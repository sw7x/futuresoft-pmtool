<?php
namespace Modules\Communicate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrivateMessageThread extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'status',
        //'created_by',
        //'updated_at',
        'send_to',
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
     * Get the user who created this thread.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who this thread is sent to.
     */
    public function sendTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'send_to');
    }

    /**
     * Get all messages in this thread.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(PrivateMessage::class, 'private_message_thread_id');
    }

    /**
     * Get the latest message in this thread.
     */
    public function latestMessage()
    {
        return $this->hasOne(PrivateMessage::class, 'private_message_thread_id')
                    ->latest('posted_date_time');
    }

    /**
     * Get the first message in this thread.
     */
    public function firstMessage()
    {
        return $this->hasOne(PrivateMessage::class, 'private_message_thread_id')
                    ->oldest('posted_date_time');
    }

    /**
     * Get unread messages in this thread for a specific user.
     */
    public function unreadMessagesForUser(int $userId)
    {
        return $this->messages()
                    ->where('send_to', $userId)
                    ->where('status', 'unread');
    }

    /**
     * Check if the thread has unread messages for a specific user.
     */
    public function hasUnreadMessagesForUser(int $userId): bool
    {
        return $this->unreadMessagesForUser($userId)->exists();
    }

    /**
     * Get the count of unread messages for a specific user.
     */
    public function getUnreadMessagesCountForUser(int $userId): int
    {
        return $this->unreadMessagesForUser($userId)->count();
    }

    /**
     * Mark all messages in thread as read for a specific user.
     */
    public function markAsReadForUser(int $userId): void
    {
        $this->messages()
             ->where('send_to', $userId)
             ->where('status', 'unread')
             ->update(['status' => 'read']);
    }

    /**
     * Get the other participant in the thread.
     */
    public function getOtherParticipant(int $userId)
    {
        return $this->created_by === $userId 
            ? $this->sendTo 
            : $this->createdBy;
    }

    /**
     * Check if a user is a participant in this thread.
     */
    public function isParticipant(int $userId): bool
    {
        return $this->created_by === $userId || $this->send_to === $userId;
    }

    /**
     * Scope a query to only include threads for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('created_by', $userId)
                     ->orWhere('send_to', $userId);
    }

    /**
     * Scope a query to only include active threads.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'enable');
    }

    /**
     * Scope a query to only include threads with unread messages for a user.
     */
    public function scopeWithUnreadMessagesForUser($query, int $userId)
    {
        return $query->whereHas('messages', function ($q) use ($userId) {
            $q->where('send_to', $userId)
              ->where('status', 'unread');
        });
    }

    /**
     * Get the thread title with fallback.
     */
    public function getDisplayTitleAttribute(): string
    {
        if ($this->title) {
            return $this->title;
        }

        $participants = [];
        if ($this->createdBy) {
            $participants[] = $this->createdBy->first_name . ' ' . $this->createdBy->last_name;
        }
        if ($this->sendTo) {
            $participants[] = $this->sendTo->first_name . ' ' . $this->sendTo->last_name;
        }

        return implode(' & ', $participants) ?: 'Private Message';
    }

    /**
     * Get the total messages count in this thread.
     */
    public function getMessagesCountAttribute(): int
    {
        return $this->messages()->count();
    }

    /**
     * Get the last activity time of the thread.
     */
    public function getLastActivityAttribute()
    {
        $latestMessage = $this->latestMessage;
        return $latestMessage ? $latestMessage->posted_date_time : $this->created_at;
    }
}