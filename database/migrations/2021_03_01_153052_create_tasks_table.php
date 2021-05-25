<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->id();
            $table->uuid('tenant_id');
            $table->uuid('event_id')->nullable();
            $table->uuid('vehicle_id')->nullable();
            $table->uuid('executed_by')->nullable();
            $table->uuid('action_id')->nullable();
            $table->string('code')->nullable();
            $table->string('observation')->nullable();
            $table->jsonb('detail')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
