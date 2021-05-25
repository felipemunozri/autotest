<?php

namespace App\Http\Controllers\Api\V1\Reports;

use App\Domain\Helpers\Reports\VehicleRankingHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleRankingController extends BaseApiController
{
    public function index(Request $request)
    {   
        $paginate = $request->has('page');
        $perPage = $request->input('per_page', 30);
        
        $query = VehicleRankingHelper::getRankingByTenant($this->currentTenantId());
        $data = $paginate ? $query->paginate($perPage) : $query->get();

        return response()->json($data, 200);
    }
}