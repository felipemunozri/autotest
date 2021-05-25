<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Vehicle;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailTest extends TestCase
{
    /**
     * @test
     */
    public function sendPasswordResetEmail()
    {
        Sanctum::actingAs(
        //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
        //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');
        $data = [
            'email' => 'admin@autoseguro.com',
            'url' => 'http://autoseguro.test',
        ];

        $this->withoutExceptionHandling();
        //$respuesta = $this->get( "/api/v1/devices", $data)->response->getContent();
        $respuesta = $this->json('post', "/api/v1/public/password/email", $data)   ;
        $respuesta->dump();
        $respuesta->assertSuccessful();
        $this->assertNotEmpty($respuesta);
    }

    /**
    * @test
    */
    public function sendWelcomeEmail()
    {
        Sanctum::actingAs(
            //User::query()->where('id', 'e94fbe67-3fb8-42d3-9a98-192750b86c03')->firstOrFail(),
            //User::factory()->create(),
            User::query()->firstOrFail(),
            ['view-tasks'],
        );

        $this->login(null, 'api');
        $data = [
            'plate_number' => 'DWZG76',
            'beneficiary_id' => '86dcf8a0-67e6-46ea-a665-523d7040f49b',
            'tenant_id' => '268111c2-79ce-40b8-a50a-6d1e587915b8',
            'email' => 'carlos.henriquez.lara@gmail.com'
        ];

        $this->withoutExceptionHandling();
        //$respuesta = $this->get( "/api/v1/devices", $data)->response->getContent();
        $respuesta = $this->json('post', "/api/v1/mails/welcome", $data)   ;
        $respuesta->dump();
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
