<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            // Project Identity
            $table->string('name');
            $table->text('description')->nullable();
            
            // Timeline & Deadlines
            $table->timestamp('start_at')->nullable();
            $table->timestamp('planned_to_delivery_at')->nullable();
            $table->timestamp('actual_delivery_at')->nullable();
            $table->timestamp('deadline')->nullable();
            
            // Finance & Billing
            $table->string('currency')->default('USD');
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->decimal('revenue', 15, 2)->nullable();
            $table->enum('billing_type', ['fixed_cost', 'time_material'])->nullable();
            $table->enum('payment_status', ['not_invoiced', 'partially_paid', 'paid'])->nullable();
            
            // Classification & Status
            $table->enum('locality', ['local', 'foreign'])->nullable();
            $table->enum('project_type', ['internal', 'client', 'rd', 'maintenance'])->default('client');
            $table->enum('project_category', ['software', 'infrastructure', 'marketing', 'hr', 'other'])->default('software');
            $table->enum('priority', ['critical', 'high', 'medium', 'low'])->nullable();
            $table->enum('status', ['enable', 'disable'])->default('enable');
            $table->enum('progress', ['not_started', 'in_progress', 'completed', 'blocked', 'cancelled'])->default('not_started');
            
            // Documentation
            $table->longText('documentation')->nullable();
            


            // PM ID (Project Manager)
            //------------$table->foreignId('pm_id')->constrained('users');

            // NEW: Client ID
            //------------$table->foreignId('client_id')->constrained('clients');

            
            
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
        Schema::dropIfExists('projects');
    }
}








