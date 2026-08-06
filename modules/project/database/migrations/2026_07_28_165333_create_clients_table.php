<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            
            // Client Information
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->enum('client_type', ['initial', 'company'])->default('initial');
            
            // Contact & Location
            $table->string('email');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            
            // Additional Information
            $table->text('description')->nullable();
            $table->text('comments')->nullable();

            // Status
            $table->enum('status', ['enable', 'disable'])->default('enable');
                
            // Profile Picture
            $table->string('profile_image')->nullable(); // Store image path
                                    
            // Timestamps
            $table->timestamps();
            
            // Soft Deletes (optional but recommended)
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
        Schema::dropIfExists('clients');
    }
}
