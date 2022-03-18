<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentVehicleInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_vehicle_infos', function (Blueprint $table) {
            $table->id();
            // $table->string('vehicle_type');

            $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types')->onDelete('cascade');

            $table->string('vehicle_name');
            $table->string('model_year')->nullable();
            $table->string('make')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->foreignId('resident_data_id')->nullable()->constrained('resident_data')->onDelete('cascade');
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
        Schema::dropIfExists('resident_vehicle_infos');
    }
}
