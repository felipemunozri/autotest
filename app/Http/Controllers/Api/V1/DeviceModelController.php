<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GenericResource;
use App\Models\DeviceModel;
use Illuminate\Http\Request;

class DeviceModelController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new DeviceModel();
    }

    public function index(Request $request)
    {
        $params = $request->only(['name']);

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, DeviceModel $deviceModel)
    {
        $include = $request->input('include', []);
        $deviceModel->load($include);
        return GenericResource::make($deviceModel);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'required',
            'description' => 'sometimes',
            'detail' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $deviceModel = new DeviceModel();
        $deviceModel->fill($params);
        $deviceModel->save();

        $deviceModel->load($include);

        return GenericResource::make($deviceModel);
    }

    public function update(Request $request, DeviceModel $deviceModel)
    {
        $include = $request->input('include', []);

        $deviceModel->fill($request->all());
        $deviceModel->save();

        $deviceModel->load($include);
        return GenericResource::make($deviceModel);
    }

    public function delete(Request $request, DeviceModel $deviceModel)
    {
        $deviceModel->delete();
        return GenericResource::make($deviceModel);
    }
}
