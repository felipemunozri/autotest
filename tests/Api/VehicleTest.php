<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Vehicle;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityCodeTest extends TestCase
{
    /**
    * @test
    */
    public function getVehicles()
    {
        Sanctum::actingAs(
            //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
            //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');
        $data = [
            //'plate_number' => 'DWZG76',
            'dni' => '16960182-8',
        ];

        $this->withoutExceptionHandling();
        $respuesta = $this->json('get', "/api/v1/vehicles", $data);

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }
}
