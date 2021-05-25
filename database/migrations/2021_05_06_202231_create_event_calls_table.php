<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_calls', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('beneficiary_id');
            $table->uuid('user_id')->nullable();
            $table->uuid('event_id');
            $table->timestamp('call_start')->nullable();
            $table->timestamp('call_end')->nullable();
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
        Schema::dropIfExists('event_contacts');
    }
}
