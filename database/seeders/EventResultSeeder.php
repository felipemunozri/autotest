<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class EventResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_results')->truncate();

        $data = [
            ['Recuperado', 1, true, 'retrieved'],
            ['Recuperado con daños', 2, true, 'retrieved-with-damage'],
            ['Pérdida total', 3, true, 'total-loss'],
            ['No recuperado', 4, true, 'not-retrieved'],
        ];
        $data = array_map(function($element) {
            return [
                'id' => Uuid::uuid4(),
                'name' => $element[0],
                'order' => $element[1],
                'visible' => $element[2],
                'code' => $element[3]
            ];
        }, $data);

        DB::table('event_results')->insert($data);
    }
}
