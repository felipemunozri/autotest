<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DeviceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('device_status')->truncate();

        $data = [
            ['Disponibe','enabled'],
            ['Preparado','prepared'],
            ['Instalado','installed'],
            ['Activado','activated'],
            ['Deshabilitado','disabled'],
        ];
        $data = array_map(function($element) {
            return [
                'id' => Uuid::uuid4(),
                'name' => $element[1],
                'description' => $element[0],
            ];
        }, $data);

        DB::table('device_status')->insert($data);
    }
}
