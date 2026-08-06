<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('address')->nullable();
            $table->string('phone', 20);
            $table->text('nic')->unique();
            $table->text('profile_pic')->nullable();

            $table->timestamp('date_of_joined')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->text('epf_etf_details')->nullable();
            $table->text('edu_qualifications')->nullable();
            $table->text('skills')->nullable();

            //$table->boolean('account_status')->default(true);
            $table->enum('employment_status', ['pending', 'active', 'resigned', 'terminated']);
            $table->timestamp('termination_date')->nullable();
        });            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'address',
                'phone',
                'nic',
                'profile_pic',
                'date_of_joined',
                'hourly_rate',
                'monthly_salary',
                'epf_etf_details',
                'edu_qualifications',
                'skills',
                //'account_status',
                'employment_status',
                'termination_date'
            ]);
        });
    }
}
