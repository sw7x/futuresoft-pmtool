<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeveloperProjectEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developer_project_enrollments', function (Blueprint $table) {
            $table->id();

            // Assignment Details
            $table->boolean('is_notify')->default(false); // Whether to send notification
            $table->timestamp('assigned_date_time');
            $table->timestamp('unassigned_date_time')->nullable();            
            $table->text('message')->nullable();
            
                
            //------------$table->foreignId('project_id')->constrained('projects');

            //------------$table->foreignId('developer_id')->constrained('users');

            // Timestamps
            $table->timestamps();
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
        Schema::dropIfExists('developer_project_enrollments');
    }
}
