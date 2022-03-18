<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RequestsDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('requests_devices', function (Blueprint $table) {
            $table->foreignId('request_service_id')->constrained('request_services')->onDelete('cascade');
            $table->foreignId('device_id')->constrained('service_devices')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('requests_devices');
    }
}
