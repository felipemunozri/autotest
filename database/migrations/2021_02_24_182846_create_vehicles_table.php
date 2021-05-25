<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->id();
            $table->uuid('tenant_id')->nullable();
            $table->uuid('country_id')->nullable();
            $table->uuid('vehicle_type_id')->nullable();
            $table->uuid('card_brand_id')->nullable();
            $table->string('plate_number');
            $table->string('model')->nullable();
            $table->uuid('model_id')->nullable();
            $table->integer('year')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('color_code')->nullable();
            $table->string('color')->nullable();
            $table->integer('engine_capacity')->nullable();
            $table->string('drive')->nullable();
            $table->uuid('fuel_type_id')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_dni')->nullable();
            $table->date('owner_adquisition_date')->nullable();
            $table->string('key')->nullable();
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
        Schema::dropIfExists('vehicles');
    }
}
