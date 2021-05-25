<?php


namespace App\Domain\Helpers;

use App\Models\Device;
use App\Models\DeviceStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DeviceHelper
{
    public function getDeviceCountsByStatus($tenantId)
    {
        $devicesStatus = DeviceStatus::query()->get();

        $devices = Device::query()->where('tenant_id', $tenantId)->get();

        $data = [];

        foreach ($devicesStatus as $status) {
            $devicesFiltered = $devices->where('device_status_id', $status->id)->count();
            $data[$status->name] = $devicesFiltered;
        }

        return (object)$data;
    }
}
