<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\Beneficiary;
use Illuminate\Http\Request;

class BeneficiaryController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new Beneficiary();
    }

    public function index(Request $request)
    {
        $params = $request->only(['name', 'lastname', 'dni', 'email']);

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $vehicle_id = $request->input('vehicle_id', null);

        $data = $this->filtrar($params)
            ->with($include);

        if($vehicle_id) {
            $data = $data->whereHas('vehicles', function ($q) use ($vehicle_id) {
                $q->where('id', $vehicle_id);
            });
        }

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, Beneficiary $beneficiary)
    {
        if($beneficiary->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $include = $request->input('include', []);
        $beneficiary->load($include);
        return GenericResource::make($beneficiary);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'required',
            'lastname' => 'required',
            'dni' => 'required',
            'dni_serial_number' => 'sometimes',
            'phone_number' => 'sometimes',
            'email' => 'sometimes',
            'address' => 'sometimes',
        ]);
        $include = $request->input('include', []);

        $beneficiary = new Beneficiary();
        $beneficiary->fill($params);
        $beneficiary->save();

        (new AuditHelper())->storeAuditRecord($beneficiary, 'created');

        $beneficiary->load($include);

        return GenericResource::make($beneficiary);
    }

    public function update(Request $request, Beneficiary $beneficiary)
    {
        $include = $request->input('include', []);

        $auditOld = $beneficiary;

        $beneficiary->fill($request->all());
        $beneficiary->save();

        (new AuditHelper())->storeAuditRecord($beneficiary, 'updated', $auditOld);

        $beneficiary->load($include);
        return GenericResource::make($beneficiary);
    }

    public function delete(Request $request, Beneficiary $beneficiary)
    {
        $beneficiary->delete();
        (new AuditHelper())->storeAuditRecord($beneficiary, 'deleted');
        return GenericResource::make($beneficiary);
    }
}
