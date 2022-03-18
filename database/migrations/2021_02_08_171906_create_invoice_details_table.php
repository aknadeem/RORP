<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('cascade');
            $table->foreignId('device_id')->nullable()->constrained('service_devices')->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained('service_packages')->onDelete('cascade');
            $table->foreignId('user_service_id')->nullable()->constrained('user_services')->onDelete('cascade');
            $table->double('price');
            $table->double('discount_amount')->nullable();
            $table->string('title')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->double('final_price');
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
        Schema::dropIfExists('invoice_details');
    }
}
