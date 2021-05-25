<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vehicle;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceTest extends TestCase
{
    /**
    * @test
    */
    public function getDevices()
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
            'phone_number' => '+56991534382',
        ];

        $this->withoutExceptionHandling();
        //$respuesta = $this->get( "/api/v1/devices", $data)->response->getContent();
        $respuesta = $this->json('get', "/api/v1/devices", $data)   ;

        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }

    /**
     * @test
     */
    public function getDevice()
    {
        Sanctum::actingAs(
        //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
        //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');
        $device = Device::query()->firstOrFail();

        $data = [
            //'plate_number' => 'DWZG76',
            //'phone_number' => '+56991534382',
        ];

        $this->withoutExceptionHandling();
        $respuesta = $this->json('get', "/api/v1/devices/$device->id", $data);

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }

    /**
     * @test
     */
    public function getDeviceStatus()
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
        $respuesta = $this->json('get', "/api/v1/device-status", $data);

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }

    /**
     * @test
     */
    public function getExportDeviceQr()
    {
        Sanctum::actingAs(
        //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
        //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');
        $tenant = Tenant::query()->firstOrFail();

        $data = [
        ];

        $this->withoutExceptionHandling();
        $respuesta = $this->json('get', "/api/v1/public/exports/$tenant->id/qr-devices/pdf", $data);

        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }
}
