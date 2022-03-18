<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentalServiceRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('departmental_service_request_logs');
        Schema::create('departmental_service_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('departmental_service_requests')->onDelete('cascade');
            $table->date('request_date')->nullable();
            $table->tinyInteger('request_status')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('refer_to')->nullable();
            $table->unsignedBigInteger('refer_by')->nullable();
            $table->unsignedBigInteger('addedby')->nullable();
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('departmental_service_request_logs');
    }
}
