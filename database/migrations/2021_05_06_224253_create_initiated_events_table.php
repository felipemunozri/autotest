<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitiatedEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initiated_events', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('tenant_id')->nullable();
            $table->uuid('beneficiary_id');
            $table->uuid('vehicle_id')->nullable();
            $table->dateTime('event_date')->nullable();
            $table->uuid('received_by')->nullable();
            $table->string('detail', 2000)->nullable();
            $table->string('observation', 2000)->nullable();
            $table->uuid('event_status_id')->nullable();
            $table->string('reason', 2000)->nullable();
            $table->json('origin')->nullable();
            $table->time('event_time')->nullable();
            $table->date('event_date')->change();
            $table->uuid('event_result_id')->nullable();
            $table->timestamp('event_start')->nullable();
            $table->timestamp('event_end')->nullable();
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
        Schema::dropIfExists('archived_events');
    }
}
