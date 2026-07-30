<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectThreadMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_thread_messages', function (Blueprint $table) {
            $table->id();
            
            // Message content
            $table->text('message');
            $table->timestamp('posted_date_time')->useCurrent();
            
            // Thread relationship (required)
            //------------$table->foreignId('project_thread_id')->constrained('project_threads');
            
            // Self-referencing for replies (optional)
            //------------$table->foreignId('replied_to_message_id')->nullable()->constrained('project_thread_messages');
            
            // Who posted this message
            //------------$table->foreignId('posted_by')->constrained('users');
            
                
            // Indexes for performance
            //$table->index(['project_thread_id', 'posted_date_time']);
            //$table->index('replied_to_message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_thread_messages');
    }
}
