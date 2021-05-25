<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\OperationHelper;
use App\Domain\Helpers\VehicleHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ValidateController extends BaseApiController
{
    public function check(Request $request)
    {
        $plateNumber = $request->input('plate_number');
        $dni = $request->input('dni');

        $result = (new VehicleHelper())->isBeneficiary( $this->currentTenantId(), $plateNumber, $dni);

        return response()->json($result);
    }

    public function send(Request $request, $to)
    {
        $code = \mt_rand(100000, 999999);
        $body = 'El código seguridad para validacicón: '.$code;

        $result = (new OperationHelper())->sendSms($to, $body);
    }
}
