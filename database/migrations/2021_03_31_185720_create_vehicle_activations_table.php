<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleActivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_activations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->uuid('user_id')->nullable();
            $table->uuid('vehicle_id')->nullable();
            $table->uuid('device_id')->nullable();
            $table->uuid('status_id')->nullable();
            $table->json('details')->nullable();
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
        Schema::dropIfExists('vehicle_activations');
    }
}
