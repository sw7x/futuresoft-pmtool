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
    protected $appends = ['tree_id', 'parent_tree_id'];

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
            'state'             => ["checked" => $this->access === 'allow'], /////// Using the new accessor
            'li_attr'           => [
                'class'             => $this->type,
                'data-key'          => $this->key,
                'data-access'       => $this->access === 'allow',
                'data-db_rec_id'    => $this->id,                
            ],
        ];
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


    /**
     * Get the type of permission based on its depth from root
     * This enforces a strict hierarchy: root -> branch -> twig -> leaf
     */
    public function getTypeAttribute()
    {
        if ($this->parent_id === null) {
            return 'root';
        }
        
        // Calculate depth from root
        $depth = $this->getLevel($this); // Reuse existing method
        
        // Strict level mapping based on depth
        switch ($depth) {
            case 1: return 'branch';// Direct child of root
            case 2: return 'twig';// Child of branch
            default: return 'leaf';// Depth 3 or more (all deeper levels are leaf)
        }

    }



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

    /////////////////
    



    /**
     * Generate the tree_id based on hierarchy
     * 
     * This function creates a unique, human-readable identifier for each permission
     * based on its position in the tree. Example: "root1-branch2-twig1-leaf3"
     * 
     * @param array &$counters Reference to counters array that tracks numbering across the tree
     * @return string The generated tree_id
     */
    private function generateTreeId(&$counters)
    {
        // STEP 1: Find the root ancestor of this permission
        // =================================================
        // Get the top-most parent (where parent_id is null) by traversing up the tree
        $root = $this->getRoot();
        
        // Safety check: if no root found (shouldn't happen), return empty string
        if (!$root) {
            return ''; 
        }
        
        
        // STEP 2: Handle root nodes (top-level permissions)
        // =================================================
        // If this permission IS the root itself
        if ($this->id === $root->id) {
            // Get current root counter or start at 0
            $rootCounter = $counters['root'] ?? 0;
            // Increment counter for this new root
            $rootCounter++;
            // Store updated counter
            $counters['root'] = $rootCounter;
            // Map this root's database ID to its sequential number (root1, root2, etc.)
            $counters['root_map'][$root->id] = $rootCounter;
            // Return simple root identifier
            return 'root' . $rootCounter;
        }


        // STEP 3: Get root number for non-root permissions
        // =================================================
        // Try to get the root number from our mapping (if already calculated)
        $rootNumber = $counters['root_map'][$root->id] ?? null;


        // If this root hasn't been numbered yet (shouldn't happen if roots processed first)
        if (!$rootNumber) {
            $rootCounter = $counters['root'] ?? 0;
            $rootCounter++;
            $counters['root'] = $rootCounter;
            $counters['root_map'][$root->id] = $rootCounter;
            $rootNumber = $rootCounter;
        }
        
        
        // STEP 4: Build the path from root to this permission
        // =================================================
        // Get ordered list of all ancestors including this node
        // Example: [root, branch, twig, leaf]
        $path = $this->getPathFromRoot();
        
        // STEP 5: Start building the tree_id with the root part
        // =================================================
        $treeId = 'root' . $rootNumber;  // Start with "root1", "root2", etc.
        $currentParent = $root;  // Track parent as we traverse down


        
        // STEP 6: Traverse each level and add appropriate segment
        // =================================================
        foreach ($path as $index => $node) {
            // Skip the root node (already added to treeId)
            if ($node->id === $root->id) continue;
            
            // Create unique key for counting siblings under same parent
            // Example: "15_23" where 15 is parent ID, 23 is current node ID
            $parentKey = $currentParent->id . '_' . $node->id;
            
            // Determine the level/depth of this node (1=branch, 2=twig, 3+=leaf)
            $level = $this->getLevel($node);
            
            // STEP 7: Add appropriate segment based on level
            // =================================================
            switch ($level) {
                case 1: // Branch level (direct children of root)
                    // Check if this branch under current parent already has a number
                    if (!isset($counters['branch'][$parentKey])) {
                        // Get or initialize counter for branches under this parent
                        $branchCounter = $counters['branch_total'][$currentParent->id] ?? 0;
                        $branchCounter++;  // Increment for new branch
                        $counters['branch_total'][$currentParent->id] = $branchCounter;
                        // Store the assigned number for this specific branch
                        $counters['branch'][$parentKey] = $branchCounter;
                    }
                    // Append branch segment (e.g., "-branch2")
                    $treeId .= '-branch' . $counters['branch'][$parentKey];
                    break;
                    
                case 2: // Twig level (children of branches)
                    // Similar logic for twig level
                    if (!isset($counters['twig'][$parentKey])) {
                        $twigCounter = $counters['twig_total'][$currentParent->id] ?? 0;
                        $twigCounter++;
                        $counters['twig_total'][$currentParent->id] = $twigCounter;
                        $counters['twig'][$parentKey] = $twigCounter;
                    }
                    // Append twig segment (e.g., "-twig1")
                    $treeId .= '-twig' . $counters['twig'][$parentKey];
                    break;
                    
                default: // Leaf level (depth 3 or more) and deeper
                    // Similar logic for leaf level
                    if (!isset($counters['leaf'][$parentKey])) {
                        $leafCounter = $counters['leaf_total'][$currentParent->id] ?? 0;
                        $leafCounter++;
                        $counters['leaf_total'][$currentParent->id] = $leafCounter;
                        $counters['leaf'][$parentKey] = $leafCounter;
                    }
                    // Append leaf segment (e.g., "-leaf3")
                    $treeId .= '-leaf' . $counters['leaf'][$parentKey];
                    break;
            }
            
            // STEP 8: Move down the tree for next iteration
            // =================================================
            // Set current node as the new parent for the next level
            $currentParent = $node;
        }
        
        // STEP 9: Return the complete tree_id
        // =================================================
        // Example: "root1-branch2-twig1-leaf3"
        return $treeId;
    }

    /**
     * Visual example of how tree_id is built:
     * 
     * Tree Structure:
     * root1 (ID: 1)
     * ├── branch1 (ID: 2)      <- level 1
     * │   ├── twig1 (ID: 4)     <- level 2  
     * │   │   └── leaf1 (ID: 7) <- level 3
     * │   └── twig2 (ID: 5)     <- level 2
     * └── branch2 (ID: 3)      <- level 1
     *     └── twig3 (ID: 6)     <- level 3 
     * 
     * Generated tree_ids:
     * - root1 (ID: 1)                 -> "root1"
     * - branch1 (ID: 2)               -> "root1-branch1"
     * - twig1 (ID: 4)                 -> "root1-branch1-twig1"
     * - leaf1 (ID: 7)                 -> "root1-branch1-twig1-leaf1"
     * - twig2 (ID: 5)                 -> "root1-branch1-twig2"
     * - branch2 (ID: 3)               -> "root1-branch2"
     * - twig3 (ID: 6)                 -> "root1-branch2-twig3"
     * 
     *




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