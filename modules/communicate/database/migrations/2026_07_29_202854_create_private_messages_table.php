<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_messages', function (Blueprint $table) {
            $table->id();
            
            // Message content
            $table->text('message');
            $table->timestamp('posted_date_time')->useCurrent();
            
            // Thread relationship (required)
            //------------$table->foreignId('private_message_thread_id')->constrained('private_message_threads');
            
            // Self-referencing for replies (optional)
            //------------$table->foreignId('replied_to_private_message_id')->nullable()->constrained('private_messages');
            
            // Who posted this message
            //------------$table->foreignId('posted_by')->constrained('users');
            
                
            // Indexes for performance
            //$table->index(['private_message_thread_id', 'posted_date_time']);
            //$table->index('replied_to_private_message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('private_messages');
    }
}
