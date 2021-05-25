<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeviceModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('device_models')->truncate();
        $timestamp = Carbon::now();
        $data = [
            [
                'GPS103', 
                'gps103',
                [
                    'begin' => 'begin',
                    'locate' => 'fix010s001n',
                    'turn_on' => 'resume',
                    'turn_off' => 'stop',
                ]
            ],
        ];
        $data = array_map(function($element) use ($timestamp) {
            return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'code' => $element[1],
               'detail' => json_encode($element[2]),
               'created_at' => $timestamp,
               'updated_at' => $timestamp
            ];
        }, $data);

        DB::table('device_models')->insert($data);
    }
}
