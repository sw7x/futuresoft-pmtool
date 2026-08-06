<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_phases', function (Blueprint $table) {
            $table->id();
            
            //------------$table->foreignId('project_id')->constrained('projects');

            // Phase details
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('progress', ['not_started', 'in_progress', 'completed', 'blocked', 'cancelled'])->default('not_started');
            
            // Scheduled Timeline
            $table->timestamp('scheduled_start')->nullable();
            $table->timestamp('scheduled_end')->nullable();
            
            // Actual Timeline
            $table->timestamp('actual_start')->nullable();
            $table->timestamp('actual_end')->nullable();
            
            $table->integer('order')->default(0); // For sorting phases
            
            $table->timestamps();
            
            //$table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_phases');
    }
}