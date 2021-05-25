<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_records', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('log_name')->nullable();
            $table->string('description');
            $table->uuid('tenant_id');
            $table->string('tenant_name');
            $table->uuidMorphs('target');
            $table->uuid('user_id');
            $table->string('user_name');
            $table->string('user_lastname');
            $table->string('user_email');
            $table->jsonb('attributes')->nullable();
            $table->jsonb('old')->nullable();
            $table->timestamps();
            $table->index('log_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_records');
    }
}
