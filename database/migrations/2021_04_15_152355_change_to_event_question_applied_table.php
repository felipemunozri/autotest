<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeToEventQuestionAppliedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_questions_applied', function (Blueprint $table) {
            $table->uuid('alternative_id')->nullable();
            $table->float('number_answer')->nullable();
            $table->string('answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_questions_applied', function (Blueprint $table) {
            $table->dropColumn('alternative_id');
            $table->dropColumn('number_answer');
            $table->string('answer')->change();
        });
    }
}
