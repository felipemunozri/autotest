<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->id();
            $table->uuid('tenant_id')->nullable();
            $table->uuid('beneficiary_id');
            $table->uuid('vehicle_id')->nullable();
            $table->dateTime('event_date')->nullable();
            $table->uuid('received_by')->nullable();
            $table->string('detail', 2000)->nullable();
            $table->string('observation', 2000)->nullable();
            $table->uuid('event_status_id')->nullable();
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
        Schema::dropIfExists('events');
    }
}
