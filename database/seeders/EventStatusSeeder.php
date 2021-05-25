<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_status')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Creado', 'created'],
            ['Validado', 'validated'],
            ['En Progreso', 'in-progress'],
            ['Finalizado', 'finished']
        ];
        $data = array_map(function($element) use ($timestamp) {
           return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'code' => $element[1]
           ];
        }, $data);

        DB::table('event_status')->insert($data);
    }
}
