<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('invoice_type', ['income', 'cost'])->default('income');
            $table->enum('cost_category', ['employee_cost', 'infrastructure_cost', 'third_party_services','other'])->nullable();
            
            // Financial Details
            $table->string('currency')->default('USD');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['pending','bank_transfer', 'cash', 'cheque', 'online_payment'])->default('pending');
            $table->string('transaction_reference')->nullable();
            
            // Important Dates
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            
            // Attachments
            $table->string('proof_of_payment')->nullable(); // Store file path
            
            // Status
            $table->enum('progress_level', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
        
            //------------$table->foreignId('project_id')->constrained('projects');
                        
            // Timestamps
            $table->timestamps();         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}





