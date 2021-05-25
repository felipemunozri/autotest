<?php

namespace App\Helpers\DeviceSchemas\GPS103;

use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;
use App\Domain\Helpers\ValidatorsHelper;
use Carbon\Carbon;
use App\Helpers\DeviceSchemas\DeviceResponseSchemaInterface;
use App\Helpers\DeviceSchemas\GPS103\GPS103LocateResponseSchema;

class GPS103Schema
{
    private $response_message;

    public function __construct($response_message) {
        $this->response_message = $response_message;
    }

    public function getValidSchema()
    {
        $default = null;

        $actions = [
            'begin' => fn() => $this->beginSchema(),
            'locate' => fn() => $this->locateSchema(),
            'turn_on' => fn()  => $this->turnOnSchema(),
            'turn_off' => fn() => $this->turnOffSchema(),
        ];


        foreach ($actions as $key => $value) {
            $action = $actions[$key]();
            
            if($action->is_valid) {
                $default = $action;
                break;
            }
        }
        return $default;
    }

    private function beginSchema()
    {
        $data = (object)[
            "schema" => (object) [ "message" => $this->response_message ],
            "is_valid" => false,
            "type" => "begin",
            "model" => 'GPS103'
        ];

        if($this->response_message === 'begin ok!') {
            $data->is_valid = true;
        } 
        return $data;    
    }

    private function locateSchema ()
    {
        $locate_schema = new GPS103LocateResponseSchema();
        $schema = $locate_schema->parseFormat($this->response_message);
        $is_valid = $locate_schema->isValid();
        $data = (object)[
            "schema" => $schema,
            "is_valid" => $is_valid,
            "type" => "locate",
            "model" => 'GPS103'
        ];

        return $data;
    }

    private function turnOnSchema ()
    {
        $data = (object)[
            "schema" => (object) [ "message" => $this->response_message ],
            "is_valid" => false,
            "type" => "turn_on",
            "model" => 'GPS103'
        ];

        if($this->response_message === 'Resume engine Succeed') {
            $data->is_valid = true;
        } 
        return $data;
    }

    private function turnOffSchema ()
    {
        $data = (object)[
            "schema" => (object) [ "message" => $this->response_message ],
            "is_valid" => false,
            "type" => "turn_off",
            "model" => 'GPS103'
        ];
        
        if($this->response_message === 'Stop engine Succeed' || $this->response_message === 'It will be executed after speed less than 20KM/H') {
            $data->is_valid = true;
        }
        return $data;
    }
}
