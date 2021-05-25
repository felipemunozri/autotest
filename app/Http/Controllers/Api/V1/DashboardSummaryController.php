<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\DeviceHelper;
use App\Domain\Helpers\EventHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardSummaryController extends BaseApiController
{
    public function index(Request $request)
    {
        $eventsSummary = (new EventHelper())->getSummary($this->currentTenantId(), true);

        $deviceSummary = (new DeviceHelper())->getDeviceCountsByStatus($this->currentTenantId());

        $data = (object) [
            'events' => $eventsSummary,
            'devices' => $deviceSummary,
        ];

        return response()->json($data, 200);
    }
}
