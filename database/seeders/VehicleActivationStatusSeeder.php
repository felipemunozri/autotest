<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VehicleActivationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicle_activation_status')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Iniciado', 'started'],
            ['Activado', 'activated'],
            ['Fallido', 'failed']
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

        DB::table('vehicle_activation_status')->insert($data);
    }
}
