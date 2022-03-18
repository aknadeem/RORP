<?php

use App\Helpers\Constant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentalServiceRequestsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('departmental_service_requests');
        Schema::create('departmental_service_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('sub_department_id')->nullable();
            $table->foreignId('departmental_service_id')->nullable()->constrained('departmental_services')->onDelete('cascade');
            $table->date('request_date')->nullable();
            $table->tinyInteger('request_status')->default(Constant::REQUEST_STATUS['Open']);
            $table->boolean('is_active')->default(1)->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('refer_to')->nullable();
            $table->unsignedBigInteger('refer_by')->nullable();
            $table->unsignedBigInteger('addedby')->nullable();
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departmental_service_requests');
    }
}
