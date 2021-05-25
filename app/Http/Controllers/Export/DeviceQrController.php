<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceStatus;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceQrController extends Controller
{
    public function download(Request $request, Tenant $tenant)
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

    public function view(Request $request, Tenant $tenant)
    {
        $status = $request->input('status', ['enabled', 'prepared', 'installed']);

        $deviceStatus = DeviceStatus::query()
            ->whereIn('name', $status)
            ->pluck('id');

        $devices = Device::query()->where('tenant_id', $tenant->id)
            ->whereIn('device_status_id', $deviceStatus)->get();

        //dd($devices);

        return view('print.qr_list')
            ->with([
                'devices' => $devices,
            ]);
    }
}
