<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vehicle;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventQuestionTest extends TestCase
{
    /**
    * @test
    */
    public function getEventQuestions()
    {
        Sanctum::actingAs(
            //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
            //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');
        $data = [
        ];

        $this->withoutExceptionHandling();
        $respuesta = $this->get( "/api/v1/event-questions", $data);

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }
}
