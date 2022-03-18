<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_services', function (Blueprint $table) {
            $table->id();
            $table->string('billing_type');
            $table->boolean('status')->default(1);
            $table->double('price');
            $table->double('discount_amount')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->double('final_price');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('service_request_id')->constrained('request_services')->onDelete('cascade');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('sub_type_id')->nullable();
            $table->index('type_id');
            $table->index('sub_type_id');
            $table->foreignId('package_id')->constrained('service_packages')->onDelete('cascade');
            $table->boolean('is_payed')->default(0);
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
        Schema::dropIfExists('user_services');
    }
}
