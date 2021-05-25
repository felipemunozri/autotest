<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\Country;
use App\Models\FuelType;
use Illuminate\Http\Request;

class FuelTypeController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new FuelType();
    }

    public function index(Request $request)
    {
        $params = $request->only(['name', 'code']);

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, FuelType $fuelType)
    {
        $include = $request->input('include', []);
        $fuelType->load($include);
        return GenericResource::make($fuelType);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'required',
            'code' => 'required',
        ]);

        $include = $request->input('include', []);

        $fuelType = new FuelType();
        $fuelType->fill($params);
        $fuelType->save();

        $fuelType->load($include);

        return GenericResource::make($fuelType);
    }

    public function update(Request $request, FuelType $fuelType)
    {
        $include = $request->input('include', []);

        $fuelType->fill($request->all());
        $fuelType->save();

        $fuelType->load($include);
        return GenericResource::make($fuelType);
    }

    public function delete(Request $request, FuelType $fuelType)
    {
        $fuelType->delete();
        return GenericResource::make($fuelType);
    }
}
