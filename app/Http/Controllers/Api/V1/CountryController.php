<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GenericResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new Country();
    }

    public function index(Request $request)
    {
        $params = $request->only(['name', 'code', 'code_number', 'utc']);

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, Country $country)
    {
        $include = $request->input('include', []);
        $country->load($include);
        return GenericResource::make($country);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'sometimes',
            'code_number' => 'sometimes',
            'code' => 'sometimes',
            'utc' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $country = new Country();
        $country->fill($params);
        $country->save();

        $country->load($include);

        return GenericResource::make($country);
    }

    public function update(Request $request, Country $country)
    {
        $include = $request->input('include', []);

        $country->fill($request->all());
        $country->save();

        $country->load($include);
        return GenericResource::make($country);
    }

    public function delete(Request $request, Country $country)
    {
        $country->delete();
        return GenericResource::make($country);
    }
}
