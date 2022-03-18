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
            $table->string('invoice_type')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('request_service_id')->nullable()->constrained('request_services')->onDelete('cascade');
            $table->foreignId('user_service_id')->nullable()->constrained('user_services')->onDelete('cascade');
            $table->date('due_date')->nullable();
            $table->boolean('is_payed')->default(0);
            $table->string('status')->nullable();
            $table->date('pay_date')->nullable();
            $table->double('price');
            $table->double('discount_amount')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->double('final_price');
            $table->double('remaining_amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->unsignedBigInteger('addedby')->nullable();
            $table->unsignedBigInteger('updatedby')->nullable();
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
