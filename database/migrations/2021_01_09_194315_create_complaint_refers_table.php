<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintRefersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_refers', function (Blueprint $table) {
            $table->id();
            $table->integer('complaint_id');
            $table->foreignId('refer_to')->nullable()->constrained('users')->onDelete('cascade'); 
            $table->foreignId('refer_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('complaint_refers');
    }
}
