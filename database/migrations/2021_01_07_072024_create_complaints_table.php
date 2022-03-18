<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_title');
            $table->string('complaint_type')->nullable();
            $table->string('complaint_location')->nullable();
            $table->text('complaint_description')->nullable();
            $table->string('complaint_status')->default('open');

            $table->string('poc_name')->nullable();
            $table->string('poc_number')->nullable();
            $table->string('user_type')->nullable();

            // $table->integer('department_id');
            // $table->integer('sub_department_id');

            $table->string('image')->nullable();

            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('cascade');

            $table->foreignId('sub_department_id')->nullable()->constrained('sub_departments')->onDelete('cascade');

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
        Schema::dropIfExists('complaints');
    }
}
