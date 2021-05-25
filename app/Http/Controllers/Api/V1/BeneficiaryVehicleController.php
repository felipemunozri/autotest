<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\Beneficiary;
use App\Models\BeneficiaryVehicle;
use Illuminate\Http\Request;

class BeneficiaryVehicleController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new BeneficiaryVehicle();
    }

    public function index(Request $request)
    {
        $params = $request->only(['beneficiary_id', 'vehicle_id']);

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'beneficiary_id' => 'required',
            'vehicle_id' => 'required',
            'owner' => 'sometimes',
        ]);
        $include = $request->input('include', []);

        $item = new BeneficiaryVehicle();
        $item->fill($params);
        $item->save();

        (new AuditHelper())->storeAuditRecord($item, 'Beneficiary related to vehicle created');

        $item->load($include);

        return GenericResource::make($item);
    }

    public function update(Request $request)
    {
        $params = $this->validate($request, [
            'beneficiary_id' => 'required',
            'vehicle_id' => 'required',
            'owner' => 'sometimes',
        ]);

        $record = BeneficiaryVehicle::query()
            ->where('beneficiary_id', $params['beneficiary_id'])
            ->where('vehicle_id', $params['vehicle_id'])
            ->firstOrFail();

        $auditOld = $record;

        $record->owner = $params['owner'];

        $record->save();

        (new AuditHelper())->storeAuditRecord($record, 'Beneficiary related to vehicle updated', $auditOld);

        return GenericResource::make($record);
    }

    public function delete(Request $request)
    {
        $params = $this->validate($request, [
            'beneficiary_id' => 'required',
            'vehicle_id' => 'required',
        ]);

        $record = BeneficiaryVehicle::query()
            ->where('beneficiary_id', $params['beneficiary_id'])
            ->where('vehicle_id', $params['vehicle_id'])
            ->first();

        if(!$record) {
            return response()->json([
                'data' => 'No existe asociación entre vehículo y beneficiario.'
            ], 422);
        }

        $record->delete();
        (new AuditHelper())->storeAuditRecord($record, 'Beneficiary related to vehicle deleted');

        return response()->json([
            'data' => 'Se ha eliminado la asociación.'
        ], 200);
    }
}
