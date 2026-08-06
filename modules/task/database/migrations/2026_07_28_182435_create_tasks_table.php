<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            
            // Basic task fields
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('estimate_time')->nullable(); // Store in minutes
            $table->timestamp('deadline')->nullable();            
            
            // Priority with enum
            $table->enum('priority', ['critical', 'high', 'medium', 'low']);
            
            // Status (enable/disable)
            $table->enum('status', ['enable', 'disable'])->default('enable');
            
            
            // Only one level of nesting allowed (parent tasks can have children, but children cannot have sub-children).
            //FK to parfent task
            //------------$table->foreignId('parent_task_id')->nullable()->constrained('tasks');

            // FK to projects table
            //------------$table->foreignId('project_id')->constrained('projects');

            // Timestamps
            $table->timestamps();

            // Soft Deletes (optional but recommended)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}




