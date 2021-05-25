<?php

namespace App\Helpers\DeviceSchemas;

use App\Helpers\DeviceSchemas\GPS103\GPS103Schema;

class ExtractSchema
{
    static function extract($message, $model){
        $data_schema = null;
        switch ($model) {
            case 'GPS103':
                $schema = new GPS103Schema($message);
                $data_schema = $schema->getValidSchema();
                break;
            default:
                break;
        }
        return $data_schema;
    }
}