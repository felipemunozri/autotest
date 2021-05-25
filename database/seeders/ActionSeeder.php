<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actions')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Iniciar','begin'],
            ['Status','check'],
            ['Localizar','locate'],
            ['Encender','turn_on'],
            ['Apagar','turn_off']
        ];
        $data = array_map(function($element) use ($timestamp) {
            $action_type = DB::table('action_types')->where('code', 'send-sms')->first();
            return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'code' => $element[1],
               'action_type_id' => $action_type->id,
               'enabled' => true
            ];
        }, $data);

        DB::table('actions')->insert($data);
    }
}
