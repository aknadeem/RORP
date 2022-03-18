<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentalServicesTable extends Migration
{
    public function up()
    {
        Schema::create('departmental_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('cascade');
            $table->foreignId('sub_department_id')->nullable()->constrained('sub_departments')->onDelete('cascade');
            $table->string('service_title')->nullable();
            $table->decimal('service_charges')->nullable();
            $table->tinyInteger('charges_type')->nullable();
            $table->boolean('is_active')->default(1)->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('addedby')->nullable();
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('departmental_services');
    }
}
