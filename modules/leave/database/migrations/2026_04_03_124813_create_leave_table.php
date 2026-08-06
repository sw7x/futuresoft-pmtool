<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            // Primary identifier
            $table->id();
                        
            $table->date('start_date');
            $table->date('end_date')->nullable();  // if leave has one day then this is null
            
            // Leave type and status
            $table->enum('leave_type', ['annual', 'casual', 'medical', 'other'])->default('annual');

            $table->enum('progress', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            
            // Time period details
            $table->enum('time_period', [
                'full_day',
                'half_day_morning',
                'half_day_afternoon',
                'short_leave',
                'custom_time'
            ])->default('full_day');

            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();
                        
            $table->timestamp('applied_at')->useCurrent();
            //------------$table->foreignId('applied_by')->constrained('users');            
            $table->text('reason')->nullable();
            

            $table->timestamp('responded_at')->nullable();
            //------------$table->foreignId('responded_by')->nullable()->constrained('users');
            $table->text('response_message')->nullable();


            // Timestamps
            $table->timestamps();
            
            // Soft delete for record keeping
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
        Schema::dropIfExists('leaves');
    }
}




