<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('action_types')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Envío SMS','send-sms']
        ];
        $data = array_map(function($element) use ($timestamp) {
           return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'code' => $element[1]
           ];
        }, $data);

        DB::table('action_types')->insert($data);
    }
}
