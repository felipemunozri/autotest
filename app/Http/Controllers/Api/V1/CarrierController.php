<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\Carrier;
use Illuminate\Http\Request;

class CarrierController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new Carrier();
    }

    public function index(Request $request)
    {
        $params = $request->only(['name', 'country_id']);

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, Carrier $carrier)
    {
        $include = $request->input('include', []);
        $carrier->load($include);
        return GenericResource::make($carrier);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'sometimes',
            'country_id' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $carrier = new Carrier();
        $carrier->fill($params);
        $carrier->save();

        $carrier->load($include);

        return GenericResource::make($carrier);
    }

    public function update(Request $request, Carrier $carrier)
    {
        $include = $request->input('include', []);

        $carrier->fill($request->all());
        $carrier->save();

        $carrier->load($include);
        return GenericResource::make($carrier);
    }

    public function delete(Request $request, Carrier $carrier)
    {
        $carrier->delete();
        return GenericResource::make($carrier);
    }
}
