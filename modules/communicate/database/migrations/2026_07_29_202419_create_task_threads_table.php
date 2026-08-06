<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_threads', function (Blueprint $table) {
            $table->id();
            
            // Thread metadata
            $table->string('title')->nullable();
            $table->enum('status', ['enable', 'disable'])->default('enable');
            
            // Relationships
            //------------$table->foreignId('task_id')->constrained('tasks');
            //------------$table->foreignId('posted_by')->constrained('users');
            
            // Timestamps
            $table->timestamps();

            $table->softDeletes();
            
            // Optional: add indexes for performance
            //$table->index(['task_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_threads');
    }
}
