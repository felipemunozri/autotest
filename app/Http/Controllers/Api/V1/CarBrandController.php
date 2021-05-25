<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\CarBrand;
use Illuminate\Http\Request;

class CarBrandController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new CarBrand();
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

    public function show(Request $request, CarBrand $carBrand)
    {
        $include = $request->input('include', []);
        $carBrand->load($include);
        return GenericResource::make($carBrand);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $carBrand = new CarBrand();
        $carBrand->fill($params);
        $carBrand->save();
        (new AuditHelper())->storeAuditRecord($carBrand, 'created');

        $carBrand->load($include);

        return GenericResource::make($carBrand);
    }

    public function update(Request $request, CarBrand $carBrand)
    {
        $include = $request->input('include', []);

        $auditOld = $carBrand;

        $carBrand->fill($request->all());
        $carBrand->save();

        (new AuditHelper())->storeAuditRecord($carBrand, 'updated', $auditOld);

        $carBrand->load($include);
        return GenericResource::make($carBrand);
    }

    public function delete(Request $request, CarBrand $carBrand)
    {
        $carBrand->delete();
        (new AuditHelper())->storeAuditRecord($carBrand, 'deleted');
        return GenericResource::make($carBrand);
    }
}
