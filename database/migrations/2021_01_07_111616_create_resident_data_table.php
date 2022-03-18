<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResidentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_data', function (Blueprint $table) {
            $table->id();
            $table->integer('e_pin')->nullable();
            $table->integer('m_pin')->nullable();
            $table->boolean('pin_verified')->nullable();
            $table->string('type');
            $table->string('name');
            $table->string('father_name');
            $table->text('password');
            $table->string('cnic')->nullable();
            $table->string('landlord_name')->nullable();
            $table->unsignedBigInteger('landlord_id')->nullable();
            $table->string('mobile_number');
            $table->string('emergency_contact')->nullable();
            $table->string('email')->nullable();
            $table->string('occuptaion')->nullable();  
            $table->string('gender')->nullable();
            $table->string('martial_status')->nullable();
            $table->string('image')->nullable();

            $table->string('business_number')->nullable();
            $table->boolean('is_active')->default(0);

            $table->foreignId('society_id')->nullable()->constrained('societies')->onDelete('cascade');
            $table->foreignId('society_sector_id')->nullable()->constrained('society_sectors')->onDelete('cascade');

            $table->text('address')->nullable();
            $table->text('previous_address')->nullable();
            $table->text('business_address')->nullable();
            $table->text('mail_address')->nullable();

            $table->unsignedBigInteger('addedby')->nullable();
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->dateTime('pin_verified_at')->nullable();

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
        Schema::dropIfExists('resident_data');
    }
}
