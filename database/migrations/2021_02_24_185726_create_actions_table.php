<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->id();
            $table->string('code');
            $table->string('name');
            $table->string('description', 1000)->nullable();
            $table->uuid('tenant_id')->nullable();
            $table->uuid('action_type_id')->nullable();
            $table->jsonb('detail')->nullable();
            $table->boolean('enabled')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
}
