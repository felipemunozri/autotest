<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\SimCardStatus;
use Illuminate\Http\Request;

class SimCardStatusController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new SimCardStatus();
    }

    public function index(Request $request)
    {
        $params = $request->only(['name']);

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            //->where('tenant_id', $this->currentTenantId())
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, SimCardStatus $simCardStatus)
    {
        $include = $request->input('include', []);
        $simCardStatus->load($include);
        return GenericResource::make($simCardStatus);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'required',
            'description' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $simCardStatus = new SimCardStatus();
        $simCardStatus->fill($params);
        $SimCardStatus->save();

        $simCardStatus->load($include);

        return GenericResource::make($simCardStatus);
    }

    public function update(Request $request, SimCardStatus $simCardStatus)
    {
        $include = $request->input('include', []);

        $simCardStatus->fill($request->all());
        $simCardStatus->save();

        $simCardStatus->load($include);
        return GenericResource::make($simCardStatus);
    }

    public function delete(Request $request, SimCardStatus $simCardStatus)
    {
        $simCardStatus->delete();
        return GenericResource::make($simCardStatus);
    }
}
