<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\EventHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventSummaryController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new Event();
    }

    public function index(Request $request)
    {
        $data = (new EventHelper())->getSummary($this->currentTenantId());

        return response()->json($data, 200);
    }
}
