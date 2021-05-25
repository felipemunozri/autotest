<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventCodeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_code_status')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Pendiente', 'pending'],
            ['Validado', 'validated'],
            ['Expirado', 'expired']
        ];
        $data = array_map(function($element) use ($timestamp) {
           return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'code' => $element[1]
           ];
        }, $data);

        DB::table('event_code_status')->insert($data);
    }
}
