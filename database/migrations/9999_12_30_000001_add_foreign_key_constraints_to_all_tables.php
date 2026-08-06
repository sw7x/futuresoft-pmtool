<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyConstraintsToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        //db tables belongs to app      - not check existance
        //db tables belongs to modules  - check existance        
        //users           - db tables belongs to app 
        
        

        //===============Communicate module ==============
        if (Schema::hasTable('project_threads') && Schema::hasTable('projects') && Schema::hasTable('users')) {
            Schema::table('project_threads', function (Blueprint $table) {
                $table->foreignId('project_id')->constrained('projects');
                $table->foreignId('posted_by')->constrained('users');       
                
                $table->index(['project_id', 'status']);
            });
        }

        if (Schema::hasTable('project_thread_messages') && Schema::hasTable('project_threads') && Schema::hasTable('users')) {
            Schema::table('project_thread_messages', function (Blueprint $table) {                
                $table->foreignId('project_thread_id')->constrained('project_threads');                
                $table->foreignId('replied_to_message_id')->nullable()->constrained('project_thread_messages');                
                $table->foreignId('posted_by')->constrained('users');                
            });
        }


        if (Schema::hasTable('task_threads') && Schema::hasTable('tasks') && Schema::hasTable('users')) {
            Schema::table('task_threads', function (Blueprint $table) {                            
                $table->foreignId('task_id')->constrained('tasks');
                $table->foreignId('posted_by')->constrained('users');           
                                
                $table->index(['task_id', 'status']);
            });
        }

        if (Schema::hasTable('task_thread_messages') && Schema::hasTable('task_threads') && Schema::hasTable('users')) {
            Schema::table('task_thread_messages', function (Blueprint $table) {
                $table->foreignId('task_thread_id')->constrained('task_threads');                
                $table->foreignId('replied_to_message_id')->nullable()->constrained('task_thread_messages');
                $table->foreignId('posted_by')->constrained('users');
            });
        }

        if (Schema::hasTable('private_message_threads') && Schema::hasTable('users')) {
            Schema::table('private_message_threads', function (Blueprint $table) {
                $table->foreignId('created_by')->constrained('users');
                $table->foreignId('send_to')->constrained('users');
                                        
                $table->index(['created_by', 'send_to', 'status']);
            });
        }

        if (Schema::hasTable('private_messages') && Schema::hasTable('private_message_threads') && Schema::hasTable('users')) {
            Schema::table('private_messages', function (Blueprint $table) {
                $table->foreignId('private_message_thread_id')->constrained('private_message_threads');                
                $table->foreignId('replied_to_private_message_id')->nullable()->constrained('private_messages');                
                $table->foreignId('posted_by')->constrained('users');                                
            });
        }


        //=========== Employee module ================
        if (Schema::hasTable('designations')) {
            Schema::table('designations', function (Blueprint $table) {           
                // Hierarchical Relationship
                $table->foreignId('parent_id')->nullable()->constrained('designations');    //->nullOnDelete();                
            });
        }

        if (Schema::hasTable('designation_user') && Schema::hasTable('designations') && Schema::hasTable('users')) {
            Schema::table('designation_user', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained('users');            
                $table->foreignId('designation_id')->constrained('designations');
                
                // Prevent duplicate assignments
                $table->unique(['user_id', 'designation_id']);
            });
        }


        //===================== leave module =======================
        if (Schema::hasTable('leaves') && Schema::hasTable('users')) {
            Schema::table('leaves', function (Blueprint $table) {           
                $table->foreignId('applied_by')->constrained('users');
                $table->foreignId('responded_by')->nullable()->constrained('users');
            });
        }


        //===================== Project module =======================
        if (Schema::hasTable('projects') && Schema::hasTable('clients') && Schema::hasTable('users')) {
            Schema::table('projects', function (Blueprint $table) {                
                $table->foreignId('pm_id')->constrained('users');
                $table->foreignId('client_id')->constrained('clients');
            });
        }

        if (Schema::hasTable('project_phases') && Schema::hasTable('projects')) {
            Schema::table('project_phases', function (Blueprint $table) {
                $table->foreignId('project_id')->constrained('projects');
            });
        }

        if (Schema::hasTable('invoices') && Schema::hasTable('projects')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->foreignId('project_id')->constrained('projects');                        
            });
        }

        if (Schema::hasTable('developer_project_enrollments') && Schema::hasTable('projects') && Schema::hasTable('users')) {
            Schema::table('developer_project_enrollments', function (Blueprint $table) {
                $table->foreignId('project_id')->constrained('projects');
                $table->foreignId('developer_id')->constrained('users');                         
            });
        }

        //===================== Task module =======================
        if (Schema::hasTable('tasks') && Schema::hasTable('projects')) {
            Schema::table('tasks', function (Blueprint $table) {
                // Only one level of nesting allowed (parent tasks can have children, but children cannot have sub-children).
                $table->foreignId('parent_task_id')->nullable()->constrained('tasks');
                
				$table->foreignId('project_id')->constrained('projects');            
            });
        }

        if (Schema::hasTable('developer_task_assignments') && Schema::hasTable('tasks') && Schema::hasTable('developer_project_enrollments')) {
            Schema::table('developer_task_assignments', function (Blueprint $table) {
                $table->foreignId('task_id')->constrained('tasks');
                
                //   ======     $table->foreignId('developer_project_enrollment_id')->constrained('developer_project_enrollments');

                // For developer_project_enrollment_id with custom constraint name
                $table->foreignId('developer_project_enrollment_id')->nullable();

                $table->foreign('developer_project_enrollment_id', 'dev_task_assign_enrollment_foreign')
                    ->references('id') // or whatever column it references
                    ->on('developer_project_enrollments');    


                // Optional: Add unique constraint to prevent duplicate assignments
                // 'dev_task_assign_unique' - This is the custom name given to the unique index.
                $table->unique(['task_id', 'developer_project_enrollment_id'], 'dev_task_assign_unique');
            });
        }

        if (Schema::hasTable('task_assignment_messages') && Schema::hasTable('developer_task_assignments') && Schema::hasTable('users')) {
            Schema::table('task_assignment_messages', function (Blueprint $table) {
                $table->foreignId('task_assignment_id')->constrained('developer_task_assignments');
                $table->foreignId('posted_by')->constrained('users');
            });
        }


        //===================== Timesheet module =======================
        if (Schema::hasTable('timesheets') && Schema::hasTable('users')) {
            Schema::table('timesheets', function (Blueprint $table) {
                $table->foreignId('submitted_by')->nullable()->constrained('users');
                $table->foreignId('reviewed_by')->nullable()->constrained('users');              
            });
        }

        if (Schema::hasTable('timesheet_entries') && Schema::hasTable('developer_task_assignments') && Schema::hasTable('timesheets')) {
            Schema::table('timesheet_entries', function (Blueprint $table) {
                $table->foreignId('task_assignment_id')->constrained('developer_task_assignments');                
                $table->foreignId('timesheet_id')->constrained('timesheets');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        //===================== TIMESHEET =====================
        if (Schema::hasTable('timesheet_entries') && Schema::hasTable('developer_task_assignments') && Schema::hasTable('timesheets')) {
            Schema::table('timesheet_entries', function (Blueprint $table) {
                $table->dropForeign(['task_assignment_id']);
                $table->dropForeign(['timesheet_id']);
                $table->dropColumn(['task_assignment_id', 'timesheet_id']);
            });
        }

        if (Schema::hasTable('timesheets') && Schema::hasTable('users')) {
            Schema::table('timesheets', function (Blueprint $table) {
                $table->dropForeign(['submitted_by']);
                $table->dropForeign(['reviewed_by']);
                $table->dropColumn(['submitted_by', 'reviewed_by']);
            });
        }

        
        //===================== TASK =====================
        if (Schema::hasTable('task_assignment_messages') && Schema::hasTable('developer_task_assignments') && Schema::hasTable('users')) {
            Schema::table('task_assignment_messages', function (Blueprint $table) {
                $table->dropForeign(['task_assignment_id']);
                $table->dropForeign(['posted_by']);
                $table->dropColumn(['task_assignment_id', 'posted_by']);
            });
        }

        if (Schema::hasTable('developer_task_assignments') && Schema::hasTable('tasks') && Schema::hasTable('developer_project_enrollments')) {
            Schema::table('developer_task_assignments', function (Blueprint $table) {
                // Drop foreign keys FIRST — they depend on the unique index,
                // so MySQL won't let the index be dropped while they still exist
                $table->dropForeign(['task_id']);
                $table->dropForeign('dev_task_assign_enrollment_foreign');

                // Now it's safe to drop the unique constraint
                $table->dropUnique('dev_task_assign_unique');

                // Drop both columns
                $table->dropColumn(['task_id', 'developer_project_enrollment_id']);                
            });
        }

        if (Schema::hasTable('tasks') && Schema::hasTable('projects')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropForeign(['parent_task_id']);
                $table->dropForeign(['project_id']);
                $table->dropColumn(['parent_task_id', 'project_id']);
            });
        }

    
        //===================== PROJECT =====================
        if (Schema::hasTable('developer_project_enrollments') && Schema::hasTable('projects') && Schema::hasTable('users')) {
            Schema::table('developer_project_enrollments', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
                $table->dropForeign(['developer_id']);
                $table->dropColumn(['project_id', 'developer_id']);
            });
        }
        
        if (Schema::hasTable('invoices') && Schema::hasTable('projects')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            });
        }

        if (Schema::hasTable('project_phases') && Schema::hasTable('projects')) {
            Schema::table('project_phases', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            });
        }

        if (Schema::hasTable('projects') && Schema::hasTable('clients') && Schema::hasTable('users')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropForeign(['pm_id']);
                $table->dropForeign(['client_id']);
                $table->dropColumn(['pm_id', 'client_id']);
            });
        }


        //===================== LEAVE =====================
        if (Schema::hasTable('leaves') && Schema::hasTable('users')) {
            Schema::table('leaves', function (Blueprint $table) {
                $table->dropForeign(['applied_by']);
                $table->dropForeign(['responded_by']);
                $table->dropColumn(['applied_by', 'responded_by']);
            });
        }


        //===================== EMPLOYEE =====================
        if (Schema::hasTable('designation_user') && Schema::hasTable('designations') && Schema::hasTable('users')){
            Schema::table('designation_user', function (Blueprint $table) {

                $table->dropForeign(['user_id']);
                $table->dropForeign(['designation_id']);
                $table->dropUnique(['user_id', 'designation_id']);
                $table->dropColumn(['user_id', 'designation_id']);
            });
        }

        if (Schema::hasTable('designations')) {
            Schema::table('designations', function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            });
        }


        //===================== MESSAGING & THREADS =====================
        if (Schema::hasTable('private_messages') && Schema::hasTable('private_message_threads') && Schema::hasTable('users')) {
            Schema::table('private_messages', function (Blueprint $table) {
                $table->dropForeign(['private_message_thread_id']);
                $table->dropForeign(['replied_to_private_message_id']);
                $table->dropForeign(['posted_by']);
                $table->dropColumn(['private_message_thread_id', 'replied_to_private_message_id', 'posted_by']);
            });
        }

        if (Schema::hasTable('private_message_threads') && Schema::hasTable('users')) {
            Schema::table('private_message_threads', function (Blueprint $table) {                

                $table->dropForeign(['created_by']);
                $table->dropForeign(['send_to']);

                $table->dropIndex(['created_by', 'send_to', 'status']);

                $table->dropColumn(['created_by', 'send_to']);
            });
        }

        if (Schema::hasTable('task_thread_messages') && Schema::hasTable('task_threads') && Schema::hasTable('users')) {
            Schema::table('task_thread_messages', function (Blueprint $table) {
                $table->dropForeign(['task_thread_id']);
                $table->dropForeign(['replied_to_message_id']);
                $table->dropForeign(['posted_by']);
                $table->dropColumn(['task_thread_id', 'replied_to_message_id', 'posted_by']);
            });
        }

        if (Schema::hasTable('task_threads') && Schema::hasTable('tasks') && Schema::hasTable('users')) {
            Schema::table('task_threads', function (Blueprint $table) {
                $table->dropForeign(['task_id']);
                $table->dropForeign(['posted_by']);

                $table->dropIndex(['task_id', 'status']);
                
                $table->dropColumn(['task_id', 'posted_by']);
            });
        }

        if (Schema::hasTable('project_thread_messages') && Schema::hasTable('project_threads') && Schema::hasTable('users')) {
            Schema::table('project_thread_messages', function (Blueprint $table) {
                $table->dropForeign(['project_thread_id']);
                $table->dropForeign(['replied_to_message_id']);
                $table->dropForeign(['posted_by']);
                $table->dropColumn(['project_thread_id', 'replied_to_message_id', 'posted_by']);
            });
        }

        if (Schema::hasTable('project_threads') && Schema::hasTable('projects') && Schema::hasTable('users')) {
            Schema::table('project_threads', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
                $table->dropForeign(['posted_by']);

                $table->dropIndex(['project_id', 'status']);

                $table->dropColumn(['project_id', 'posted_by']);
            });
        }     
        
    }
}

