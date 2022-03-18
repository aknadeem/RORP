<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_services', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('open');
            $table->string('service_type')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('sub_type_id')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('service_packages')->onDelete('cascade');
            $table->foreignId('device_id')->nullable()->constrained('service_devices')->onDelete('cascade');
            $table->unsignedBigInteger('addedby')->nullable();
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->unsignedBigInteger('refer_to')->nullable();
            $table->unsignedBigInteger('refer_by')->nullable();
            $table->index('refer_to');
            $table->index('refer_by');
            $table->boolean('is_active')->default(1);
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_invoiced')->default(0);
            $table->boolean('is_paid')->default(0);
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
        Schema::dropIfExists('request_services');
    }
}
