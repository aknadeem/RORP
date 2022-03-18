<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwoFourSevensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('two_four_sevens', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('cascade');

            $table->foreignId('sub_department_id')->nullable()->constrained('sub_departments')->onDelete('cascade');

            $table->foreignId('hod_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('two_four_sevens');
    }
}
