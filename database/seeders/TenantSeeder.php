<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tenants')->truncate();
        $timestamp = Carbon::now();
        $country = DB::table('countries')->where('code', 'CL')->first();
        $data = [
            ['Pruebas AutoSeguro','pruebas-autoseguro','77109214-4',$country->id],
        ];
        $data = array_map(function($element) use ($timestamp) {
            return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'code' => $element[1],
               'dni' => $element[2],
               'country_id' => $element[3],
               'created_at' => $timestamp,
               'updated_at' => $timestamp
            ];
        }, $data);

        DB::table('tenants')->insert($data);
    }
}
