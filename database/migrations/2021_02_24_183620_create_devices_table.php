<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->id();
            $table->string('serial_number')->nullable();
            $table->uuid('device_model_id')->nullable();
            $table->string('code')->nullable();
            $table->uuid('tenant_id')->nullable();
            $table->uuid('vehicle_id')->nullable();
            $table->uuid('device_status_id')->nullable();
            $table->string('password')->nullable();
            $table->json('detail')->nullable();
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
        Schema::dropIfExists('devices');
    }
}
