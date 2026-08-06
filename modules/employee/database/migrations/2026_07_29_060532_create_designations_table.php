<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designations', function (Blueprint $table) {
            // Primary Key
            $table->id();
            
            // Core Fields
            $table->string('name', 100)->unique();
            $table->string('short_code', 20)->nullable()->unique();


            $table->text('description')->nullable();
            
            // Hierarchical Relationship
            //------------$table->foreignId('parent_id')->nullable()->constrained('designations');
            //->nullOnDelete();                  
            
            $table->enum('status', ['enable', 'disable'])->default('enable');

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
        Schema::dropIfExists('designations');
    }
}
