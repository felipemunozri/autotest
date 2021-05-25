<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Models\User;
use App\Models\Vehicle;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabdataase;

class SecurityCodeTest extends TestCase
{
    /**
     * @test
     */
    public function getDashboardSummary()
    {
        Sanctum::actingAs(
        //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
        //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');

        $this->withoutExceptionHandling();
        $respuesta = $this->json('get', "/api/v1/summary/dashboard");

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }

    /**
    * @test
    */
    public function getEventSummary()
    {
        Sanctum::actingAs(
            //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
            //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');

        $this->withoutExceptionHandling();
        $respuesta = $this->json('get', "/api/v1/summary/events");

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }
}
