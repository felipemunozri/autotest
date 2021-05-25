<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sim_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->id();
            $table->uuid('country_id')->nullable();
            $table->uuid('carrier_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->uuid('sim_card_status_id')->nullable();
            $table->uuid('device_id')->nullable();
            $table->uuid('tenant_id')->nullable();
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
        Schema::dropIfExists('sim_cards');
    }
}
