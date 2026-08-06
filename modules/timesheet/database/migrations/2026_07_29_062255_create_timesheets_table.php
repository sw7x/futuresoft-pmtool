<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheets', function (Blueprint $table) {
            // Primary Key
            $table->id();

            $table->enum('timesheet_type', ['regular', 'overtime', 'compensatory'])->default('regular');
            
            // Time Period
            $table->date('week_start_date')->nullable(false);            
            $table->date('week_end_date')->nullable(false);
            
            // Status Fields
            $table->enum('progress', ['pending', 'approved', 'rejected', 'draft'])->default('pending');
                        
            // Submission Details
            $table->timestamp('submitted_at')->nullable();            
            //------------$table->foreignId('submitted_by')->nullable()->constrained('users');

            // Review Details
            $table->timestamp('reviewed_at')->nullable();
            //------------$table->foreignId('reviewed_by')->nullable()->constrained('users');              
            
            // Timestamps
            $table->timestamps(); // created_at, updated_at
            
            // Soft Delete
            $table->softDeletes(); // deleted_at          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheets');
    }
}




