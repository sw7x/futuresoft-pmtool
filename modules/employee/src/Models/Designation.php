<?php
namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

//use App\Models\User;

class Designation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'short_code',
        'description',
        'parent_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'parent_id' => 'integer',
    ];

    /**
     * Get the parent designation.
     */
    public function parent()
    {
        return $this->belongsTo(Designation::class, 'parent_id');
    }

    /**
     * Get the child designations (sub-designations).
     */
    public function children()
    {
        return $this->hasMany(Designation::class, 'parent_id');
    }

    /**
     * Get all descendants (nested children) of this designation.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors (nested parents) of this designation.
     */
    public function ancestors()
    {
        return $this->parent()->with('ancestors');
    }

    /**
     * Get the users associated with this designation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'designation_user')
                    ->withPivot('assigned_at')
                    ->withTimestamps();
    }

    /**
     * Get active users with this designation (account and employment active).
     */
    public function activeUsers()
    {
        return $this->belongsToMany(User::class, 'designation_user')
                    ->withPivot('assigned_at')
                    ->where('account_status', true)
                    ->where('employment_status', 'active')
                    ->withTimestamps();
    }

    /**
     * Check if designation is enabled.
     */
    public function isEnabled(): bool
    {
        return $this->status === 'enable';
    }

    /**
     * Check if designation is disabled.
     */
    public function isDisabled(): bool
    {
        return $this->status === 'disable';
    }

    /**
     * Enable the designation.
     */
    public function enable(): self
    {
        $this->status = 'enable';
        return $this;
    }

    /**
     * Disable the designation.
     */
    public function disable(): self
    {
        $this->status = 'disable';
        return $this;
    }

    /**
     * Toggle the designation status.
     */
    public function toggleStatus(): self
    {
        $this->status = $this->isEnabled() ? 'disable' : 'enable';
        return $this;
    }

    /**
     * Get the full hierarchy path as string.
     */
    public function getParentDesignations() 
    {
        $path = [];
        //$current = $this;
        $current = $this->parent;
        
        while ($current) {
            array_unshift($path, $current);
            $current = $current->parent;
        }
        
        return $path;
    }

    

    /**
     * Get the depth level of the designation.
     */
    public function getLevelAttribute(): int
    {
        $level = 0;
        $current = $this;
        
        while ($current->parent) {
            $level++;
            $current = $current->parent;
        }
        
        return $level;
    }

    /**
     * Check if this designation has children.
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Check if this designation has parent.
     */
    public function hasParent(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Get user count for this designation.
     */
    public function getUserCount(): int
    {
        return $this->users()->count();
    }

    /**
     * Get active user count for this designation.
     */
    public function getActiveUserCount(): int
    {
        return $this->activeUsers()->count();
    }

    /**
     * Scope a query to only include enabled designations.
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', 'enable');
    }

    /**
     * Scope a query to only include disabled designations.
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', 'disable');
    }

    /**
     * Scope a query to only include root level designations (no parent).
     */
    public function scopeRootLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to search by name or short_code.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
                     ->orWhere('short_code', 'LIKE', "%{$search}%")
                     ->orWhere('description', 'LIKE', "%{$search}%");
    }

    /**
     * Get the immediate subordinate designations (children) recursively.
     */
    public function getSubordinateDesignations(): array
    {
        $subordinates = [];
        $this->load('children');
        
        foreach ($this->children as $child) {
            $subordinates[] = $child;
            $subordinates = array_merge($subordinates, $child->getSubordinateDesignations());
        }
        
        return $subordinates;
    }

    /**
     * Get all users under this designation hierarchy (including sub-designations).
     */
    ///TOD: -----------------------check it works
    public function getAllUsersUnderHierarchy()
    {
        $designationIds = [$this->id];
        $children = $this->getSubordinateDesignations();
        
        foreach ($children as $child) {
            $designationIds[] = $child->id;
        }
        

        return \App\Models\User::whereHas('designations', function ($query) use ($designationIds) {
            $query->whereIn('designation_id', $designationIds);
        })->get();
    }

    /**
     * Get the root parent of this designation.
     */
    public function getRootParent()
    {
        $current = $this;
        
        while ($current->parent) {
            $current = $current->parent;
        }
        
        return $current;
    }


    /**
     * Get the complete hierarchy tree that this record belongs to.
     * Returns the root Designation with all nested children loaded.
     */
    public function getFullHierarchyTree()
    {
        $root = $this->getRootParent(); // Gets top-most node
        
        // Get all descendant objects as a flat array
        $tree = $root->getSubordinateDesignations(); 
        
        // Prepend the root record itself to the start of the array
        array_unshift($tree, $root); 
        
        return collect($tree);
    }

    /**
     * Convert to array for API responses.
     */
    public function toArray()
    {
        $array = parent::toArray();
        
        // Add computed attributes
        $array['level'] = $this->getLevelAttribute();
        $array['hierarchy_path'] = $this->getParentDesignations();
        $array['user_count'] = $this->getUserCount();
        $array['active_user_count'] = $this->getActiveUserCount();
        $array['has_children'] = $this->hasChildren();
        $array['has_parent'] = $this->hasParent();
        $array['is_enabled'] = $this->isEnabled();
        
        return $array;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Auto-generate short_code if not provided
        static::creating(function ($designation) {
            if (empty($designation->short_code)) {
                $designation->short_code = strtoupper(substr($designation->name, 0, 3));
            }
        });
    }
}