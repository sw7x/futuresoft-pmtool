<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;

use Cartalyst\Sentinel\Users\EloquentUser as CartalystUser;

use App\Models\Role as RoleModel;

class User extends CartalystUser
//class User extends Authenticatable
{
    //use HasApiTokens, HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable, Authorizable,SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    

    const GENDER_MALE   = 'male';
    const GENDER_FEMALE = 'female';
    const GENDER_OTHER  = 'other';

    protected $appends = ['is_activated'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [       
        'first_name',
        'last_name',
        'email',
        'username',
        'password',
        'phone',
        'gender',
        'address', 
        'nic',
        'profile_pic',
        'date_of_joined',
        'hourly_rate',
        'monthly_salary',
        'epf_etf_details',
        'edu_qualifications',
        'skills',
        'date_of_birth',
        'account_status',
        'employment_status',
        'designation_id',
        //'permissions'
        //'last_login'
        //'created_at'
        //'updated_at'
    ];
    
    protected $loginNames = ['email', 'username'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
        'account_status' => 'boolean',
    ];

    public static function boot(){
        parent::boot();        
        /*static::creating(function ($model) {
            $model->uuid = str_replace('-', '', Uuid::uuid4()->toString());
        });*/
    }


    protected static function booted(){
        /*static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('users.status', 1);
        });*/
    }





    public function getProfilePicAttribute($value){
        
        if($this->roles->isEmpty()){
            $imagePath = asset('images/default-profile-images/user.png');
        }else{
            if($value){           
                $imagePath = asset('storage/'.$value);
            }else{            
                $userRole = $this->getUserRoles()->first()->slug;                
                switch ($userRole) {
                    case "admin":
                        $imagePath = asset('images/default-profile-images/admin.png');
                    break;
                    case "owner":
                        $imagePath = asset('images/default-profile-images/owner.png');
                    break;
                    case "manager":
                        $imagePath  = asset('images/default-profile-images/manager.png');
                    break;
                    case "pm":
                        $imagePath = asset('images/default-profile-images/pm.png');
                    break;
                    case "dev":
                        $imagePath = asset('images/default-profile-images/dev.png');
                    break;
                    default:
                        $imagePath = asset('images/default-profile-images/user.png');
                }                    
            }
        }        
        //dd($this->getUserRoles());    
        return $imagePath;
    }




    public function getUserRoles(){

        //return $this->roles[0]->getRoleSlug();
        $userRoles = array();

        $userRoles = $this->roles->each(function($item, $key){

            //dd($item->getRoleSlug());
            return $item->getRoleSlug();
        });
        return $userRoles;
    }



    public function getFirstRole()
    {
        $role = $this->roles->first();
        
        if ($role) {
            return (object)[
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'permissions' => $role->permissions
            ];
        }
        
        return null;
    }

    public function getFirstRoleName()
    {
        $role = $this->roles->first();
        return $role ? $role->name : null;

    }

    public function getFirstRoleSlug()
    {
        $role = $this->roles->first();
        return $role ? $role->slug : null;
    }


    public function getIsActivatedAttribute(){
        return $this->isactivated();
    }

    public function isactivated(){
        if($this->activations->isEmpty())
            return null;
        else
            return ($this->activations->first()->completed);
    }


    // In your model
    public function toArray()
    {
        $array = parent::toArray();
        
        // Remove specific keys
        unset($array['roles']);
        
        // Or add custom computed fields
        $array['full_name'] = $this->first_name . ' ' . $this->last_name;
        
        return $array;
    }



    public function getAllUserRoles(){
        $roles = Sentinel::getRoleRepository()->get();
        //dd($roles);
        $userRoles = null;
        $userRoles = $roles->filter(function ($value, $key){
            //var_dump ($value);
            return $this->inRole($value->name);

        })->values()->all();
        //dd($userRoles);
        return $userRoles;

    }


    // .... leave model=======================
    /**
     * Get all leaves for the user.
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'user_id');
    }

    /**
     * Get leaves applied by the user.
     */
    public function appliedLeaves()
    {
        return $this->hasMany(Leave::class, 'applied_by');
    }

    /**
     * Get leaves responded to by the user.
     */
    public function respondedLeaves()
    {
        return $this->hasMany(Leave::class, 'responded_by');
    }

    /**
     * Get pending leaves for the user.
     */
    public function pendingLeaves()
    {
        return $this->hasMany(Leave::class, 'user_id')->where('progress', 'pending');
    }

    /**
     * Get approved leaves for the user.
     */
    public function approvedLeaves()
    {
        return $this->hasMany(Leave::class, 'user_id')->where('progress', 'approved');
    }

    /**
     * Get current active leave for the user (if any).
     */
    public function currentLeave()
    {
        return $this->hasOne(Leave::class, 'user_id')
                    ->where('progress', 'approved')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->latest();
    }

    /**
     * Check if user has any pending leave requests.
     */
    public function hasPendingLeave()
    {
        return $this->leaves()->where('progress', 'pending')->exists();
    }

    /**
     * Check if user is currently on leave.
     */
    public function isOnLeave()
    {
        return $this->leaves()
                    ->where('progress', 'approved')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->exists();
    }




    // .... Designation model=======================

    /**
     * Get the designations for the user.
     */
    public function designations()
    {
        return $this->belongsToMany(\Modules\Employee\Models\Designation::class, 'designation_user')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    /**
     * Get the primary designation (most recent assigned).
     */
    public function primaryDesignation()
    {
        return $this->belongsToMany(\Modules\Employee\Models\Designation::class, 'designation_user')
                    ->withPivot('assigned_at')
                    ->orderBy('designation_user.assigned_at', 'desc')
                    ->withTimestamps()
                    ->limit(1);
    }

    /**
     * Get enabled designations for the user.
     */
    public function enabledDesignations()
    {
        return $this->belongsToMany(\Modules\Employee\Models\Designation::class, 'designation_user')
                    ->where('status', 'enable')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    /**
     * Check if user has a specific designation.
     */
    public function hasDesignation($designationId): bool
    {
        return $this->designations()->where('designation_id', $designationId)->exists();
    }

    /**
     * Check if user has any of the given designations.
     */
    public function hasAnyDesignation(array $designationIds): bool
    {
        return $this->designations()->whereIn('designation_id', $designationIds)->exists();
    }

    /**
     * Assign a designation to the user.
     */
    public function assignDesignation($designationId): void
    {
        if (!$this->hasDesignation($designationId)) {
            $this->designations()->attach($designationId, [
                'assigned_at' => now()
            ]);
        }
    }

    /**
     * Remove a designation from the user.
     */
    public function removeDesignation($designationId): void
    {
        $this->designations()->detach($designationId);
    }

    /**
     * Sync designations for the user.
     */
    public function syncDesignations(array $designationIds): void
    {
        $this->designations()->sync($designationIds);
    }

    /**
     * Get the highest level designation for the user.
     */
    public function getHighestLevelDesignation()
    {
        return $this->designations()
                    ->orderBy('level', 'desc')
                    ->first();
    }

    public function getDesignationName()
    {
        $designation = $this->designations()->first();
        return $designation ? $designation->name : 'No Designation';
    }

    public function getDesignationHierarchy()
    {
        $designations = $this->designations()->with('parent')->get();
        $hierarchy = [];
        
        foreach ($designations as $designation) {
            $path = [];
            $current = $designation;
            
            while ($current) {
                array_unshift($path, $current->name);
                $current = $current->parent;
            }
            
            $hierarchy[] = implode(' > ', $path);
        }
        
        return $hierarchy;
    }

    public function getPrimaryDesignation()
    {
        return $this->designations()->orderBy('designation_user.created_at', 'desc')->first();
    }

    public function hasDesignationByName($name)
    {
        return $this->designations()->where('name', $name)->exists();
    }

    public function getDesignationLevel()
    {
        $designation = $this->designations()->first();
        return $designation ? $designation->level : 0;
    }




 


    // .... Timesheet, TimesheetEntry  models===================

    /**
     * Get the timesheets submitted by the user.
     */
    public function submittedTimesheets()
    {
        return $this->hasMany(Timesheet::class, 'submitted_by');
    }

    /**
     * Get the timesheets reviewed by the user.
     */
    public function reviewedTimesheets()
    {
        return $this->hasMany(Timesheet::class, 'reviewed_by');
    }

    /**
     * Get timesheet entries for the user through task assignments.
     */
    public function timesheetEntries()
    {
        return $this->hasManyThrough(
            TimesheetEntry::class,
            TaskAssignment::class,
            'assigned_to', // Foreign key on task_assignments table
            'task_assignment_id', // Foreign key on timesheet_records table
            'id', // Local key on users table
            'id' // Local key on task_assignments table
        );
    }

    /**
     * Get timesheets for the user through task assignments.
     */
    public function timesheets()
    {
        return $this->hasManyThrough(
            Timesheet::class,
            TimesheetEntry::class,
            'task_assignment_id', // Foreign key on timesheet_records table
            'id', // Foreign key on timesheets table
            'id', // Local key on users table
            'timesheet_id' // Local key on timesheet_records table
        );
    }

    /**
     * Get total hours worked for a specific week.
     */
    public function getTotalHoursForWeek(string $weekStartDate, string $weekEndDate): float
    {
        return $this->timesheetEntries()
                    ->whereBetween('date', [$weekStartDate, $weekEndDate])
                    ->sum('spend_time') / 60;
    }

    /**
     * Get total hours worked for a specific date.
     */
    public function getTotalHoursForDate(string $date): float
    {
        return $this->timesheetEntries()
                    ->whereDate('date', $date)
                    ->sum('spend_time') / 60;
    }

    /**
     * Get pending timesheets for the user.
     */
    public function getPendingTimesheets()
    {
        return $this->timesheets()->where('progress', 'pending');
    }

    /**
     * Get approved timesheets for the user.
     */
    public function getApprovedTimesheets()
    {
        return $this->timesheets()->where('progress', 'approved');
    }

    /**
     * Check if user has submitted any timesheet for a specific week.
     */
    public function hasSubmittedTimesheetForWeek(string $weekStartDate, string $weekEndDate): bool
    {
        return $this->timesheets()
                    ->where('week_start_date', $weekStartDate)
                    ->where('week_end_date', $weekEndDate)
                    ->exists();
    }





















    // ==================== PROJECT THREAD RELATIONSHIPS ====================

    /**
     * Get all project threads created by the user.
     */
    public function projectThreads()
    {
        return $this->hasMany(ProjectThread::class, 'posted_by');
    }

    /**
     * Get all project thread messages created by the user.
     */
    public function projectThreadMessages()
    {
        return $this->hasMany(ProjectThreadMessage::class, 'posted_by');
    }

    /**
     * Get the latest project thread message created by the user.
     */
    public function latestProjectThreadMessage()
    {
        return $this->hasOne(ProjectThreadMessage::class, 'posted_by')->latest('posted_date_time');
    }

    /**
     * Get all project threads where the user has participated.
     */
    public function participatedProjectThreads()
    {
        return $this->hasManyDeep(
            ProjectThread::class,
            [ProjectThreadMessage::class],
            [
                'posted_by', // Foreign key on project_thread_messages
                'id', // Foreign key on project_threads
            ],
            [
                'id', // Local key on users
                'project_thread_id', // Local key on project_thread_messages
            ]
        )->distinct();
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get the total number of project threads created by the user.
     */
    public function getProjectThreadsCountAttribute(): int
    {
        return $this->projectThreads()->count();
    }

    /**
     * Get the total number of project thread messages created by the user.
     */
    public function getProjectThreadMessagesCountAttribute(): int
    {
        return $this->projectThreadMessages()->count();
    }

    /**
     * Check if the user has created any project threads.
     */
    public function hasCreatedProjectThreads(): bool
    {
        return $this->projectThreads()->exists();
    }

    /**
     * Get the user's latest project thread activity.
     */
    public function getLatestProjectThreadActivityAttribute()
    {
        $latestMessage = $this->latestProjectThreadMessage;
        return $latestMessage ? $latestMessage->posted_date_time : null;
    }

    /**
     * Get the user's project thread activity count for today.
     */
    public function getTodayProjectThreadActivityCountAttribute(): int
    {
        return $this->projectThreadMessages()
                    ->whereDate('posted_date_time', today())
                    ->count();
    }
    // ==================== ============================ ====================








    // ==================== TASK THREAD RELATIONSHIPS ====================

    /**
     * Get all task threads created by the user.
     */
    public function taskThreads()
    {
        return $this->hasMany(TaskThread::class, 'posted_by');
    }

    /**
     * Get all task thread messages created by the user.
     */
    public function taskThreadMessages()
    {
        return $this->hasMany(TaskThreadMessage::class, 'posted_by');
    }

    /**
     * Get the latest task thread message created by the user.
     */
    public function latestTaskThreadMessage()
    {
        return $this->hasOne(TaskThreadMessage::class, 'posted_by')->latest('posted_date_time');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get the total number of task threads created by the user.
     */
    public function getTaskThreadsCountAttribute(): int
    {
        return $this->taskThreads()->count();
    }

    /**
     * Get the total number of task thread messages created by the user.
     */
    public function getTaskThreadMessagesCountAttribute(): int
    {
        return $this->taskThreadMessages()->count();
    }

    /**
     * Check if the user has created any task threads.
     */
    public function hasCreatedTaskThreads(): bool
    {
        return $this->taskThreads()->exists();
    }

    // ==================== ============================ ====================





    // ==================== PRIVATE MESSAGE THREAD RELATIONSHIPS ====================

    /**
     * Get all private message threads created by the user.
     */
    public function createdPrivateMessageThreads()
    {
        return $this->hasMany(PrivateMessageThread::class, 'created_by');
    }

    /**
     * Get all private message threads sent to the user.
     */
    public function receivedPrivateMessageThreads()
    {
        return $this->hasMany(PrivateMessageThread::class, 'send_to');
    }

    /**
     * Get all private message threads where the user is a participant.
     */
    public function privateMessageThreads()
    {
        return $this->hasMany(PrivateMessageThread::class, 'created_by')
                    ->orWhere('send_to', $this->id);
    }

    /**
     * Get all private messages posted by the user.
     */
    public function privateMessages()
    {
        return $this->hasMany(PrivateMessage::class, 'posted_by');
    }

    /**
     * Get the latest private message posted by the user.
     */
    public function latestPrivateMessage()
    {
        return $this->hasOne(PrivateMessage::class, 'posted_by')->latest('posted_date_time');
    }

    /**
     * Get unread private messages sent to the user.
     */
    public function unreadPrivateMessages()
    {
        return $this->hasMany(PrivateMessage::class, 'send_to', 'id')
                    ->where('status', 'unread');
    }

    /**
     * Get unread private message threads for the user.
     */
    public function unreadPrivateMessageThreads()
    {
        return $this->privateMessageThreads()
                    ->whereHas('messages', function ($q) {
                        $q->where('send_to', $this->id)
                          ->where('status', 'unread');
                    });
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get the total count of unread private messages for the user.
     */
    public function getUnreadPrivateMessagesCountAttribute(): int
    {
        return $this->unreadPrivateMessages()->count();
    }

    /**
     * Get the total count of private message threads for the user.
     */
    public function getPrivateMessageThreadsCountAttribute(): int
    {
        return $this->privateMessageThreads()->count();
    }

    /**
     * Get the total count of private messages posted by the user.
     */
    public function getPrivateMessagesCountAttribute(): int
    {
        return $this->privateMessages()->count();
    }

    /**
     * Check if the user has any unread private messages.
     */
    public function hasUnreadPrivateMessages(): bool
    {
        return $this->unreadPrivateMessages()->exists();
    }

    /**
     * Get all private message threads with a specific user.
     */
    public function getPrivateMessageThreadWithUser(int $otherUserId)
    {
        return $this->privateMessageThreads()
                    ->where(function ($q) use ($otherUserId) {
                        $q->where('created_by', $otherUserId)
                          ->orWhere('send_to', $otherUserId);
                    })
                    ->first();
    }

    /**
     * Start a new private message thread with another user.
     */
    public function startPrivateMessageThread(
        int $otherUserId, 
        string $message, 
        ?string $title = null
    ): PrivateMessageThread {
        // Create the thread
        $thread = PrivateMessageThread::create([
            'title' => $title,
            'status' => 'enable',
            'created_by' => $this->id,
            'send_to' => $otherUserId,
        ]);

        // Create the first message
        $thread->messages()->create([
            'message' => $message,
            'posted_date_time' => now(),
            'posted_by' => $this->id,
            'status' => 'unread',
        ]);

        return $thread;
    }

    /**
     * Get the latest private message thread activity for the user.
     */
    public function getLatestPrivateMessageActivityAttribute()
    {
        $latestMessage = $this->latestPrivateMessage;
        return $latestMessage ? $latestMessage->posted_date_time : null;
    }

    // ==================== ============================ ====================





    //=== DeveloperProjectEnrollment ===================

    /**
     * Get the developer enrollments for the user.
     * This returns all enrollments (active and historical).
     */
    public function developerEnrollments()
    {
        return $this->hasMany(DeveloperProjectEnrollment::class, 'developer_id');
    }

    /**
     * Get the active developer enrollments for the user.
     * This returns only active (currently assigned) enrollments.
     */
    public function activeDeveloperEnrollments()
    {
        return $this->hasMany(DeveloperProjectEnrollment::class, 'developer_id')
                    ->whereNull('unassigned_date_time');
    }
    // ==================== ============================ ====================













    //=== Project ===================

    /**
     * Get the projects managed by this user (as Project Manager).
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'pm_id');
    }

    /**
     * Get the active projects managed by this user.
     */
    public function activeManagedProjects()
    {
        return $this->hasMany(Project::class, 'pm_id')
                    ->where('status', 'enable');
    }
    // ==================== ============================ ====================






    // ==================== TASK ASSIGNMENT MESSAGE RELATIONSHIPS ====================

    /**
     * Get all task assignment messages posted by the user.
     */
    /*
    public function taskAssignmentMessages()
    {
        return $this->hasMany(TaskAssignmentMessage::class, 'posted_by');
    }
    */

    /**
     * Get the latest task assignment message posted by the user.
     */
    /*
    public function latestTaskAssignmentMessage()
    {
        return $this->hasOne(TaskAssignmentMessage::class, 'posted_by')->latest('posted_date_time');
    }
    */
    // ==================== ============================ ====================






}
