<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique()->nullable();
            $table->integer('resident_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('user_type')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('work_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(1);

            $table->foreignId('society_id')->constrained('societies')->onDelete('cascade');

            $table->foreignId('society_sector_id')->nullable()->constrained('society_sectors')->onDelete('cascade');
            
            $table->foreignId('user_level_id')->constrained('user_levels')->onDelete('cascade');
            $table->unsignedBigInteger('addedby')->nullable();
            $table->unsignedBigInteger('updatedby')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('device')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
