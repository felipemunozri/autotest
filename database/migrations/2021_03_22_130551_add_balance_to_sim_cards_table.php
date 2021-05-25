<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalanceToSimCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sim_cards', function (Blueprint $table) {
            $table->float('balance')->nullable()->default(0);
            $table->float('sms')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sim_cards', function (Blueprint $table) {
            $table->dropColumn('balance');
            $table->dropColumn('sms');
        });
    }
}
