<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Vehicle;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OperationSMSTest extends TestCase
{
    /**
    * @test
    */
    public function sendLocationmessage()
    {
        Sanctum::actingAs(
            //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
            //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );


        $vehicle = Vehicle::query()->where('plate_number', '=', 'DWZG76')->first();

        $this->login(null, 'api');
        $data = [
            //'periodo' => 2020,
            //'mes' => 12,
            'action' => 'locate'
        ];

        $this->withoutExceptionHandling();
        //$respuesta = $this->json('post', "/client/api/v1/operation-sms/$vehicle->id", $data);
        $respuesta = $this->json('post', "/api/v1/operation-sms/send/$vehicle->id", $data);

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }

    /**
     * @test
     */
    public function get_vehiculos()
    {
        //$this->login(null, 'api');

        $this->withoutExceptionHandling();
        $respuesta = $this->json('get', '/api/v1/vehicles');

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }
}
