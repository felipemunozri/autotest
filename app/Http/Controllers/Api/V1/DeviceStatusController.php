<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\DeviceStatus;
use Illuminate\Http\Request;

class DeviceStatusController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new DeviceStatus();
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

    public function show(Request $request, DeviceStatus $deviceStatus)
    {
        $include = $request->input('include', []);
        $deviceStatus->load($include);
        return GenericResource::make($deviceStatus);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'required',
            'description' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $deviceStatus = new DeviceStatus();
        $deviceStatus->fill($params);
        $deviceStatus->save();

        $deviceStatus->load($include);

        return GenericResource::make($deviceStatus);
    }

    public function update(Request $request, DeviceStatus $deviceStatus)
    {
        $include = $request->input('include', []);

        $deviceStatus->fill($request->all());
        $deviceStatus->save();

        $deviceStatus->load($include);
        return GenericResource::make($deviceStatus);
    }

    public function delete(Request $request, DeviceStatus $deviceStatus)
    {
        $deviceStatus->delete();
        return GenericResource::make($deviceStatus);
    }
}
