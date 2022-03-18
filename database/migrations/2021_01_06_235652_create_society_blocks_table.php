<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocietyBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('society_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('block_name');
            $table->string('slug')->unique();
            $table->foreignId('society_id')->nullable()->constrained('societies')->onDelete('cascade');

            $table->foreignId('society_sector_id')->nullable()->constrained('society_sectors')->onDelete('cascade');
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
        Schema::dropIfExists('society_blocks');
    }
}
