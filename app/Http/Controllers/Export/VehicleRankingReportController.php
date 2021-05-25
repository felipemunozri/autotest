<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Domain\Helpers\Reports\VehicleRankingHelper;

class VehicleRankingReportController extends Controller
{
    public function download(Request $request, Tenant $tenant)
    {
        $query = \http_build_query([
            'url' => route('api.exports.vehicle-ranking.view', [
                'tenant' => $tenant,
                /* 'from' => $request->input('from'),
                'to' => $request->input('to'), */
            ]),
            'nombre' => 'Vehículos_más_robados_'.Carbon::now()->format('d-m-Y'),
            'download' => 'true',
            'footer' => 'Auto Seguro 365 - '.Carbon::now()->year,
        ]);

        $url = config('url.render')."/raw?$query";

        return redirect($url);
    }

    public function view(Request $request, Tenant $tenant)
    {
        $paginate = $request->has('page');
        $perPage = $request->input('per_page', 30);
        
        $query = VehicleRankingHelper::getRankingByTenant($tenant->id);
        $data = $paginate ? $query->paginate($perPage) : $query->get();

        return view('export.vehicle_ranking', [
            'ranking' => $data
        ]);
    }
}
