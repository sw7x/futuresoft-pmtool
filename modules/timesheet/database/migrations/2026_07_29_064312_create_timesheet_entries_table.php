<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_entries', function (Blueprint $table) {
            // Primary Key
            $table->id();                   
            
            //------------$table->foreignId('task_assignment_id')->constrained('developer_task_assignments');
            
            // Date and Time Information
            $table->date('date')->nullable(false);
            
            $table->integer('spend_time')->nullable(); // Store in minutes                        
            
            $table->text('comment')->nullable();
            
            $table->text('reviewer_comment')->nullable();
            
            
            // Relationships (Foreign Keys)
            //------------$table->foreignId('timesheet_id')->constrained('timesheets');

            // Timestamps
            $table->timestamps(); // created_at, updated_at
            
                

            // Indexes for better performance
            //$table->index(['timesheet_id', 'date']);
            //$table->index(['task_assignment_id', 'date']);
            //$table->index(['date']);
            //$table->unique(['timesheet_id', 'task_assignment_id', 'date'], 'unique_timesheet_record');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheet_entries');
    }
}
