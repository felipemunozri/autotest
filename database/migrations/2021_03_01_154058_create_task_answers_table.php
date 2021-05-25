<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->id();
            $table->uuid('event_id')->nullable();
            $table->uuid('task_id')->nullable();
            $table->uuid('vehicle_id')->nullable();
            $table->uuid('action_id')->nullable();
            $table->string('phone_number');
            $table->string('code');
            $table->json('detail');
            $table->uuid('task_answer_status_id')->nullable();
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
        Schema::dropIfExists('task_answers');
    }
}
