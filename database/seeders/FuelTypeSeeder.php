<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fuel_types')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Gasolina', 'gasoline'],
            ['Diésel', 'diesel'],
        ];
        $data = array_map(function($element) use ($timestamp) {
            return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'code' => $element[1]
            ];
        }, $data);

        DB::table('fuel_types')->insert($data);
    }
}
