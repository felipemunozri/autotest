<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Models\MetabaseReport;
use App\Http\Resources\GenericResource;

class MetabaseReportController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new MetabaseReport();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tenantId = $this->currentTenantId();

        $paginate = $request->has('page');
        $perPage = $request->input('per_page', 30);

        $filters = $request->only(['code']);

        $data = $this->filtrar($filters)
            ->where('tenant_id', $tenantId);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'metabase_report_id' => 'required',
            'code' => 'required',
        ]);

        $include = $request->input('include', []);
        $tenantId = $this->currentTenantId();

        $metabaseReport = new MetabaseReport();
        $metabaseReport->fill($params);
        $metabaseReport->tenant_id = $tenantId;
        $metabaseReport->save();

        $metabaseReport->load($include);

        return GenericResource::make($metabaseReport);
    }

    public function update(Request $request, MetabaseReport $metabaseReport)
    {
        $include = $request->input('include', []);

        $metabaseReport->fill($request->all());
        $metabaseReport->save();

        $metabaseReport->load($include);
        return GenericResource::make($metabaseReport);
    }
}
