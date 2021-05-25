<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CarBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('car_brands')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Acura','ACURA'],
            ['Alfa Romeo','ALFA'],
            ['AMC','AMC'],
            ['Aston Martin','ASTON'],
            ['Audi','AUDI'],
            ['Avanti','AVANTI'],
            ['Bentley','BENTL'],
            ['BMW','BMW'],
            ['Buick','BUICK'],
            ['Cadillac','CAD'],
            ['Chevrolet','CHEV'],
            ['Chrysler','CHRY'],
            ['Citroën', 'CIT'],
            ['Daewoo','DAEW'],
            ['Daihatsu','DAIHAT'],
            ['Datsun','DATSUN'],
            ['DeLorean','DELOREAN'],
            ['Dodge','DODGE'],
            ['Eagle','EAGLE'],
            ['Ferrari','FER'],
            ['FIAT','FIAT'],
            ['Fisker','FISK'],
            ['Ford','FORD'],
            ['Freightliner','FREIGHT'],
            ['Geo','GEO'],
            ['GMC','GMC'],
            ['Honda','HONDA'],
            ['HUMMER','AMGEN'],
            ['Hyundai','HYUND'],
            ['Infiniti','INFIN'],
            ['Isuzu','ISU'],
            ['Jaguar','JAG'],
            ['Jeep','JEEP'],
            ['Kia','KIA'],
            ['Lamborghini','LAM'],
            ['Lancia','LAN'],
            ['Land Rover','ROV'],
            ['Lexus','LEXUS'],
            ['Lincoln','LINC'],
            ['Lotus','LOTUS'],
            ['Maserati','MAS'],
            ['Maybach','MAYBACH'],
            ['Mazda','MAZDA'],
            ['McLaren','MCLAREN'],
            ['Mercedes-Benz','MB'],
            ['Mercury','MERC'],
            ['Merkur','MERKUR'],
            ['MINI','MINI'],
            ['Mitsubishi','MIT'],
            ['Nissan','NISSAN'],
            ['Oldsmobile','OLDS'],
            ['Peugeot','PEUG'],
            ['Plymouth','PLYM'],
            ['Pontiac','PONT'],
            ['Porsche','POR'],
            ['RAM','RAM'],
            ['Renault','REN'],
            ['Rolls-Royce','RR'],
            ['Saab','SAAB'],
            ['Saturn','SATURN'],
            ['Scion','SCION'],
            ['smart','SMART'],
            ['SRT','SRT'],
            ['Sterling','STERL'],
            ['Subaru','SUB'],
            ['Suzuki','SUZUKI'],
            ['Tesla','TESLA'],
            ['Toyota','TOYOTA'],
            ['Triumph','TRI'],
            ['Volkswagen','VOLKS'],
            ['Volvo','VOLVO'],
            ['Yugo','YUGO'],
        ];
        $data = array_map(function($element) use ($timestamp) {
           return [
               'id' => Uuid::uuid4(),
               'name' => $element[0]
           ];
        }, $data);

        DB::table('car_brands')->insert($data);
    }
}
