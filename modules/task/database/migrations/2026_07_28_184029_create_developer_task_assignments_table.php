<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeveloperTaskAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

        Schema::create('developer_task_assignments', function (Blueprint $table) {
            $table->id();
                        
            // Notification flag
            $table->boolean('is_notify')->default(false);
            
            // Timestamps
            $table->timestamp('assigned_date_time')->nullable();
            $table->timestamp('finished_date_time')->nullable();
			$table->timestamp('stopped_date_time')->nullable();
            
            // Duration (spent time)
            $table->integer('spend_time')->nullable(); // Duration in minutes/hours

            // Progress status
            $table->enum('progress', ['not_started', 'in_progress', 'completed', 'blocked', 'cancelled','mixed']);
            
            
            //------------$table->foreignId('task_id')->constrained('tasks');
            //------------$table->foreignId('developer_project_enrollment_id')->nullable()->constrained('developer_project_enrollments');
			
            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Optional: Add unique constraint to prevent duplicate assignments
            // $table->unique(['task_id', 'developer_project_enrollment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('developer_task_assignments');
    }
}

