<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_threads', function (Blueprint $table) {
            $table->id();
            
            // Thread metadata
            $table->string('title')->nullable();
            $table->enum('status', ['enable', 'disable'])->default('enable');
            
            // Relationships
            //------------$table->foreignId('project_id')->constrained('projects');
            //------------$table->foreignId('posted_by')->constrained('users');
            
            // Timestamps
            $table->timestamps();

            $table->softDeletes();
            
            // Optional: add indexes for performance
            //$table->index(['project_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_threads');
    }
}
