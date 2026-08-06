<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateMessageThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_message_threads', function (Blueprint $table) {
            $table->id();
            
            // Thread metadata
            $table->string('title')->nullable();
            $table->enum('status', ['enable', 'disable'])->default('enable');
            
            // Relationships
            //------------$table->foreignId('created_by')->constrained('users');
            //------------$table->foreignId('send_to')->constrained('users');
            
            // Timestamps
            $table->timestamps();

            $table->softDeletes();
            
            // Optional: add indexes for performance
            //$table->index(['created_by', 'send_to', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('private_message_threads');
    }
}
