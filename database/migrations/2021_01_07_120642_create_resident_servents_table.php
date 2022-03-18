<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentServentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_servents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->string('servent_type');
            $table->string('father_name');

            $table->foreignId('servent_type_id')->nullable()->constrained('servent_types')->onDelete('cascade');
            $table->string('cnic')->nullable();
            $table->string('gender')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable(); 
            $table->string('image')->nullable();
            $table->string('occupation')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('resident_servents');
    }
}
