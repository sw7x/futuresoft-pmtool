<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyConstraintsToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        //db tables belongs to app      - not check existance
        //db tables belongs to modules  - check existance
        
        //users           - db tables belongs to app 
        //courses         - db tables belongs to modules 
        //projects,tasks  - db tables belongs to modules

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasTable('designations')) {
                $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
            }
        });
        
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {                
                $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            });
        }       
        
        /*
        if (Schema::hasTable('projects') && Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {                
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            });
        }
        */

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {            
            $table->dropForeign(['designation_id']);                      
        });
        
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->dropForeign(['author_id']);
            });
        }
        
        /*        
        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {         
                $table->dropForeign(['project_id']);                      
            });
        }
        */


    }
}




