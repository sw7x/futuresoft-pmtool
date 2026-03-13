<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;


class Permission extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'key',
        'parent_id',
        'access',
        'role_id',
        'status'
    ];
    
    // Store tree_id as a transient attribute
    protected $appends = ['tree_id', 'parent_tree_id', 'checkbox_state'];

    protected $casts = [
        'status' => 'boolean',
        'access' => 'string'
    ];

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }


    // Relationship with role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function toArray()
    {
        return [
            'db_rec_id'         => $this->id,
            'db_parent_rec_id'  => $this->parent_id ?? null, // Add this line
            'id'                => $this->tree_id,/////
            'parent'            => $this->parent_tree_id,/////////
            'text'              => $this->name .' - '.$this->id.' - '.$this->access,
            'type'              => $this->type,/////////////////
            'state'             => $this->checkbox_state, /////// Using the new accessor
            'li_attr'           => [
                'class'             => $this->type,
                'data-key'          => $this->key,
                'data-access'       => $this->access === 'allow',
                'data-db_rec_id'    => $this->id,                
            ],
        ];
    }

    /**
     * Get the checkbox state based on hierarchy
     */
    public function getCheckboxStateAttribute()
    {
        // For leaf nodes, use direct access value
        if ($this->type === 'leaf') {
            return ['checked' => $this->access === 'allow'];
        }
        
        // For non-leaf nodes, calculate based on immediate children
        return $this->calculateParentCheckboxState();
    }

    /**
     * Calculate checkbox state for parent nodes based on immediate children
     */
    private function calculateParentCheckboxState()
    {
        // Load children if not already loaded
        if (!$this->relationLoaded('children')) {
            $this->load('children');
        }
        
        $children = $this->children;
        
        // If no children, treat as leaf
        if ($children->isEmpty()) {
            return ['checked' => $this->access === 'allow'];
        }
        
        $hasChecked         = false;
        $hasUnchecked       = false;
        $hasUndetermined    = false;
        
        foreach ($children as $child) {
            // Get child's state (could be from access or its own calculated state)
            $childState = $child->checkbox_state;
            
            if (isset($childState['undetermined']) && $childState['undetermined'] === true) {
                $hasUndetermined = true;
                // Once we find undetermined, we can break early
                break;
            } elseif ($childState['checked'] === true) {
                $hasChecked = true;
            } else {
                $hasUnchecked = true;
            }
        }
        
        // Determine parent state based on children
        if ($hasUndetermined) {
            return [
                'checked'       => false,
                'undetermined'  => true
            ];
        } elseif ($hasChecked && !$hasUnchecked) {
            // All children are checked
            return ['checked' => true];
        } elseif (!$hasChecked && $hasUnchecked) {
            // All children are unchecked
            return ['checked' => false];
        } else {
            // Mixed state (some checked, some unchecked)
            return [
                'checked'       => false,
                'undetermined'  => true
            ];
        }
    }

    /**
     * Get the parent tree ID for this permission
     */
    public function getParentTreeIdAttribute()
    {
        // Default for root nodes
        if (!$this->parent_id) {
            return '#';
        }
        
        // If parent relationship is loaded and exists
        if ($this->relationLoaded('parent') && $this->parent) {
            return $this->parent->tree_id ?? '#';
        }
        
        // Try to load the parent if not loaded
        if ($this->parent_id) {
            $this->load('parent');
            if ($this->parent) {
                return $this->parent->tree_id ?? '#';
            }
        }
        
        return '#';
    }

    public function getTypeAttribute()
    {
        if ($this->parent_id === null) {
            return 'root';
        }
        
        // Load parent relationship if needed
        if (!$this->relationLoaded('parent') && $this->parent_id) {
            $this->load('parent');
        }
        
        // Check parent's type
        if ($this->parent) {
            if ($this->parent->parent_id === null) {
                return 'branch'; // Parent is root
            } elseif ($this->parent->parent && $this->parent->parent->parent_id === null) {
                return 'twig'; // Grandparent is root
            } else {
                return 'leaf'; // Deeper levels
            }
        }
        
        return 'leaf'; // Default fallback
    }

    /*=====================================*/
    /* Tree ID Generation Methods */
    /*=====================================*/

    /**
     * Get the tree_id attribute
     */
    public function getTreeIdAttribute()
    {
        static $counters = [];
        
        // Generate cache key based on the tree structure
        $cacheKey = $this->generateTreeCacheKey();
        
        // Check if we already have a tree_id for this permission in this request
        if (isset($counters[$cacheKey])) {
            return $counters[$cacheKey];
        }
        
        // Generate the tree_id based on hierarchy
        $treeId = $this->generateTreeId($counters);
        
        // Cache it for this request
        $counters[$cacheKey] = $treeId;
        
        return $treeId;
    }

    /**
     * Generate a unique cache key for this permission
     */
    private function generateTreeCacheKey()
    {
        $parts = [];
        $current = $this;
        
        while ($current) {
            array_unshift($parts, $current->id);
            $current = $current->parent;
            if ($current && !$current->relationLoaded('parent')) {
                $current->load('parent');
            }
        }
        
        return implode('-', $parts);
    }

    /**
     * Generate the tree_id based on hierarchy
     */
    private function generateTreeId(&$counters)
    {
        // Get the root of this permission
        $root = $this->getRoot();
        
        if (!$root) {
            return ''; 
        }
        
        // Root level
        if ($this->id === $root->id) {
            $rootCounter = $counters['root'] ?? 0;
            $rootCounter++;
            $counters['root'] = $rootCounter;
            $counters['root_map'][$root->id] = $rootCounter;
            return 'root' . $rootCounter;
        }
        
        $rootNumber = $counters['root_map'][$root->id] ?? null;
        
        // If root number not set, calculate it
        if (!$rootNumber) {
            $rootCounter = $counters['root'] ?? 0;
            $rootCounter++;
            $counters['root'] = $rootCounter;
            $counters['root_map'][$root->id] = $rootCounter;
            $rootNumber = $rootCounter;
        }
        
        // Get path from root to this permission
        $path = $this->getPathFromRoot();
        
        // Build the tree_id
        $treeId = 'root' . $rootNumber;
        $currentParent = $root;
        
        foreach ($path as $index => $node) {
            if ($node->id === $root->id) continue;
            
            $parentKey = $currentParent->id . '_' . $node->id;
            $level = $this->getLevel($node);
            
            switch ($level) {
                case 1: // Branch level
                    if (!isset($counters['branch'][$parentKey])) {
                        $branchCounter = $counters['branch_total'][$currentParent->id] ?? 0;
                        $branchCounter++;
                        $counters['branch_total'][$currentParent->id] = $branchCounter;
                        $counters['branch'][$parentKey] = $branchCounter;
                    }
                    $treeId .= '-branch' . $counters['branch'][$parentKey];
                    break;
                    
                case 2: // Twig level
                    if (!isset($counters['twig'][$parentKey])) {
                        $twigCounter = $counters['twig_total'][$currentParent->id] ?? 0;
                        $twigCounter++;
                        $counters['twig_total'][$currentParent->id] = $twigCounter;
                        $counters['twig'][$parentKey] = $twigCounter;
                    }
                    $treeId .= '-twig' . $counters['twig'][$parentKey];
                    break;
                    
                default: // Leaf level and deeper
                    if (!isset($counters['leaf'][$parentKey])) {
                        $leafCounter = $counters['leaf_total'][$currentParent->id] ?? 0;
                        $leafCounter++;
                        $counters['leaf_total'][$currentParent->id] = $leafCounter;
                        $counters['leaf'][$parentKey] = $leafCounter;
                    }
                    $treeId .= '-leaf' . $counters['leaf'][$parentKey];
                    break;
            }
            
            $currentParent = $node;
        }
        
        return $treeId;
    }

    /**
     * Get the root of this permission
     */
    private function getRoot()
    {
        $current = $this;
        
        while ($current->parent) {
            $current = $current->parent;
        }
        
        return $current->parent_id === null ? $current : null;
    }

    /**
     * Get the path from root to this permission
     */
    private function getPathFromRoot()
    {
        $path = [];
        $current = $this;
        
        while ($current) {
            array_unshift($path, $current);
            $current = $current->parent;
        }
        
        return $path;
    }

    /**
     * Get the level of a permission (1 = branch, 2 = twig, 3+ = leaf)
     */
    private function getLevel($permission)
    {
        if ($permission->parent_id === null) {
            return 0;
        }
        
        $level = 0;
        $current = $permission;
        
        while ($current->parent) {
            $level++;
            $current = $current->parent;
        }
        
        return $level;
    }

    /**
     * Static method to get all permissions with tree_ids pre-calculated
     */
    public static function getAllWithTreeIds()
    {
        $permissions = self::with('parent')->get();
        
        // Reset counters
        $counters = [
            'root' => 0,
            'root_map' => [],
            'branch' => [],
            'branch_total' => [],
            'twig' => [],
            'twig_total' => [],
            'leaf' => [],
            'leaf_total' => []
        ];
        
        // First, process roots to assign root numbers
        $roots = $permissions->whereNull('parent_id');
        foreach ($roots as $root) {
            $counters['root']++;
            $counters['root_map'][$root->id] = $counters['root'];
        }
        
        // Then process all permissions to generate tree_ids
        foreach ($permissions as $permission) {
            $permission->tree_id = $permission->generateTreeId($counters);
        }
        
        return $permissions;
    }

    /**
     * Enhanced static method to get all permissions with proper checkbox states
     * This ensures children are loaded for state calculation
     */
    public static function getTreeWithStates()
    {
        // Load permissions with all necessary relationships
        $permissions = self::with(['parent', 'children', 'role'])->get();
        
        // Reset counters
        $counters = [
            'root' => 0,
            'root_map' => [],
            'branch' => [],
            'branch_total' => [],
            'twig' => [],
            'twig_total' => [],
            'leaf' => [],
            'leaf_total' => []
        ];
        
        // First, process roots to assign root numbers
        $roots = $permissions->whereNull('parent_id');
        foreach ($roots as $root) {
            $counters['root']++;
            $counters['root_map'][$root->id] = $counters['root'];
        }
        
        // Process all permissions to generate tree_ids
        foreach ($permissions as $permission) {
            $permission->tree_id = $permission->generateTreeId($counters);
        }
        
        return $permissions;
    }





    ////////////////////////////////




    /**
     * Get all descendants of a permission by ID
     * 
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public static function getAllChildrenById($id)
    {
        $permission = self::with('children')->find($id);
        
        if (!$permission) {
            return collect();
        }
        
        $descendants = collect();
        
        // Helper recursive function
        $fetchChildren = function ($permission) use (&$fetchChildren, &$descendants) {
            foreach ($permission->children as $child) {
                $descendants->push($child);
                
                // Load children if not already loaded
                if (!$child->relationLoaded('children')) {
                    $child->load('children');
                }
                
                // Recursively fetch grandchildren
                if ($child->children->isNotEmpty()) {
                    $fetchChildren($child);
                }
            }
        };
        
        $fetchChildren($permission);
        
        return $descendants;
    }

    /**
     * Get all descendant IDs of a permission by ID
     * 
     * @param int $id
     * @return array
     */
    public static function getChildrenIdsById($id)
    {
        return self::getAllChildrenById($id)->pluck('id')->toArray();
    }








}







