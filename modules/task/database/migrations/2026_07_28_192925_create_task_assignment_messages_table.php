<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskAssignmentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_assignment_messages', function (Blueprint $table) {
            $table->id();
            
            // Message content
            $table->text('message');
            
            // Timestamp
            $table->timestamp('posted_date_time');
            
            $table->boolean('is_edited')->default(false);                
            
            //------------$table->foreignId('task_assignment_id')->constrained('developer_task_assignments');
            //------------$table->foreignId('posted_by')->constrained('users');

            $table->timestamps();                  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_assignment_messages');
    }
}






