<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designation_user', function (Blueprint $table) {
            $table->id();
            
            //------------$table->foreignId('user_id')->constrained();            
            //------------$table->foreignId('designation_id')->constrained();
            
            // Track when designation was assigned
            $table->timestamp('assigned_at')->useCurrent();
            
            $table->timestamps();
            
            // Prevent duplicate assignments
            //$table->unique(['user_id', 'designation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designation_user');
    }
}


        