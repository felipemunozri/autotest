<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class   AddTenantToPermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->unsigned();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->unsigned();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->unsigned();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) {
            $table->dropColumn("tenant_id");
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) {
            $table->dropColumn("tenant_id");
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropColumn('tenant_id');
            $table->dropColumn('description');
        });
    }
}
