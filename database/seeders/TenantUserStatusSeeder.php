<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TenantUserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tenant_user_status')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Activo', 'active'],
            ['Inactivo', 'inactive'],
        ];
        $data = array_map(function($element) use ($timestamp) {
            return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'code' => $element[1],
               'created_at' => $timestamp,
               'updated_at' => $timestamp
            ];
        }, $data);

        DB::table('tenant_user_status')->insert($data);
    }
}
