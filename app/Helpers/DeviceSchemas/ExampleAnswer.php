<?php

namespace App\Helpers\DeviceSchemas;

use App\Helpers\DeviceSchemas\GPS103\GPS103ExampleSchema;

class ExampleAnswer
{
    static function generate($model, $action_type){
        $example_answer = null;
        switch ($model) {
            case 'GPS103':
                $schema = new GPS103ExampleSchema($action_type);
                $example_answer = $schema->getExampleSchema();
                break;
            default:
                break;
        }
        return $example_answer;
    }
}