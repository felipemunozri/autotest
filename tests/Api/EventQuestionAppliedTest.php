<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Models\Event;
use App\Models\EventQuestion;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vehicle;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventQuestionAppliedTest extends TestCase
{
    /**
     * @test
     */
    public function storeAnswer()
    {
        Sanctum::actingAs(
        //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
        //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');

        $event = Event::query()->firstOrFail();
        $question = EventQuestion::query()->where('order', 1)->firstOrFail();

        $data = [
            'event_id' => $event->id,
            'event_question_id' => $question->id,
            'answer' => 'Sí'
        ];

        $this->withoutExceptionHandling();
        $respuesta = $this->json('post', "/api/v1/event-questions-applied", $data);

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }

    /**
    * @test
    */
    public function storeAnswerList()
    {
        Sanctum::actingAs(
            //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
            //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');

        $event = Event::query()->firstOrFail();
        $question1 = EventQuestion::query()->where('order', 1)->firstOrFail();
        $question2 = EventQuestion::query()->where('order', 2)->firstOrFail();
        $question3 = EventQuestion::query()->where('order', 3)->firstOrFail();
        $question4 = EventQuestion::query()->where('order', 4)->firstOrFail();

        $data = [
            'list' => [
                ['event_id' => $event->id, 'event_question_id' => $question1->id, 'answer' => 'Sí'],
                ['event_id' => $event->id, 'event_question_id' => $question2->id, 'answer' => 'Estoy reloco'],
                ['event_id' => $event->id, 'event_question_id' => $question3->id, 'answer' => '5'],
                ['event_id' => $event->id, 'event_question_id' => $question4->id, 'answer' => 'No'],
            ]
        ];

        $this->withoutExceptionHandling();
        $respuesta = $this->json('post',  "/api/v1/event-questions-applied/store-list", $data);

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }
}
