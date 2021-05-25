<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            //$table->id();
            //$table->string('uuid')->unique()->default(\Illuminate\Support\Str::uuid());
            $table->string('dni')->nullable()->unique();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('address')->nullable()->nullable();
            $table->string('contact')->nullable()->nullable();
            $table->string('logo_url')->nullable()->nullable();
            $table->uuid('country_id')->nullable();
            $table->uuid('tenant_status_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
