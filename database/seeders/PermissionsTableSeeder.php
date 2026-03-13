<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Role as RoleModel;


class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing permissions if needed (optional)
        // DB::table('permissions')->truncate();
        
        $data = [
            // Root Level - Project Management (Root)
            ["id" => "root1", "parent" => "#", "text" => "Project Management", "type" => "root", "state" => ["checked" => true], "li_attr" => ["class" => "root", "data-key" => "PROJECT_MANAGEMENT", "data-access" => true]],
            
                // Project Management Children (Branch level)
                ["id" => "root1-branch1", "parent" => "root1", "text" => "Create Project", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "CREATE_PROJECT", "data-access" => false]],
                ["id" => "root1-branch2", "parent" => "root1", "text" => "View Projects", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "VIEW_PROJECTS", "data-access" => true]],
                ["id" => "root1-branch3", "parent" => "root1", "text" => "Edit Project", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "EDIT_PROJECT", "data-access" => true]],
                ["id" => "root1-branch4", "parent" => "root1", "text" => "Delete Project", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "DELETE_PROJECT", "data-access" => true]],
                ["id" => "root1-branch5", "parent" => "root1", "text" => "Assign PM", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "ASSIGN_PM", "data-access" => true]],
                ["id" => "root1-branch6", "parent" => "root1", "text" => "Assign Dev's", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "ASSIGN_DEV'S", "data-access" => true]],
                ["id" => "root1-branch7", "parent" => "root1", "text" => "View Project Plan", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "VIEW_PROJECT_PLAN", "data-access" => true]],
                ["id" => "root1-branch8", "parent" => "root1", "text" => "View Project Timeline", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "VIEW_PROJECT_TIMELINE", "data-access" => true]],
                
                
                // Project Thread (Branch level)
                ["id" => "root1-branch9", "parent" => "root1", "text" => "Project Thread", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "PROJECT_THREAD", "data-access" => true]],
                
                    // Project Thread Children (Twig level)
                    ["id" => "root1-branch9-twig1", "parent" => "root1-branch9", "text" => "Create", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "CREATE", "data-access" => true]],
                    ["id" => "root1-branch9-twig2", "parent" => "root1-branch9", "text" => "Post", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "POST", "data-access" => true]],
                    ["id" => "root1-branch9-twig3", "parent" => "root1-branch9", "text" => "Delete", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "DELETE", "data-access" => true]],
                    ["id" => "root1-branch9-twig4", "parent" => "root1-branch9", "text" => "View", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "VIEW", "data-access" => true]],
                    ["id" => "root1-branch9-twig5", "parent" => "root1-branch9", "text" => "Edit", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "EDIT", "data-access" => true]],

            
            // Root Level - Task Management (Root)
            ["id" => "root2", "parent" => "#", "text" => "Task Management", "type" => "root", "li_attr" => ["class" => "root", "data-key" => "TASK_MANAGEMENT", "data-access" => true]],
            
                // Task Management Children (Branch level)
                ["id" => "root2-branch1", "parent" => "root2", "text" => "Create Task", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "CREATE_TASK", "data-access" => true]],
                ["id" => "root2-branch2", "parent" => "root2", "text" => "View Tasks", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "VIEW_TASKS", "data-access" => true]],
                ["id" => "root2-branch3", "parent" => "root2", "text" => "Edit Task", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "EDIT_TASK", "data-access" => true]],
                ["id" => "root2-branch4", "parent" => "root2", "text" => "Delete Task", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "DELETE_TASK", "data-access" => true]],
                ["id" => "root2-branch5", "parent" => "root2", "text" => "Assign Dev's", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "ASSIGN_DEV'S", "data-access" => true]],
                
                // Task Thread (Branch level)
                ["id" => "root2-branch6", "parent" => "root2", "text" => "Task Thread", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "TASK_THREAD", "data-access" => true]],
                
                    // Task Thread Children (Twig level)
                    ["id" => "root2-branch6-twig1", "parent" => "root2-branch6", "text" => "Create", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "CREATE", "data-access" => true]],
                    
                    // Task Thread Post (Twig level with children)
                    ["id" => "root2-branch6-twig2", "parent" => "root2-branch6", "text" => "Post", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "POST", "data-access" => true]],
                    
                        // Task Thread Post Children (Leaf level)
                        ["id" => "root2-branch6-twig2-leaf1", "parent" => "root2-branch6-twig2", "text" => "Post Messages", "type" => "leaf", "li_attr" => ["class" => "leaf", "data-key" => "POST_MESSAGES", "data-access" => true]],
                        ["id" => "root2-branch6-twig2-leaf2", "parent" => "root2-branch6-twig2", "text" => "Reply", "type" => "leaf", "li_attr" => ["class" => "leaf", "data-key" => "REPLY", "data-access" => true]],
                        ["id" => "root2-branch6-twig2-leaf3", "parent" => "root2-branch6-twig2", "text" => "Quote", "type" => "leaf", "li_attr" => ["class" => "leaf", "data-key" => "QUOTE", "data-access" => true]],
                        
                    // More Task Thread Children (twig level)
                    ["id" => "root2-branch6-twig3", "parent" => "root2-branch6", "text" => "Delete", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "DELETE", "data-access" => true]],
                    ["id" => "root2-branch6-twig4", "parent" => "root2-branch6", "text" => "View", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "VIEW", "data-access" => true]],
                    ["id" => "root2-branch6-twig5", "parent" => "root2-branch6", "text" => "Edit", "type" => "twig", "li_attr" => ["class" => "twig", "data-key" => "EDIT", "data-access" => true]],

            
            // Root Level - User Management (Root)
            ["id" => "root3", "parent" => "#", "text" => "User Management", "type" => "root", "li_attr" => ["class" => "root", "data-key" => "USER_MANAGEMENT", "data-access" => true]],
            
                // User Management Children (Branch level)
                ["id" => "root3-branch1", "parent" => "root3", "text" => "Add Users", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "ADD_USERS", "data-access" => true]],
                ["id" => "root3-branch2", "parent" => "root3", "text" => "View Users", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "VIEW_USERS", "data-access" => true]],
                ["id" => "root3-branch3", "parent" => "root3", "text" => "Edit Users", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "EDIT_USERS", "data-access" => true]],
                ["id" => "root3-branch4", "parent" => "root3", "text" => "Delete Users", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "DELETE_USERS", "data-access" => true]],

            
            // Root Level - System Settings (Root)
            ["id" => "root4", "parent" => "#", "text" => "System Settings", "type" => "root", "li_attr" => ["class" => "root", "data-key" => "SYSTEM_SETTINGS", "data-access" => true]],
            
                // System Settings Children (Branch level)
                ["id" => "root4-branch1", "parent" => "root4", "text" => "Access System Logs", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "ACCESS_SYSTEM_LOGS", "data-access" => true]],
                ["id" => "root4-branch2", "parent" => "root4", "text" => "Manage Backups", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "MANAGE_BACKUPS", "data-access" => true]],
                ["id" => "root4-branch3", "parent" => "root4", "text" => "Access Analytics", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "ACCESS_ANALYTICS", "data-access" => true]],
                ["id" => "root4-branch4", "parent" => "root4", "text" => "Global Settings", "type" => "branch", "li_attr" => ["class" => "branch", "data-key" => "GLOBAL_SETTINGS", "data-access" => true]],

            
            // Root Level - Individual Items 
            ["id" => "root5", "parent" => "#", "text" => "Change Password", "type" => "root", "li_attr" => ["class" => "root", "data-key" => "CHANGE_PASSWORD", "data-access" => true]],
            ["id" => "root6", "parent" => "#", "text" => "View Permissions", "type" => "root", "li_attr" => ["class" => "root", "data-key" => "VIEW_PERMISSIONS", "data-access" => true]],
            ["id" => "root7", "parent" => "#", "text" => "Edit Profile", "type" => "root", "li_attr" => ["class" => "root", "data-key" => "EDIT_PROFILE", "data-access" => true]],
            ["id" => "root8", "parent" => "#", "text" => "View Dashboard", "type" => "root", "li_attr" => ["class" => "root", "data-key" => "VIEW_DASHBOARD", "data-access" => true]]
        ];



        $roleArr = RoleModel::getRoleOptions();

        foreach ($roleArr as $roleId => $roleName) {
            
            // First pass: Insert all permissions without parent_id (will be updated later)
            $insertedIds = [];
            
            foreach ($data as $item) {
                // Skip items with parent '#' for now - these are root items
                if ($item['parent'] === '#') {
                    $id = DB::table('permissions')->insertGetId([
                        'name'          => $item['text'],
                        'key'           => $item['li_attr']['data-key'],
                        'parent_id'     => null,
                        'access'        => $item['li_attr']['data-access'] ? 'allow' : 'deny',
                        'role_id'       => $roleId,
                        'status'        => true,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                    
                    // Store mapping between the original ID and the new DB ID
                    $insertedIds[$item['id']] = $id;
                }
            }
            //dump($insertedIds);



            // Second pass: Insert child permissions (those with parents)
            $childItems = array_filter($data, function($item) {
                return $item['parent'] !== '#';
            });

            foreach ($childItems as $childItem) {
                // Get the parent DB ID from our mapping
                $parentDbId = $insertedIds[$childItem['parent']] ?? null;
                
                if ($parentDbId) {
                    $id = DB::table('permissions')->insertGetId([
                        'name'          => $childItem['text'],
                        'key'           => $childItem['li_attr']['data-key'],
                        'parent_id'     => $parentDbId,
                        'access'        => $childItem['li_attr']['data-access'] ? 'allow' : 'deny',
                        'role_id'       => $roleId,
                        'status'        => true,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                    
                    // Store mapping for this child as well (in case it has its own children)
                    $insertedIds[$childItem['id']] = $id;
                }
            }
        }

        $this->command->info('Permissions seeded successfully!');
    }
}