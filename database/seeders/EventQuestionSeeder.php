<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class EventQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_questions')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['Hubieron heridos', 'injured', 'Hubieron heridos', 1, 1],
            ['Personas afectadas', 'number_affected', 'Personas afectadas', 2, 1],
            ['El siniestro fue con violencia', 'in-progress', 'El siniestro fue con violencia', 3, 1],
            //['Estado del vehículo', 'vehicle_status', 'Estado del vehículo', 4, 1],
        ];
        $data = array_map(function($element) use ($timestamp) {
            return [
                'id' => Uuid::uuid4(),
                'name' => $element[0],
                'code' => $element[1],
                'description' => $element[2],
                'order' => $element[3]
            ];
        }, $data);

        DB::table('event_questions')->insert($data);

        /*Alternativas a preguntas*/
        DB::table('event_questions_alternatives')->truncate();

        $alternatives1 = [
            ['id' => Uuid::uuid4(), 'event_question_id' => $data[0]['id']->toString(), 'code' => 'q1-a1', 'body' => 'Sí', 'order' => 1],
            ['id' => Uuid::uuid4(), 'event_question_id' => $data[0]['id']->toString(), 'code' => 'q1-a2', 'body' => 'No', 'order' => 2],
        ];

        DB::table('event_questions_alternatives')->insert($alternatives1);

        $alternatives2 = [
            ['id' => Uuid::uuid4(), 'event_question_id' => $data[1]['id']->toString(), 'code' => 'q2-a1', 'body' => '1', 'order' => 1],
            ['id' => Uuid::uuid4(), 'event_question_id' => $data[1]['id']->toString(), 'code' => 'q1-a2', 'body' => '2', 'order' => 2],
            ['id' => Uuid::uuid4(), 'event_question_id' => $data[1]['id']->toString(), 'code' => 'q1-a3', 'body' => '3', 'order' => 3],
            ['id' => Uuid::uuid4(), 'event_question_id' => $data[1]['id']->toString(), 'code' => 'q1-a4', 'body' => '4 o más', 'order' => 4],
        ];
        DB::table('event_questions_alternatives')->insert($alternatives2);

        $alternatives3 = [
            ['id' => Uuid::uuid4(), 'event_question_id' => $data[2]['id']->toString(), 'code' => 'q3-a1', 'body' => 'Sí', 'order' => 1],
            ['id' => Uuid::uuid4(), 'event_question_id' => $data[2]['id']->toString(), 'code' => 'q3-a2', 'body' => 'No', 'order' => 2],
        ];
        DB::table('event_questions_alternatives')->insert($alternatives3);

//        $alternatives4 = [
//            ['event_question_id' => $data[3]['id'], 'code' => 'q1-a1', 'body' => 'Sí', 'order' => 1],
//            ['event_question_id' => $data[3]['id'], 'code' => 'q1-a2', 'body' => 'No', 'order' => 2],
//        ];
//        DB::table('event_questions_alternatives')->insert($alternatives1);
    }
}
