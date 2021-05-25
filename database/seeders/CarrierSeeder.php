<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('carriers')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Entel','CL','entel', 50],
            ['Movistar','CL','movistar', 50],
            ['Claro','CL','claro', 50],
            ['Wom','CL','wom', 50]
        ];
        $data = array_map(function($element) use ($timestamp) {
            $country = DB::table('countries')->where('code', $element[1])->first();
           return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'code' => $element[2],
               'sms_cost' => $element[3],
               'country_id' => $country->id,
               'created_at' => $timestamp,
               'updated_at' => $timestamp
           ];
        }, $data);

        DB::table('carriers')->insert($data);
    }
}
