<?php

namespace App\Helpers\DeviceSchemas\GPS103;

use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;
use App\Domain\Helpers\ValidatorsHelper;
use Carbon\Carbon;
use App\Helpers\DeviceSchemas\DeviceResponseSchemaInterface;
use App\Helpers\DeviceSchemas\GPS103\GPS103LocateResponseSchema;

class GPS103ExampleSchema
{
    private $action_type;

    public function __construct($action_type) {
        $this->action_type = $action_type;
    }

    public function getExampleSchema()
    {
        $default = null;

        $actions = [
            'begin' => fn() => $this->beginSchema(),
            'locate' => fn() => $this->locateSchema(),
            'turn_on' => fn()  => $this->turnOnSchema(),
            'turn_off' => fn() => $this->turnOffSchema(),
        ];

        return $actions[$this->action_type]();
    }

    private function beginSchema()
    {
        return 'begin ok!';    
    }

    private function locateSchema ()
    {
        return 'lat:-39.288950
        long:-71.931510
        speed:0.00
        T:21/03/04 04:51
        http://maps.google.com/maps?f=q&q=-39.288950,-71.931510&z=16
        Pwr: ON Door: OFF ACC: OFF';
    }

    private function turnOnSchema ()
    {
        return 'Resume engine Succeed';
    }

    private function turnOffSchema ()
    {
        return 'Stop engine Succeed';
    }
}
