<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventQuestionsAlternativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_questions_alternatives', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('event_question_id');
            $table->string('code')->nullable();
            $table->string('body');
            $table->integer('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_questions_alternatives');
    }
}
