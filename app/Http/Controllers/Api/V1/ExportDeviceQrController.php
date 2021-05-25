<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExportDeviceQrController extends BaseApiController
{
    public function index(Request $request, Tenant $tenant)
    {
        $query = \http_build_query([
            'url' => route('exports.qr-devices.view', [
                'tenant' => $tenant,
                //'from' => $request->input('from'),
                //'to' => $request->input('to'),
                //'search' => $request->input('search'),
            ]),
            'nombre' => 'qr_dispositivos'.Str::random(6),
            'download' => 'false',
            'footer' => 'Auto Seguro 365 - '.Carbon::now()->year,
        ]);

        $url = config('url.render')."/raw?$query";

        return redirect($url);
    }
}
