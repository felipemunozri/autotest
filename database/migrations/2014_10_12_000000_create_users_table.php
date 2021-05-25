<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            //$table->id();
            $table->uuid('id')->primary();
            //$table->string('uuid')->nullable()->default(\Illuminate\Support\Str::uuid());
            $table->string('dni')->nullable()->unique();
            $table->string('name');
            $table->string('second_name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('second_lastname')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('api_token')->nullable()->default(Hash::make(Uuid::uuid4()));
            $table->string('provider')->nullable();
            $table->string('provider_id')->unique()->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
