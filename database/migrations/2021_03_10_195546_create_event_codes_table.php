<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id')->nullable();
            $table->string('code', 6)->nullable();
            $table->uuid('beneficiary_id')->nullable();
            $table->timestamp('duration')->nullable();
            $table->uuid('status_id')->nullable();
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
        Schema::dropIfExists('event_codes');
    }
}
